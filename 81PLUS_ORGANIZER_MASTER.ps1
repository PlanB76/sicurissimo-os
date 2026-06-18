param(
    [string]$SourceRoot = "C:\MEMORIA81+",
    [string]$TargetName = "81PLUS_GLOBAL_MASTER",
    [string]$DriveMirrorRoot = "",
    [switch]$OpenAtEnd,
    [switch]$DryRun
)

$ErrorActionPreference = "Continue"

# ============================================================
# 81PLUS ORGANIZER MASTER
# Versione pulita ASCII per Windows PowerShell
# - Crea struttura progetto su Desktop
# - Analizza C:\MEMORIA81+
# - Estrae ZIP in temporaneo
# - Deduplica con SHA256
# - Tiene file piu recente
# - Smista nelle cartelle corrette
# - Crea report e manifest
# - Crea mirror Google Drive se trova cartella locale Drive
# NON cancella mai gli originali
# ============================================================

function Write-Info {
    param([string]$Message)
    Write-Host "[81PLUS] $Message" -ForegroundColor Cyan
}

function Write-Ok {
    param([string]$Message)
    Write-Host "[OK] $Message" -ForegroundColor Green
}

function Write-Warn {
    param([string]$Message)
    Write-Host "[WARN] $Message" -ForegroundColor Yellow
}

function New-Folder {
    param([string]$Path)
    if ($DryRun) {
        return
    }
    if (!(Test-Path -LiteralPath $Path)) {
        New-Item -ItemType Directory -Path $Path -Force | Out-Null
    }
}

function Safe-Name {
    param([string]$Name)
    $invalid = [System.IO.Path]::GetInvalidFileNameChars()
    foreach ($c in $invalid) {
        $Name = $Name.Replace($c, "_")
    }
    return $Name
}

function Get-SafeDestPath {
    param(
        [string]$Folder,
        [string]$FileName
    )

    $safe = Safe-Name $FileName
    $dest = Join-Path $Folder $safe

    if (!(Test-Path -LiteralPath $dest)) {
        return $dest
    }

    $base = [System.IO.Path]::GetFileNameWithoutExtension($safe)
    $ext = [System.IO.Path]::GetExtension($safe)
    $i = 2

    do {
        $candidate = Join-Path $Folder ("{0}__SAME_NAME_{1}{2}" -f $base, $i, $ext)
        $i++
    } while (Test-Path -LiteralPath $candidate)

    return $candidate
}

function Get-HashSafe {
    param([string]$Path)
    try {
        return (Get-FileHash -Algorithm SHA256 -LiteralPath $Path).Hash
    } catch {
        return $null
    }
}

function Detect-GoogleDriveRoot {
    $candidates = @(
        "$env:USERPROFILE\Google Drive\My Drive",
        "$env:USERPROFILE\Google Drive\Il mio Drive",
        "$env:USERPROFILE\My Drive",
        "$env:USERPROFILE\Il mio Drive",
        "$env:USERPROFILE\Desktop\Google Drive",
        "G:\My Drive",
        "G:\Il mio Drive",
        "H:\My Drive",
        "H:\Il mio Drive",
        "D:\Google Drive",
        "E:\Google Drive"
    )

    foreach ($p in $candidates) {
        if (Test-Path -LiteralPath $p) {
            return $p
        }
    }

    return ""
}

function Get-Category {
    param(
        [string]$FullPath,
        [string]$FileName
    )

    $text = ($FullPath + " " + $FileName).ToLowerInvariant()

    $rules = @(
        @{ Pattern = "master|blueprint|dna|decisioni|changelog|roadmap|naming|semantic|memoria"; Folder = "00_MASTER_VIVO" },
        @{ Pattern = "holding|planb|cash ltd|societa|governance|verbali|marchi|ip|domini"; Folder = "01_HOLDING_PLANB_CASH_LTD" },
        @{ Pattern = "legale|regolamento|privacy|cookie|gdpr|contratto|disclaimer|dpo|mica|ai act|termini"; Folder = "02_LEGALE_REGOLAMENTI_COMPLIANCE" },
        @{ Pattern = "hub1|81plus.net|sic-id|sic id|sso|kyc|dashboard|wallet|paygate|missione|ruota|piramide|api hub1"; Folder = "03_HUB1_81PLUS_NET" },
        @{ Pattern = "hub2|sicurissimo|dvr|pos|duvri|haccp|privacy gdpr|corsi|formazione|documenti|sicurezza"; Folder = "04_HUB2_SICURISSIMO" },
        @{ Pattern = "hub3|web3|saf|81x|nft|metaverso|dao|token|smart contract|wallet web3|marketplace web3"; Folder = "05_HUB3_WEB3_UTILITY" },
        @{ Pattern = "22 nodi|paygate81|doc81|academy81|network81|career81|equilibrium|pix81|genesys81|lock81|club81"; Folder = "06_22_NODI_ECOSISTEMA" },
        @{ Pattern = "notebooklm|notebook lm|master vivo|hub1 wave1|visual master|due diligence|gestione globale"; Folder = "07_32_NOTEBOOKLM" },
        @{ Pattern = "agent ai|agenti ai|orchestrator|ai operating|matricola|prompt agent|control tower"; Folder = "08_AI_OPERATING_SYSTEM_150_AGENTI" },
        @{ Pattern = "marketing|contenuti|social|ads|newsletter|brevo|webinar|lead magnet|funnel|copy|aida|epppa|repppa|attrai|vendi|sorprendi"; Folder = "09_MARKETING_LEAD_GENERATION" },
        @{ Pattern = "scout81|lead pack|prospect|osint|scraping|scout score|opt-out|do not contact"; Folder = "10_SCOUT81_LEAD_PACK" },
        @{ Pattern = "sales|crm|networker|pipeline|follow up|closing|script vendita|provvigioni|regola 50"; Folder = "11_SALES_CRM_NETWORKERS" },
        @{ Pattern = "pvplus|pv plus|pv\+|career81|equilibrium|eq1|eq2|eq3|eq4|eq5|eq6|eq7|eq8|bounty|exchange"; Folder = "12_PV_PVPLUS_CAREER_EQUILIBRIUM" },
        @{ Pattern = "gamification|retention|badge|leaderboard|wall of fame|buddy|diario|arena|mystery|streak|maslow|ruota della vita"; Folder = "13_GAMIFICATION_RETENTION_OS" },
        @{ Pattern = "pass|kit|sdp|membership|basic\+|pro\+|elite\+|network pass|club pass|franchise pass"; Folder = "14_PASS_KIT_SDP_MEMBERSHIP" },
        @{ Pattern = "sicurissimo point|franchising|territorio|franchiser|point81"; Folder = "15_SICURISSIMO_POINT81_FRANCHISING" },
        @{ Pattern = "welfare|hr|dipendenti|micro-learning|clima|benessere|welfare score"; Folder = "16_WELFARE_PLAN" },
        @{ Pattern = "visual|brand|logo|figma|canva|mockup|prompt gemini|midjourney|colori|font|badge icone|pitch visual"; Folder = "17_VISUAL_MASTER_BRAND_ASSET" },
        @{ Pattern = "claude code|github|php|javascript|html|css|backend|frontend|deploy|hostinger|n8n|api rest"; Folder = "18_TECH_CLAUDE_CODE_GITHUB" },
        @{ Pattern = "database|mysql|schema|erd|table|api docs|data dictionary|event taxonomy|ml ready"; Folder = "19_DATABASE_API_ARCHITECTURE" },
        @{ Pattern = "cashflow|economia|tasse|fisc|commercialista|iva|fatture|unit economics|business plan|margini|pricing"; Folder = "20_ECONOMIA_CASHFLOW_TASSE" },
        @{ Pattern = "startup innovativa|bando|bandi|finanziamenti|fondo perduto|smart&start|invitalia|voucher 3i|funding"; Folder = "21_STARTUP_INNOVATIVA_BANDI_FONDI" },
        @{ Pattern = "investitori|angels|banche|fondi|pitch|term sheet|valuation|due diligence|exit|data room"; Folder = "22_INVESTITORI_BANCHE_EXIT" },
        @{ Pattern = "dao|governance|votazioni|proposal|roadmap vote|club survey"; Folder = "23_DAO_GOVERNANCE_WEB3" },
        @{ Pattern = "global|dubai|singapore|usa|africa|cayman|free zone|internazionale"; Folder = "24_GLOBAL_EXPANSION" },
        @{ Pattern = "qa|quality|bonifica|test|bug|approval|human approval|go no go|release"; Folder = "25_QUALITY_ASSURANCE_BONIFICA" },
        @{ Pattern = "security|cyber|backup|incident|access control|password|privacy risk|api security"; Folder = "26_SECURITY_PRIVACY_CYBER_RISK" },
        @{ Pattern = "wave|lancio|launch|go-live|golive|mvp|release|wave1|wave2|wave3"; Folder = "27_WAVE_LANCI_OPERATIVI" },
        @{ Pattern = "cliente|clienti|case study|testimonianze|recensioni|ateco"; Folder = "28_CLIENTI_CASE_STUDY_TESTIMONIANZE" },
        @{ Pattern = "template|prompt|script|whatsapp|call|email template|landing template|ads template"; Folder = "29_TEMPLATE_PROMPT_SCRIPT_COPY" }
    )

    foreach ($r in $rules) {
        if ($text -match $r.Pattern) {
            return $r.Folder
        }
    }

    return "99_INBOX_DA_SMISTARE\DA_ANALIZZARE"
}

# ============================================================
# Start
# ============================================================

if (!(Test-Path -LiteralPath $SourceRoot)) {
    Write-Warn "Source folder not found: $SourceRoot"
    Write-Warn "Create it or run script with -SourceRoot"
    exit 1
}

$Desktop = [Environment]::GetFolderPath("Desktop")
$TargetRoot = Join-Path $Desktop $TargetName
$TempRoot = Join-Path $env:TEMP ("81PLUS_EXTRACT_" + [Guid]::NewGuid().ToString("N"))

Write-Info "Source: $SourceRoot"
Write-Info "Target: $TargetRoot"

New-Folder $TargetRoot
New-Folder $TempRoot

# ============================================================
# Folder structure
# ============================================================

$MainFolders = @(
"00_MASTER_VIVO",
"00_MASTER_VIVO\DECISIONI_DEFINITIVE",
"00_MASTER_VIVO\CHANGELOG_SETTIMANALE",
"00_MASTER_VIVO\ROADMAP_GLOBALE",
"00_MASTER_VIVO\NAMING_UFFICIALE",
"00_MASTER_VIVO\SEMANTIC_GUARD",
"00_MASTER_VIVO\MAPPA_22_NODI",
"00_MASTER_VIVO\MAPPA_32_NOTEBOOKLM",
"00_MASTER_VIVO\MAPPA_AGENTI_AI",

"01_HOLDING_PLANB_CASH_LTD",
"01_HOLDING_PLANB_CASH_LTD\STRUTTURA_HOLDING",
"01_HOLDING_PLANB_CASH_LTD\ORGANIGRAMMA_HUMAN_10_AI_90",
"01_HOLDING_PLANB_CASH_LTD\SOCIETA_OPERATIVE\PLANB_CASH_LTD",
"01_HOLDING_PLANB_CASH_LTD\SOCIETA_OPERATIVE\81PLUS_GLOBAL_SRL_STARTUP",
"01_HOLDING_PLANB_CASH_LTD\SOCIETA_OPERATIVE\SICURISSIMO_81",
"01_HOLDING_PLANB_CASH_LTD\SOCIETA_OPERATIVE\NETWORK81",
"01_HOLDING_PLANB_CASH_LTD\SOCIETA_OPERATIVE\SICURISSIMO_POINT81",
"01_HOLDING_PLANB_CASH_LTD\SOCIETA_OPERATIVE\81PLUS_TECH_AI_LAB",
"01_HOLDING_PLANB_CASH_LTD\GOVERNANCE",
"01_HOLDING_PLANB_CASH_LTD\VERBALI_DECISIONI",
"01_HOLDING_PLANB_CASH_LTD\MARCHI_IP_DOMINI",
"01_HOLDING_PLANB_CASH_LTD\CONTRATTI_SOCIETARI",
"01_HOLDING_PLANB_CASH_LTD\DOCUMENTI_AMMINISTRATIVI",

"02_LEGALE_REGOLAMENTI_COMPLIANCE",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\TERMINI_GENERALI",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\PRIVACY_POLICY",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\COOKIE_POLICY",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_SIC_ID",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_PV",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_PVPLUS",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_PVPLUS_EXCHANGE",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_CAREER81",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_EQUILIBRIUM",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_SCOUT81",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_LEAD_PACK",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_NETWORKERS",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_FRANCHISER",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_SICURISSIMO_POINT81",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_DAO_CONSULTIVA",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\REGOLAMENTO_WELFARE",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\CONTRATTI_CLIENTI",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\CONTRATTI_NETWORKERS",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\CONTRATTI_FRANCHISER",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\CONTRATTI_PARTNER",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\DISCLAIMER_AUDIT_DOCUMENTI",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\GDPR_DPO",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\MICA_WEB3",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\AI_ACT",
"02_LEGALE_REGOLAMENTI_COMPLIANCE\APPROVAZIONI_UMANE",

"03_HUB1_81PLUS_NET",
"03_HUB1_81PLUS_NET\ARCHITETTURA_HUB1",
"03_HUB1_81PLUS_NET\SIC_ID_SSO_KYC",
"03_HUB1_81PLUS_NET\DASHBOARD_UTENTE",
"03_HUB1_81PLUS_NET\DASHBOARD_ADMIN",
"03_HUB1_81PLUS_NET\WALLET_PV_PVPLUS",
"03_HUB1_81PLUS_NET\PAYGATE81",
"03_HUB1_81PLUS_NET\MISSIONE_DEL_GIORNO",
"03_HUB1_81PLUS_NET\RUOTA_DELLA_VITA",
"03_HUB1_81PLUS_NET\PIRAMIDE_MASLOW",
"03_HUB1_81PLUS_NET\AI_AGENT_PANEL",
"03_HUB1_81PLUS_NET\NOTIFICHE",
"03_HUB1_81PLUS_NET\API_HUB1",
"03_HUB1_81PLUS_NET\CRM_BASE",
"03_HUB1_81PLUS_NET\AUDIT_BASE",
"03_HUB1_81PLUS_NET\SCOUT81_BASE",
"03_HUB1_81PLUS_NET\SEMANTIC_GUARD",
"03_HUB1_81PLUS_NET\ANTIFRODE",
"03_HUB1_81PLUS_NET\LOG_EVENTI",
"03_HUB1_81PLUS_NET\WAVE1_MVP",

"04_HUB2_SICURISSIMO",
"04_HUB2_SICURISSIMO\SICUREZZA_LAVORO",
"04_HUB2_SICURISSIMO\HACCP",
"04_HUB2_SICURISSIMO\PRIVACY_GDPR",
"04_HUB2_SICURISSIMO\AUDIT_SICURISSIMO",
"04_HUB2_SICURISSIMO\DOCUMENTI\DVR",
"04_HUB2_SICURISSIMO\DOCUMENTI\POS",
"04_HUB2_SICURISSIMO\DOCUMENTI\DUVRI",
"04_HUB2_SICURISSIMO\DOCUMENTI\MANUALE_HACCP",
"04_HUB2_SICURISSIMO\DOCUMENTI\NOMINE",
"04_HUB2_SICURISSIMO\DOCUMENTI\PRIVACY_DOC",
"04_HUB2_SICURISSIMO\CORSI_FORMAZIONE",
"04_HUB2_SICURISSIMO\PREVENTIVATORE",
"04_HUB2_SICURISSIMO\LISTINI_SERVIZI",
"04_HUB2_SICURISSIMO\PACCHETTI_START_PRO_FULL",
"04_HUB2_SICURISSIMO\CLIENTI",
"04_HUB2_SICURISSIMO\CASE_STUDY",
"04_HUB2_SICURISSIMO\PARTNER_WHITE_LABEL",
"04_HUB2_SICURISSIMO\SICURISSIMO_POINT81_LINK",

"05_HUB3_WEB3_UTILITY",
"05_HUB3_WEB3_UTILITY\SAF_UTILITY",
"05_HUB3_WEB3_UTILITY\81X_UTILITY",
"05_HUB3_WEB3_UTILITY\NFT_UTILITY",
"05_HUB3_WEB3_UTILITY\PIX81",
"05_HUB3_WEB3_UTILITY\MARKETPLACE_WEB3",
"05_HUB3_WEB3_UTILITY\METAVERSO81",
"05_HUB3_WEB3_UTILITY\DAO_CONSULTIVA",
"05_HUB3_WEB3_UTILITY\WEB3_COMPLIANCE",
"05_HUB3_WEB3_UTILITY\MICA_RISK",
"05_HUB3_WEB3_UTILITY\SMART_CONTRACT_BOZZE",
"05_HUB3_WEB3_UTILITY\WALLET_INTERNAL",
"05_HUB3_WEB3_UTILITY\WEB3_ROADMAP_NON_MVP",

"06_22_NODI_ECOSISTEMA",
"07_32_NOTEBOOKLM",
"08_AI_OPERATING_SYSTEM_150_AGENTI",
"09_MARKETING_LEAD_GENERATION",
"10_SCOUT81_LEAD_PACK",
"11_SALES_CRM_NETWORKERS",
"12_PV_PVPLUS_CAREER_EQUILIBRIUM",
"13_GAMIFICATION_RETENTION_OS",
"14_PASS_KIT_SDP_MEMBERSHIP",
"15_SICURISSIMO_POINT81_FRANCHISING",
"16_WELFARE_PLAN",
"17_VISUAL_MASTER_BRAND_ASSET",
"18_TECH_CLAUDE_CODE_GITHUB",
"19_DATABASE_API_ARCHITECTURE",
"20_ECONOMIA_CASHFLOW_TASSE",
"21_STARTUP_INNOVATIVA_BANDI_FONDI",
"22_INVESTITORI_BANCHE_EXIT",
"23_DAO_GOVERNANCE_WEB3",
"24_GLOBAL_EXPANSION",
"25_QUALITY_ASSURANCE_BONIFICA",
"26_SECURITY_PRIVACY_CYBER_RISK",
"27_WAVE_LANCI_OPERATIVI",
"28_CLIENTI_CASE_STUDY_TESTIMONIANZE",
"29_TEMPLATE_PROMPT_SCRIPT_COPY",
"30_ARCHIVIO_STORICO",
"99_INBOX_DA_SMISTARE",
"99_INBOX_DA_SMISTARE\DA_ANALIZZARE",
"99_INBOX_DA_SMISTARE\DA_BONIFICARE",
"99_INBOX_DA_SMISTARE\DA_INSERIRE_NEI_NOTEBOOK",
"99_INBOX_DA_SMISTARE\DA_VALIDARE_LEGALE",
"99_INBOX_DA_SMISTARE\DA_VALIDARE_TECNICO",
"99_INBOX_DA_SMISTARE\DA_ARCHIVIARE",
"99_INBOX_DA_SMISTARE\URGENTE"
)

foreach ($f in $MainFolders) {
    New-Folder (Join-Path $TargetRoot $f)
}

$Nodes = @(
"01_HUB1",
"02_HUB2",
"03_HUB3",
"04_PAYGATE81",
"05_SIC_ID_SSO_KYC",
"06_DOC81_AUDIT_ENGINE",
"07_ACADEMY81",
"08_SICURISSIMO_POINT81",
"09_NETWORK81",
"10_CAREER81",
"11_EQUILIBRIUM81",
"12_PVPLUS_CORE_WALLET_EXCHANGE",
"13_GAMIFICATION_OS",
"14_PIX81",
"15_GENESYS81",
"16_LOCK81",
"17_MARKETPLACE81",
"18_SHOP81",
"19_METAVERSO81",
"20_EXCHANGE81",
"21_CLUB81",
"22_DAO_GOVERNANCE_ORG81"
)

foreach ($n in $Nodes) {
    New-Folder (Join-Path $TargetRoot ("06_22_NODI_ECOSISTEMA\" + $n))
}

$Notebooks = @(
"01_MASTER_VIVO",
"02_LEGALE_REGOLAMENTI",
"03_HUB1",
"04_HUB2",
"05_HUB3",
"06_MARKETING_CONTENUTI",
"07_GLOBAL",
"08_BONIFICA",
"09_HUB1_WAVE1",
"10_VISUAL_MASTER",
"11_ECONOMIA_CASHFLOW",
"12_COMMERCIALISTA_TASSE_TAX_COMPLIANCE",
"13_COMPLIANCE",
"14_DUE_DILIGENCE",
"15_STRATEGIE",
"16_INVESTITORI_ANGELS",
"17_BANCHE_FONDI",
"18_GESTIONE_GLOBALE",
"19_EXIT",
"20_WAVE_OPERATIVE",
"21_SCOUT81_LEAD_GENERATION",
"22_SALES_CRM_NETWORKERS",
"23_WELFARE_PLAN",
"24_STARTUP_INNOVATIVA_BANDI",
"25_DAO_GOVERNANCE",
"26_AI_AGENT_CONTROL_TOWER",
"27_DATA_ML_NEURAL_ENGINE",
"28_REGOLAMENTI_PV_PVPLUS_CAREER_EQ",
"29_SICURISSIMO_POINT81_FRANCHISING",
"30_CUSTOMER_SUCCESS_SUPPORT",
"31_QA_HUMAN_APPROVAL",
"32_SECURITY_PRIVACY_CYBER_RISK"
)

$NotebookSubs = @(
"FONTI",
"PROMPT_MASTER",
"OUTPUT_APPROVATI",
"OUTPUT_DA_VALIDARE",
"TASK_CLAUDE_CODE",
"TASK_AI_AGENT",
"TASK_HUMAN",
"REPORT_SETTIMANALI",
"ARCHIVIO"
)

foreach ($nb in $Notebooks) {
    $nbRoot = Join-Path $TargetRoot ("07_32_NOTEBOOKLM\" + $nb)
    New-Folder $nbRoot

    foreach ($sub in $NotebookSubs) {
        New-Folder (Join-Path $nbRoot $sub)
    }

    $readme = @"
# $nb

## Missione
Questo NotebookLM governa una area specializzata del progetto 81+ Global.

## Regole
- Usare solo naming ufficiale 81+.
- Non usare SafePoint. Usare SICURISSIMO POINT81+.
- Evitare promesse finanziarie o assolute.
- Ogni output deve avere data, versione, stato e owner.
- Ogni settimana produrre report con cambiamenti, blocchi, rischi, task AI, task umano, task Claude Code e prossima azione.

## Cartelle
- FONTI: documenti sorgente.
- PROMPT_MASTER: prompt da usare nel notebook.
- OUTPUT_APPROVATI: materiale consolidato.
- OUTPUT_DA_VALIDARE: bozze da verificare.
- TASK_CLAUDE_CODE: task tecnici.
- TASK_AI_AGENT: task per agenti AI.
- TASK_HUMAN: task umani.
- REPORT_SETTIMANALI: report settimanali.
- ARCHIVIO: vecchie versioni.
"@

    if (!$DryRun) {
        Set-Content -LiteralPath (Join-Path $nbRoot "README_NOTEBOOK.md") -Value $readme -Encoding UTF8
    }
}

$AiFolders = @(
"REGISTRY_AGENTI",
"MATRICOLE_AGENTI",
"PROMPT_PER_AGENTE",
"TEAM_COMANDO",
"TEAM_HUB1",
"TEAM_HUB2",
"TEAM_HUB3",
"TEAM_MARKETING",
"TEAM_SALES",
"TEAM_SCOUT81",
"TEAM_LEGALE",
"TEAM_FISCALE",
"TEAM_COMPLIANCE",
"TEAM_GAMIFICATION",
"TEAM_PV_PVPLUS",
"TEAM_CAREER81",
"TEAM_EQUILIBRIUM",
"TEAM_SICURISSIMO_POINT81",
"TEAM_FUNDING",
"TEAM_INVESTITORI",
"TEAM_DATA_ML",
"TEAM_QA",
"TEAM_SECURITY",
"LOG_OUTPUT_AGENTI",
"ERRORI_AGENTI",
"HUMAN_ESCALATION",
"AGENT_PERFORMANCE_REPORT"
)

foreach ($a in $AiFolders) {
    New-Folder (Join-Path $TargetRoot ("08_AI_OPERATING_SYSTEM_150_AGENTI\" + $a))
}

$PromptFolders = @(
"PROMPT_CLAUDE",
"PROMPT_CLAUDE_CODE",
"PROMPT_CLAUDE_COWORK",
"PROMPT_CHATGPT",
"PROMPT_GEMINI",
"PROMPT_NOTEBOOKLM",
"PROMPT_AGENTI_AI",
"AI_SHARED_CONTEXT",
"OUTPUT_PER_CHATGPT",
"OUTPUT_PER_CLAUDE",
"OUTPUT_PER_GEMINI",
"OUTPUT_PER_GEMMA4"
)

foreach ($p in $PromptFolders) {
    New-Folder (Join-Path $TargetRoot ("29_TEMPLATE_PROMPT_SCRIPT_COPY\" + $p))
}

# ============================================================
# Write base files
# ============================================================

$ReadmeMaster = @"
# 81PLUS GLOBAL MASTER

Questa e la cartella centrale del progetto 81+ Global.

Regola operativa:
99_INBOX raccoglie.
BONIFICA pulisce.
MASTER VIVO decide.
NOTEBOOKLM specializza.
CLAUDE COWORK organizza.
CLAUDE CODE costruisce.
GEMINI crea visual.
CHATGPT ragiona e integra.
GEMMA4 locale analizza offline.
HUB1 registra.
QA valida.
LEGAL e COMPLIANCE approvano.
WAVE lancia.

Nota:
Questo script non cancella file originali da C:\MEMORIA81+.
I duplicati vengono esclusi dalla copia e registrati nei report.
"@

$AiSync = @"
# AI SYNC INSTRUCTIONS

NotebookLM:
Caricare nel NotebookLM relativo i file presenti in:
07_32_NOTEBOOKLM/[NOTEBOOK]/FONTI

Claude:
Usare:
29_TEMPLATE_PROMPT_SCRIPT_COPY/PROMPT_CLAUDE

Claude CoWork:
Usare:
29_TEMPLATE_PROMPT_SCRIPT_COPY/PROMPT_CLAUDE_COWORK

Claude Code:
Usare:
29_TEMPLATE_PROMPT_SCRIPT_COPY/PROMPT_CLAUDE_CODE
18_TECH_CLAUDE_CODE_GITHUB

ChatGPT:
Usare:
29_TEMPLATE_PROMPT_SCRIPT_COPY/PROMPT_CHATGPT
29_TEMPLATE_PROMPT_SCRIPT_COPY/AI_SHARED_CONTEXT

Gemini:
Usare:
29_TEMPLATE_PROMPT_SCRIPT_COPY/PROMPT_GEMINI
17_VISUAL_MASTER_BRAND_ASSET

Gemma4 Docker:
Usare:
29_TEMPLATE_PROMPT_SCRIPT_COPY/OUTPUT_PER_GEMMA4
29_TEMPLATE_PROMPT_SCRIPT_COPY/AI_SHARED_CONTEXT

Nota:
NotebookLM, Claude, ChatGPT e Gemini non scrivono automaticamente su Drive senza Drive Desktop, API, connector o integrazione.
Claude Code puo lavorare su cartella locale sincronizzata da Google Drive Desktop.
"@

$NotebookIndex = "# NOTEBOOK INDEX`r`n`r`n" + (($Notebooks | ForEach-Object { "- $_" }) -join "`r`n")
$NextSteps = @"
# CLAUDE CODE NEXT STEPS

Priorita Wave1:
1. Creare database MVP.
2. Creare landing HUB1.
3. Creare form audit.
4. Creare SIC-ID base.
5. Creare CRM lead.
6. Creare dashboard admin base.
7. Creare dashboard utente base.
8. Creare wallet PV+ base.
9. Creare missione del giorno.
10. Creare Ruota e Piramide base.
11. Creare Scout81+ base.
12. Creare export CSV.
13. Creare semantic guard.
14. Creare log eventi.
15. Creare README installazione Hostinger.
"@

$GemmaTasks = @"
# GEMMA4 LOCAL TASKS

Usare Gemma4 Docker per:
1. Riassumere documenti locali.
2. Classificare file non chiari.
3. Suggerire tag.
4. Creare sintesi offline.
5. Preparare output da mettere in AI_SHARED_CONTEXT.
6. Non usare come fonte finale legale o fiscale.
"@

if (!$DryRun) {
    Set-Content -LiteralPath (Join-Path $TargetRoot "README_MASTER.md") -Value $ReadmeMaster -Encoding UTF8
    Set-Content -LiteralPath (Join-Path $TargetRoot "AI_SYNC_INSTRUCTIONS.md") -Value $AiSync -Encoding UTF8
    Set-Content -LiteralPath (Join-Path $TargetRoot "NOTEBOOK_INDEX.md") -Value $NotebookIndex -Encoding UTF8
    Set-Content -LiteralPath (Join-Path $TargetRoot "CLAUDE_CODE_NEXT_STEPS.md") -Value $NextSteps -Encoding UTF8
    Set-Content -LiteralPath (Join-Path $TargetRoot "GEMMA4_LOCAL_TASKS.md") -Value $GemmaTasks -Encoding UTF8
}

# ============================================================
# ZIP extraction
# ============================================================

Write-Info "Searching ZIP files..."

$ZipFiles = Get-ChildItem -LiteralPath $SourceRoot -Recurse -File -ErrorAction SilentlyContinue |
    Where-Object { $_.Extension.ToLowerInvariant() -eq ".zip" }

foreach ($zip in $ZipFiles) {
    $zipBase = [System.IO.Path]::GetFileNameWithoutExtension($zip.Name)
    $extractTo = Join-Path $TempRoot (Safe-Name $zipBase)

    try {
        Write-Info "Extract ZIP: $($zip.FullName)"
        if (!$DryRun) {
            New-Folder $extractTo
            Expand-Archive -LiteralPath $zip.FullName -DestinationPath $extractTo -Force
        }
    } catch {
        if (!$DryRun) {
            Add-Content -LiteralPath (Join-Path $TargetRoot "ERRORS.log") -Value ("ZIP ERROR: " + $zip.FullName + " :: " + $_.Exception.Message)
        }
    }
}

# ============================================================
# Collect files
# ============================================================

Write-Info "Collecting files..."

$AllFiles = @()

$OriginalFiles = Get-ChildItem -LiteralPath $SourceRoot -Recurse -File -ErrorAction SilentlyContinue |
    Where-Object {
        $_.Name -notin @("Thumbs.db", ".DS_Store", "desktop.ini") -and
        $_.Length -gt 0
    }

$AllFiles += $OriginalFiles

if (!$DryRun -and (Test-Path -LiteralPath $TempRoot)) {
    $ExtractedFiles = Get-ChildItem -LiteralPath $TempRoot -Recurse -File -ErrorAction SilentlyContinue |
        Where-Object {
            $_.Name -notin @("Thumbs.db", ".DS_Store", "desktop.ini") -and
            $_.Length -gt 0
        }

    $AllFiles += $ExtractedFiles
}

Write-Info ("Files found: " + $AllFiles.Count)

# ============================================================
# Hash and dedupe
# ============================================================

$Records = New-Object System.Collections.Generic.List[object]

foreach ($file in $AllFiles) {
    $hash = Get-HashSafe $file.FullName

    if ($null -eq $hash) {
        if (!$DryRun) {
            Add-Content -LiteralPath (Join-Path $TargetRoot "ERRORS.log") -Value ("HASH ERROR: " + $file.FullName)
        }
        continue
    }

    $srcType = "ORIGINAL"
    if ($file.FullName.StartsWith($TempRoot)) {
        $srcType = "ZIP_EXTRACTED"
    }

    $Records.Add([PSCustomObject]@{
        FullName = $file.FullName
        Name = $file.Name
        Extension = $file.Extension
        Length = $file.Length
        LastWriteTime = $file.LastWriteTime
        Hash = $hash
        SourceType = $srcType
    }) | Out-Null
}

$Unique = New-Object System.Collections.Generic.List[object]
$Duplicates = New-Object System.Collections.Generic.List[object]

$Groups = $Records | Group-Object Hash

foreach ($g in $Groups) {
    $best = $g.Group | Sort-Object LastWriteTime -Descending | Select-Object -First 1
    $Unique.Add($best) | Out-Null

    $dups = $g.Group | Where-Object { $_.FullName -ne $best.FullName }

    foreach ($d in $dups) {
        $Duplicates.Add([PSCustomObject]@{
            DuplicateFile = $d.FullName
            KeptFile = $best.FullName
            Hash = $d.Hash
            SizeBytes = $d.Length
            LastWriteTime = $d.LastWriteTime
            Reason = "SHA256 identical - kept newest file"
        }) | Out-Null
    }
}

Write-Info ("Unique files: " + $Unique.Count)
Write-Info ("Duplicates excluded: " + $Duplicates.Count)

# ============================================================
# Copy unique files
# ============================================================

$Manifest = New-Object System.Collections.Generic.List[object]

foreach ($file in $Unique) {
    $category = Get-Category -FullPath $file.FullName -FileName $file.Name
    $destFolder = Join-Path $TargetRoot $category

    if (!$DryRun) {
        New-Folder $destFolder
    }

    $destPath = Get-SafeDestPath -Folder $destFolder -FileName $file.Name

    try {
        Write-Info ("Copy to " + $category + " :: " + $file.Name)

        if (!$DryRun) {
            Copy-Item -LiteralPath $file.FullName -Destination $destPath -Force
        }

        $Manifest.Add([PSCustomObject]@{
            OriginalPath = $file.FullName
            Destination = $destPath
            Category = $category
            Hash = $file.Hash
            SizeBytes = $file.Length
            LastWriteTime = $file.LastWriteTime
            SourceType = $file.SourceType
        }) | Out-Null
    } catch {
        if (!$DryRun) {
            Add-Content -LiteralPath (Join-Path $TargetRoot "ERRORS.log") -Value ("COPY ERROR: " + $file.FullName + " :: " + $_.Exception.Message)
        }
    }
}

# ============================================================
# Reports
# ============================================================

$ReportText = @"
# REPORT ORGANIZZAZIONE 81PLUS

Date: $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

Source:
$SourceRoot

Target:
$TargetRoot

Files found:
$($AllFiles.Count)

Unique files copied:
$($Unique.Count)

Duplicates excluded:
$($Duplicates.Count)

Original files deleted:
0

Reports:
- MANIFEST_81PLUS.csv
- DUPLICATES_EXCLUDED_81PLUS.csv
- ERRORS.log if present
"@

if (!$DryRun) {
    $Manifest | Export-Csv -LiteralPath (Join-Path $TargetRoot "MANIFEST_81PLUS.csv") -NoTypeInformation -Encoding UTF8
    $Duplicates | Export-Csv -LiteralPath (Join-Path $TargetRoot "DUPLICATES_EXCLUDED_81PLUS.csv") -NoTypeInformation -Encoding UTF8
    Set-Content -LiteralPath (Join-Path $TargetRoot "REPORT_ORGANIZZAZIONE_81PLUS.md") -Value $ReportText -Encoding UTF8

    $reportDir = Join-Path $TargetRoot "00_MASTER_VIVO\CHANGELOG_SETTIMANALE"
    New-Folder $reportDir
    Set-Content -LiteralPath (Join-Path $reportDir ("REPORT_" + (Get-Date -Format "yyyy-MM-dd_HHmmss") + ".md")) -Value $ReportText -Encoding UTF8
}

# ============================================================
# Drive mirror
# ============================================================

if ([string]::IsNullOrWhiteSpace($DriveMirrorRoot)) {
    $DriveMirrorRoot = Detect-GoogleDriveRoot
}

if (![string]::IsNullOrWhiteSpace($DriveMirrorRoot) -and (Test-Path -LiteralPath $DriveMirrorRoot)) {
    $DriveTarget = Join-Path $DriveMirrorRoot $TargetName
    Write-Info ("Drive mirror target: " + $DriveTarget)

    if (!$DryRun) {
        New-Folder $DriveTarget

        try {
            robocopy $TargetRoot $DriveTarget /E /R:1 /W:1 /NFL /NDL /NP | Out-Null

            $DriveInfo = @"
# DRIVE MIRROR INFO

Mirror created here:
$DriveTarget

If Google Drive Desktop is active, files will sync online.

Drive web folder provided by user:
https://drive.google.com/drive/folders/1-6hUUcVoMcuOw1GWLL3WCgWeR8WziS3k

Note:
PowerShell writes to local Drive mirror, not directly to browser link.
"@

            Set-Content -LiteralPath (Join-Path $TargetRoot "DRIVE_MIRROR_INFO.md") -Value $DriveInfo -Encoding UTF8
            Set-Content -LiteralPath (Join-Path $DriveTarget "DRIVE_MIRROR_INFO.md") -Value $DriveInfo -Encoding UTF8
            Write-Ok "Drive mirror created."
        } catch {
            Add-Content -LiteralPath (Join-Path $TargetRoot "ERRORS.log") -Value ("DRIVE MIRROR ERROR: " + $_.Exception.Message)
        }
    }
} else {
    Write-Warn "Google Drive Desktop local folder not found."

    $DriveSetup = @"
# DRIVE SETUP REQUIRED

The script did not find a local Google Drive Desktop folder.

To sync automatically with Google Drive:
1. Install Google Drive Desktop.
2. Sync the Drive folder of 81+ Global.
3. Run script again with parameter:
   -DriveMirrorRoot "G:\Il mio Drive"

Drive web folder:
https://drive.google.com/drive/folders/1-6hUUcVoMcuOw1GWLL3WCgWeR8WziS3k

NotebookLM, Claude, ChatGPT and Gemini do not write automatically to Drive without connector, API, Drive Desktop or manual upload.
"@

    if (!$DryRun) {
        Set-Content -LiteralPath (Join-Path $TargetRoot "DRIVE_SETUP_REQUIRED.md") -Value $DriveSetup -Encoding UTF8
    }
}

# ============================================================
# Cleanup
# ============================================================

if (!$DryRun) {
    try {
        if (Test-Path -LiteralPath $TempRoot) {
            Remove-Item -LiteralPath $TempRoot -Recurse -Force -ErrorAction SilentlyContinue
        }
    } catch {}
}

Write-Ok "Done."
Write-Host "Target folder:" -ForegroundColor Green
Write-Host $TargetRoot -ForegroundColor White

if ($OpenAtEnd -and !$DryRun) {
    Start-Process explorer.exe $TargetRoot
}
