#Requires -Version 5.1
<#
.SYNOPSIS
    81PLUS_ORGANIZER_MASTER.ps1 — Organizzatore dell'ecosistema 81+ Global

.DESCRIPTION
    Copia e organizza i file da SourceRoot (default: C:\MEMORIA81+) nella cartella
    81PLUS_GLOBAL_MASTER sul Desktop. Analizza anche i contenuti degli archivi ZIP.
    Deduplica via SHA256. Smista i file per parole chiave nel nome/percorso.
    NON cancella mai i file originali. NON scrive su Google Drive senza Drive Desktop.

.PARAMETER SourceRoot
    Cartella sorgente da analizzare. Default: C:\MEMORIA81+

.PARAMETER DriveMirrorRoot
    Percorso locale di Google Drive Desktop (es. G:\Il mio Drive).
    Se specificato, il mirror viene creato li invece che solo sul Desktop.

.PARAMETER DryRun
    Modalita simulazione: mostra cosa farebbe senza copiare nulla.

.PARAMETER OpenAtEnd
    Apre la cartella master al termine dell'esecuzione.

.EXAMPLE
    .\81PLUS_ORGANIZER_MASTER.ps1 -SourceRoot "C:\MEMORIA81+" -OpenAtEnd
    .\81PLUS_ORGANIZER_MASTER.ps1 -SourceRoot "C:\MEMORIA81+" -DryRun
    .\81PLUS_ORGANIZER_MASTER.ps1 -SourceRoot "C:\MEMORIA81+" -DriveMirrorRoot "G:\Il mio Drive" -OpenAtEnd
#>
[CmdletBinding()]
param(
    [string] $SourceRoot      = "C:\MEMORIA81+",
    [string] $DriveMirrorRoot = "",
    [switch] $DryRun,
    [switch] $OpenAtEnd
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Continue'

# ─── VERSIONE E TIMESTAMP ─────────────────────────────────────────────────────
$ScriptVersion = "1.0.0"
$RunTimestamp  = Get-Date -Format 'yyyyMMdd_HHmmss'
$RunDate       = Get-Date -Format 'yyyy-MM-dd HH:mm:ss'

# ─── PERCORSI BASE ────────────────────────────────────────────────────────────
$DesktopPath      = [Environment]::GetFolderPath("Desktop")
$MasterFolderName = "81PLUS_GLOBAL_MASTER"
$MasterPath       = Join-Path $DesktopPath $MasterFolderName

# ─── STATISTICHE GLOBALI ─────────────────────────────────────────────────────
$Stats = [ordered]@{
    FilesScanned    = 0
    FilesCopied     = 0
    DuplicatesSkip  = 0
    ZipsProcessed   = 0
    ZipFilesFound   = 0
    Errors          = 0
    BytesCopied     = [long]0
    DriveDetected   = $false
    MirrorCreated   = $false
}

# Registry SHA256 → destinazione (per deduplicazione)
$HashRegistry = [System.Collections.Generic.Dictionary[string,string]]::new()

# Collezioni report
$ManifestRows  = [System.Collections.ArrayList]::new()
$DuplicateRows = [System.Collections.ArrayList]::new()
$ErrorLines    = [System.Collections.ArrayList]::new()

# ─── STRUTTURA CARTELLE PRINCIPALI ───────────────────────────────────────────
$MainFolders = @(
    "00_MASTER_VIVO",
    "01_HOLDING_PLANB_CASH_LTD",
    "02_LEGALE_REGOLAMENTI_COMPLIANCE",
    "03_HUB1_81PLUS_NET",
    "04_HUB2_SICURISSIMO",
    "05_HUB3_WEB3_UTILITY",
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
    "99_INBOX_DA_SMISTARE"
)

# Regole di smistamento: ogni voce = @(keywords[], destinazione)
# Verificate in ordine — la prima corrispondenza vince
$RoutingRules = @(
    # Holding
    @{ Keys = @("planb","planb.cash","holding","ltd"); Target = "01_HOLDING_PLANB_CASH_LTD" }
    # Legale
    @{ Keys = @("legale","regolamento","privacy","cookie","gdpr","contratto","disclaimer","dpo","mica","ai act","termini","aml","antiriciclaggio"); Target = "02_LEGALE_REGOLAMENTI_COMPLIANCE" }
    # HUB1
    @{ Keys = @("hub1","81plus.net","sic-id","sic_id","sicid","sso","kyc","dashboard","wallet","paygate","missione del giorno","ruota della vita","piramide maslow","cockpit"); Target = "03_HUB1_81PLUS_NET" }
    # HUB2
    @{ Keys = @("hub2","sicurissimo","dvr","pos ","duvri","haccp","dvri","formazione","corsisicurezza","document81","doc81"); Target = "04_HUB2_SICURISSIMO" }
    # HUB3
    @{ Keys = @("hub3","web3","saf ","81x","nft","metaverso","token","blockchain","crypto","defi"); Target = "05_HUB3_WEB3_UTILITY" }
    # 22 Nodi
    @{ Keys = @("22 nodi","nodi ecosistema","nodo81","career81","equilibrium","lock81","pix81","genesys","academy81","shop81","marketplace81","exchange81","club81"); Target = "06_22_NODI_ECOSISTEMA" }
    # AI OS
    @{ Keys = @("agente ai","150 agenti","ai agent","orchestrator","matricola agente","registry agenti","team ai","ai os"); Target = "08_AI_OPERATING_SYSTEM_150_AGENTI" }
    # Marketing
    @{ Keys = @("marketing","social","ads","newsletter","brevo","webinar","lead magnet","funnel","copywriting","aida","epppa","repppa","attrai","vendi","sorprendi","content plan","piano editoriale","post magnetico"); Target = "09_MARKETING_LEAD_GENERATION" }
    # Scout81
    @{ Keys = @("scout81","lead pack","prospect","osint","lead score","opt-out","ateco","territorio scout","scout pack","scout_81","scout 81"); Target = "10_SCOUT81_LEAD_PACK" }
    # Sales
    @{ Keys = @("sales","crm","networker","pipeline","follow up","followup","closing","script vendita","provvigioni","call script","call strategica","preventivo"); Target = "11_SALES_CRM_NETWORKERS" }
    # PV PVPLUS
    @{ Keys = @("pvplus","pv+","pv plus","career81","equilibrium","eq1","eq2","eq3","eq4","eq5","eq6","eq7","eq8","bounty","exchange pvplus","wallet pv"); Target = "12_PV_PVPLUS_CAREER_EQUILIBRIUM" }
    # Gamification
    @{ Keys = @("gamification","retention","badge","leaderboard","wall of fame","buddy","diario81","arena","mystery","streak","maslow","ruota della vita","life wheel","daily mission","missione giornaliera"); Target = "13_GAMIFICATION_RETENTION_OS" }
    # Pass Kit
    @{ Keys = @("pass","kit member","kit network","kit elite","kit franchiser","kit club","sdp+","membership","basic+","pro+","elite+","network pass","club pass","franchise pass"); Target = "14_PASS_KIT_SDP_MEMBERSHIP" }
    # Point81 Franchising
    @{ Keys = @("sicurissimo point","franchising","franchiser","territory","mappa territori","manuale franchising","point81"); Target = "15_SICURISSIMO_POINT81_FRANCHISING" }
    # Welfare
    @{ Keys = @("welfare","hr plan","dipendenti","micro-learning","clima aziendale","benessere","welfare check","welfare pack"); Target = "16_WELFARE_PLAN" }
    # Visual Brand
    @{ Keys = @("visual","brand","logo","figma","canva","mockup","prompt gemini","midjourney","colori81","font81","brand guide","asset pack","badge pack"); Target = "17_VISUAL_MASTER_BRAND_ASSET" }
    # Tech / Claude Code
    @{ Keys = @("claude code","github","php","javascript","html","css","backend","frontend","deploy","hostinger","n8n","api rest","webpack","dockerfile"); Target = "18_TECH_CLAUDE_CODE_GITHUB" }
    # Database / API
    @{ Keys = @("database","mysql","schema","erd","data dictionary","event taxonomy","api docs","swagger","openapi","tabella sql","create table"); Target = "19_DATABASE_API_ARCHITECTURE" }
    # Economia
    @{ Keys = @("cashflow","economia","tasse","fiscale","commercialista","iva","fattur","business plan","margini","pricing","mrr","arr","unit economics"); Target = "20_ECONOMIA_CASHFLOW_TASSE" }
    # Bandi
    @{ Keys = @("startup innovativa","bando","finanziamenti","fondo perduto","smart&start","invitalia","voucher 3i","horizon","eic","bandi regionali","finanza agevolata"); Target = "21_STARTUP_INNOVATIVA_BANDI_FONDI" }
    # Investitori
    @{ Keys = @("investitori","angels","banche","fondi","pitch","term sheet","valuation","due diligence","exit","data room","fundraising","investor"); Target = "22_INVESTITORI_BANCHE_EXIT" }
    # DAO
    @{ Keys = @("dao","governance","votazioni","proposal","roadmap vote","charter dao","token vote"); Target = "23_DAO_GOVERNANCE_WEB3" }
    # Global
    @{ Keys = @("global","dubai","singapore","usa","africa","cayman","free zone","internazionale","localizzazione","country"); Target = "24_GLOBAL_EXPANSION" }
    # QA
    @{ Keys = @("qa","quality assurance","bonifica","test case","bug","approval","go no go","release notes","go-live checklist"); Target = "25_QUALITY_ASSURANCE_BONIFICA" }
    # Security
    @{ Keys = @("security","cyber","backup","incident","access control","password","privacy risk","api security","gdpr risk","data breach"); Target = "26_SECURITY_PRIVACY_CYBER_RISK" }
    # Wave
    @{ Keys = @("wave","lancio","launch","go-live","golive","mvp","release notes","wave1","wave 1","piano 90","roadmap 90"); Target = "27_WAVE_LANCI_OPERATIVI" }
    # Clienti / Case Study
    @{ Keys = @("cliente","clienti","case study","testimonianze","recensioni","ateco","settore","azienda caso"); Target = "28_CLIENTI_CASE_STUDY_TESTIMONIANZE" }
    # Template Prompt
    @{ Keys = @("template","prompt","script whatsapp","email template","landing template","ads template","copy","swipe file"); Target = "29_TEMPLATE_PROMPT_SCRIPT_COPY" }
    # Master Vivo (largo — dopo le specifiche)
    @{ Keys = @("master vivo","blueprint","master blaster","dna fondativo","decisioni","changelog","roadmap globale","naming","semantic guard","regola finale"); Target = "00_MASTER_VIVO" }
)

# ─── 32 NOTEBOOKLM ────────────────────────────────────────────────────────────
$Notebooks = @(
    @{ Id="01"; Name="MASTER_VIVO";                  Mission="Memoria centrale strategica: DNA, decisioni, naming, 22 nodi, agent registry, semantic guard, changelog, roadmap." }
    @{ Id="02"; Name="LEGALE_REGOLAMENTI";           Mission="Regolamenti, contratti, policy PV/PV+, CAREER81+, EQUILIBRIUM, Scout81+, Lead Pack, DAO, SICURISSIMO POINT81+." }
    @{ Id="03"; Name="HUB1";                         Mission="Cervello centrale: SIC-ID, SSO, database, dashboard, wallet, missione giorno, Ruota, Piramide, API, antifrode." }
    @{ Id="04"; Name="HUB2";                         Mission="SICURISSIMO operativo: sicurezza D.Lgs 81/08, HACCP, privacy, audit, documenti, corsi, preventivi, workflow clienti." }
    @{ Id="05"; Name="HUB3";                         Mission="Web3 utility: SAF, 81X, NFT utility, marketplace, metaverso, PIX81+, DAO consultiva, gestione rischi MiCA." }
    @{ Id="06"; Name="MARKETING_CONTENUTI";          Mission="Attrazione e comunicazione: content plan, ads, social, newsletter, webinar, landing, AIDA, EPPPA, REPPPA." }
    @{ Id="07"; Name="GLOBAL";                       Mission="Espansione internazionale: paesi target, localizzazione, partner, compliance globale, Dubai, Singapore, USA, Africa." }
    @{ Id="08"; Name="BONIFICA";                     Mission="Pulizia e coerenza naming, parole vietate, rischi semantici, copy, PDF, landing, email, regolamenti." }
    @{ Id="09"; Name="HUB1_WAVE1";                   Mission="Go-live MVP HUB1: landing, SIC-ID base, audit, PDF magnete, CRM, dashboard base, PV+, missione, Ruota/Piramide." }
    @{ Id="10"; Name="VISUAL_MASTER";                Mission="Identita visiva: dark premium design, dashboard cockpit, badge, icone, prompt visual, social template, Figma, Canva." }
    @{ Id="11"; Name="ECONOMIA_CASHFLOW";            Mission="Modello economico: cashflow, margini, prezzi, MRR/ARR, unit economics, PV/PV+, Pass/Kit, scenario 90 giorni." }
    @{ Id="12"; Name="COMMERCIALISTA_TASSE_TAX";     Mission="Fiscalita legale: IVA, fatturazione, startup innovativa, holding LTD, SRL italiana, transfer pricing, residenza fiscale." }
    @{ Id="13"; Name="COMPLIANCE";                   Mission="Controllo rischi: GDPR, AI Act, MiCA, sicurezza, marketing legale, network marketing, franchising, DAO, PV/PV+." }
    @{ Id="14"; Name="DUE_DILIGENCE";                Mission="Data room per investitori: IP, contratti, bilanci, forecast, KPI, regolamenti, tech architecture, traction, team." }
    @{ Id="15"; Name="STRATEGIE";                    Mission="Direzione strategica: go-to-market, oceano blu, competitor, pricing, roadmap, partnership, canali, lanci." }
    @{ Id="16"; Name="INVESTITORI_ANGELS";           Mission="Fundraising: pitch deck, one pager, investor CRM, lista angels, follow-up, term sheet, valuation, demo script." }
    @{ Id="17"; Name="BANCHE_FONDI";                 Mission="Credito e finanza: business plan bancabile, Smart&Start, Invitalia, bandi regionali, garanzie pubbliche, rendicontazione." }
    @{ Id="18"; Name="GESTIONE_GLOBALE";             Mission="Controllo operativo: OKR, KPI, task, riunioni, wave, risorse, priorita, report CEO, risk board, decision board." }
    @{ Id="19"; Name="EXIT";                         Mission="Exit readiness: asset strategici, IP, dataset, traction, valuation, buyer map, licensing, spin-off, KPI exit." }
    @{ Id="20"; Name="WAVE_OPERATIVE";               Mission="Gestione tutte le wave: WAVE0 Fondazioni → WAVE18 Exit. Calendario, task, go/no-go, KPI, release notes." }
    @{ Id="21"; Name="SCOUT81_LEAD_GENERATION";      Mission="Prospect B2B legali: OSINT, fonti pubbliche, enrichment, scoring, Scout Pack, assegnazione networker, CRM." }
    @{ Id="22"; Name="SALES_CRM_NETWORKERS";         Mission="Vendite e rete: CRM, lead assignment, script, follow-up, preventivi, closing, onboarding networker, CAREER81+, regola 50%." }
    @{ Id="23"; Name="WELFARE_PLAN";                 Mission="Welfare aziendale e community: formazione dipendenti, micro-learning, badge, dashboard HR, engagement, clima sicurezza." }
    @{ Id="24"; Name="STARTUP_INNOVATIVA_BANDI";     Mission="Requisiti e fondi: startup innovativa, Smart&Start, Voucher 3I, bandi Veneto, CCIAA, fondi AI, Horizon/EIC." }
    @{ Id="25"; Name="DAO_GOVERNANCE";               Mission="Governance consultiva: DAO charter, votazioni consultive, roadmap voting, survey, proposal template, governance logs." }
    @{ Id="26"; Name="AI_AGENT_CONTROL_TOWER";       Mission="Controllo agenti AI: agent registry, matricole, task, permessi, prompt, output, errori, escalation, audit AI." }
    @{ Id="27"; Name="DATA_ML_NEURAL_ENGINE";        Mission="Motore dati: event tracking, vector profiles, Markov, churn probability, recommendation engine, A/B test." }
    @{ Id="28"; Name="REGOLAMENTI_PV_PVPLUS_CAREER"; Mission="Regole economiche interne: PV, PV+, PV+ Exchange, Bounty, CAREER81+, EQUILIBRIUM, reward, voucher, antifrode." }
    @{ Id="29"; Name="SICURISSIMO_POINT81_FRANCHISE"; Mission="Territorio e franchising: modello punto, requisiti apertura, livelli franchiser, mappa territori, manuali, kit." }
    @{ Id="30"; Name="CUSTOMER_SUCCESS_SUPPORT";     Mission="Assistenza e retention: ticket, FAQ, onboarding, rinnovi, supporto audit, feedback, recensioni, escalation." }
    @{ Id="31"; Name="QA_HUMAN_APPROVAL";            Mission="Validazione: QA output AI, test dashboard, test API, validazione legale/fiscale, bug, approval workflow, go/no-go." }
    @{ Id="32"; Name="SECURITY_PRIVACY_CYBER_RISK";  Mission="Protezione: access control, backup, GDPR, data minimization, security API, incident response, data breach, permessi AI." }
)

$NotebookSubfolders = @(
    "FONTI", "PROMPT_MASTER", "OUTPUT_APPROVATI", "OUTPUT_DA_VALIDARE",
    "TASK_CLAUDE_CODE", "TASK_AI_AGENT", "TASK_HUMAN", "REPORT_SETTIMANALI", "ARCHIVIO"
)

# ─── 22 NODI ECOSISTEMA ───────────────────────────────────────────────────────
$Nodi22 = @(
    "01_HUB1","02_HUB2","03_HUB3","04_PAYGATE81","05_SIC_ID_SSO_KYC",
    "06_DOC81_AUDIT_ENGINE","07_ACADEMY81","08_SICURISSIMO_POINT81",
    "09_NETWORK81","10_CAREER81","11_EQUILIBRIUM81","12_PVPLUS_CORE_WALLET_EXCHANGE",
    "13_GAMIFICATION_OS","14_PIX81","15_GENESYS81","16_LOCK81",
    "17_MARKETPLACE81","18_SHOP81","19_METAVERSO81","20_EXCHANGE81",
    "21_CLUB81","22_DAO_GOVERNANCE_ORG81"
)

# ─── AI TEAM CARTELLE ─────────────────────────────────────────────────────────
$AITeamFolders = @(
    "REGISTRY_AGENTI","MATRICOLE_AGENTI","PROMPT_PER_AGENTE","TEAM_COMANDO",
    "TEAM_HUB1","TEAM_HUB2","TEAM_HUB3","TEAM_MARKETING","TEAM_SALES",
    "TEAM_SCOUT81","TEAM_LEGALE","TEAM_FISCALE","TEAM_COMPLIANCE",
    "TEAM_GAMIFICATION","TEAM_PV_PVPLUS","TEAM_CAREER81","TEAM_EQUILIBRIUM",
    "TEAM_SICURISSIMO_POINT81","TEAM_FUNDING","TEAM_INVESTITORI",
    "TEAM_DATA_ML","TEAM_QA","TEAM_SECURITY",
    "LOG_OUTPUT_AGENTI","ERRORI_AGENTI","HUMAN_ESCALATION","AGENT_PERFORMANCE_REPORT"
)

# ─── PROMPT AI CARTELLE ───────────────────────────────────────────────────────
$PromptFolders = @(
    "PROMPT_CLAUDE","PROMPT_CLAUDE_CODE","PROMPT_CLAUDE_COWORK",
    "PROMPT_CHATGPT","PROMPT_GEMINI","PROMPT_GEMMA4","PROMPT_NOTEBOOKLM",
    "PROMPT_AGENTI_AI","AI_SHARED_CONTEXT",
    "OUTPUT_PER_CHATGPT","OUTPUT_PER_CLAUDE","OUTPUT_PER_GEMINI","OUTPUT_PER_GEMMA4"
)

# ═══════════════════════════════════════════════════════════════════════════════
# FUNZIONI UTILITY
# ═══════════════════════════════════════════════════════════════════════════════

function Write-Log {
    param([string]$Message, [string]$Level = "INFO")
    $line = "[$RunTimestamp][$Level] $Message"
    switch ($Level) {
        "ERROR"   { Write-Host $line -ForegroundColor Red    }
        "WARN"    { Write-Host $line -ForegroundColor Yellow }
        "SUCCESS" { Write-Host $line -ForegroundColor Green  }
        "DRY"     { Write-Host $line -ForegroundColor Cyan   }
        default   { Write-Host $line -ForegroundColor Gray   }
    }
    if ($Level -eq "ERROR") {
        [void]$ErrorLines.Add($line)
        $Stats.Errors++
    }
}

function Get-SafeFileName {
    param([string]$Name)
    $invalid = [System.IO.Path]::GetInvalidFileNameChars() -join ''
    $pattern = "[$([regex]::Escape($invalid))]"
    $safe    = [regex]::Replace($Name, $pattern, '_')
    # Tronca a 200 caratteri per evitare path troppo lunghi
    if ($safe.Length -gt 200) {
        $ext  = [System.IO.Path]::GetExtension($safe)
        $base = [System.IO.Path]::GetFileNameWithoutExtension($safe)
        $safe = $base.Substring(0, [Math]::Min($base.Length, 196 - $ext.Length)) + $ext
    }
    return $safe
}

function Get-FileHash256 {
    param([string]$FilePath)
    try {
        $hash = Get-FileHash -Path $FilePath -Algorithm SHA256 -ErrorAction Stop
        return $hash.Hash
    } catch {
        Write-Log "Hash fallito per $FilePath : $_" "ERROR"
        return $null
    }
}

function Get-TargetFolder {
    param([string]$FileName, [string]$RelativePath)
    # Stringa di ricerca: nome file + percorso relativo, tutto minuscolo
    $check = ($FileName + " " + $RelativePath).ToLower()
    foreach ($rule in $RoutingRules) {
        foreach ($kw in $rule.Keys) {
            if ($check -like "*$kw*") {
                return $rule.Target
            }
        }
    }
    return "99_INBOX_DA_SMISTARE"
}

function New-Dir {
    param([string]$Path)
    if (-not (Test-Path $Path)) {
        if ($DryRun) {
            Write-Log "DRY: Crea cartella $Path" "DRY"
        } else {
            New-Item -ItemType Directory -Path $Path -Force | Out-Null
        }
    }
}

# ═══════════════════════════════════════════════════════════════════════════════
# RILEVAMENTO GOOGLE DRIVE DESKTOP
# ═══════════════════════════════════════════════════════════════════════════════

function Find-GoogleDriveDesktop {
    # Cerca Google Drive Desktop in percorsi comuni
    $candidates = @()

    # Google Drive File Stream (DriveFS) — controlla lettere comuni
    foreach ($letter in @('G','H','I','J','K','L','M')) {
        $p1 = "${letter}:\Il mio Drive"
        $p2 = "${letter}:\My Drive"
        $p3 = "${letter}:\"
        if (Test-Path $p1) { $candidates += $p1 }
        if (Test-Path $p2) { $candidates += $p2 }
    }

    # Google Drive Desktop (vecchio sync) — percorso utente
    $oldPath = Join-Path $env:USERPROFILE "Google Drive"
    if (Test-Path $oldPath) { $candidates += $oldPath }

    # Controlla se Google Drive File Stream e attivo (agente in esecuzione)
    $driveFsProcess = Get-Process -Name "GoogleDriveFS" -ErrorAction SilentlyContinue
    $driveSyncProc  = Get-Process -Name "googledrivesync" -ErrorAction SilentlyContinue

    if ($driveFsProcess -or $driveSyncProc) {
        # Processo trovato: cerca il mount point via registry
        try {
            $regPath = "HKCU:\Software\Google\DriveFS\Share"
            if (Test-Path $regPath) {
                $regVal = Get-ItemProperty $regPath -ErrorAction SilentlyContinue
                if ($regVal -and $regVal.MountPoint) {
                    $mp = Join-Path $regVal.MountPoint "Il mio Drive"
                    if (Test-Path $mp) { $candidates += $mp }
                }
            }
        } catch {}
    }

    # Ritorna il primo candidato valido
    if ($candidates.Count -gt 0) {
        return $candidates[0]
    }
    return $null
}

# ═══════════════════════════════════════════════════════════════════════════════
# CREAZIONE STRUTTURA CARTELLE
# ═══════════════════════════════════════════════════════════════════════════════

function New-MasterStructure {
    param([string]$BasePath)

    Write-Log "Creazione struttura master in: $BasePath" "INFO"

    # Cartelle principali
    foreach ($folder in $MainFolders) {
        New-Dir (Join-Path $BasePath $folder)
    }

    # 99_INBOX: sotto-cartella DA_ANALIZZARE
    New-Dir (Join-Path $BasePath "99_INBOX_DA_SMISTARE\DA_ANALIZZARE")

    # ── 07_32_NOTEBOOKLM ──────────────────────────────────────────────────
    $nbBase = Join-Path $BasePath "07_32_NOTEBOOKLM"
    foreach ($nb in $Notebooks) {
        $nbPath = Join-Path $nbBase "$($nb.Id)_$($nb.Name)"
        New-Dir $nbPath
        foreach ($sub in $NotebookSubfolders) {
            New-Dir (Join-Path $nbPath $sub)
        }
        # README per ogni notebook
        $readmePath = Join-Path $nbPath "README_NOTEBOOK.md"
        if (-not (Test-Path $readmePath) -and -not $DryRun) {
            $content = @"
# NOTEBOOK $($nb.Id) — $($nb.Name)

## Missione
$($nb.Mission)

## AI Team Assegnato
Vedere `PROMPT_MASTER\` per i prompt degli agenti assegnati a questo notebook.

## Struttura
- **FONTI/** — documenti sorgente caricati su NotebookLM
- **PROMPT_MASTER/** — prompt di sistema e istruzioni per Claude CoWork
- **OUTPUT_APPROVATI/** — output validati da human reviewer
- **OUTPUT_DA_VALIDARE/** — output AI in attesa di approvazione
- **TASK_CLAUDE_CODE/** — task tecnici per Claude Code
- **TASK_AI_AGENT/** — task per agenti AI autonomi
- **TASK_HUMAN/** — task che richiedono revisione umana
- **REPORT_SETTIMANALI/** — report settimanali dello stato notebook
- **ARCHIVIO/** — versioni precedenti e storico

## Checklist Settimanale
- [ ] Cosa e cambiato questa settimana
- [ ] Cosa e bloccato
- [ ] Cosa serve
- [ ] Cosa va approvato da Mirco
- [ ] Rischi aperti
- [ ] Task per Claude Code
- [ ] Task per AI Agent
- [ ] Task per umano
- [ ] KPI aggiornati
- [ ] Prossima azione

---
*Generato da 81PLUS_ORGANIZER_MASTER.ps1 v$ScriptVersion — $RunDate*
"@
            Set-Content -Path $readmePath -Value $content -Encoding UTF8
        }
    }

    # ── 06_22_NODI_ECOSISTEMA ─────────────────────────────────────────────
    $nodiBase = Join-Path $BasePath "06_22_NODI_ECOSISTEMA"
    foreach ($nodo in $Nodi22) {
        $nPath = Join-Path $nodiBase $nodo
        New-Dir $nPath
        New-Dir (Join-Path $nPath "ARCHITETTURA")
        New-Dir (Join-Path $nPath "REGOLAMENTI")
        New-Dir (Join-Path $nPath "PROMPT")
        New-Dir (Join-Path $nPath "OUTPUT")
    }

    # ── 08_AI_OPERATING_SYSTEM_150_AGENTI ─────────────────────────────────
    $aiBase = Join-Path $BasePath "08_AI_OPERATING_SYSTEM_150_AGENTI"
    foreach ($team in $AITeamFolders) {
        New-Dir (Join-Path $aiBase $team)
    }

    # ── 29_TEMPLATE_PROMPT_SCRIPT_COPY ────────────────────────────────────
    $promptBase = Join-Path $BasePath "29_TEMPLATE_PROMPT_SCRIPT_COPY"
    foreach ($pf in $PromptFolders) {
        New-Dir (Join-Path $promptBase $pf)
    }

    Write-Log "Struttura cartelle creata: $($MainFolders.Count) cartelle principali + notebook + nodi + AI teams" "SUCCESS"
}

# ═══════════════════════════════════════════════════════════════════════════════
# COPIA FILE CON DEDUPLICAZIONE SHA256
# ═══════════════════════════════════════════════════════════════════════════════

function Copy-FileWithDedup {
    param(
        [string]$SourcePath,
        [string]$TargetDir,
        [string]$RelativeSource,
        [string]$ZipSource = ""
    )

    $Stats.FilesScanned++
    $origName = [System.IO.Path]::GetFileName($SourcePath)
    $safeName = Get-SafeFileName $origName

    # Calcola hash
    $hash = Get-FileHash256 $SourcePath
    if (-not $hash) { return }

    # Controlla se hash gia registrato (duplicato esatto)
    if ($HashRegistry.ContainsKey($hash)) {
        $existingDest = $HashRegistry[$hash]
        $Stats.DuplicatesSkip++
        # Confronta date: tieni il piu recente
        if (Test-Path $SourcePath) {
            $srcDate  = (Get-Item $SourcePath).LastWriteTime
            $destDate = if (Test-Path $existingDest) { (Get-Item $existingDest).LastWriteTime } else { [DateTime]::MinValue }
            $sourceInfo = if ($ZipSource) { "(da ZIP: $ZipSource)" } else { "" }
            if ($srcDate -gt $destDate -and -not $DryRun -and (Test-Path $existingDest)) {
                Copy-Item -Path $SourcePath -Destination $existingDest -Force
                Write-Log "Sostituito con versione piu recente: $safeName $sourceInfo" "INFO"
            }
        }
        [void]$DuplicateRows.Add([PSCustomObject]@{
            FileName     = $safeName
            SourcePath   = $SourcePath
            DuplicateDi  = $existingDest
            Hash         = $hash
            ZipSource    = $ZipSource
            Motivo       = "SHA256 identico — tenuto il piu recente"
        })
        return
    }

    # Percorso destinazione
    New-Dir $TargetDir
    $destPath = Join-Path $TargetDir $safeName

    # Gestisci conflitto: stesso nome, contenuto diverso
    if (Test-Path $destPath) {
        $existingHash = Get-FileHash256 $destPath
        if ($existingHash -ne $hash) {
            # Nome diverso: aggiungi suffisso _001, _002 ...
            $base   = [System.IO.Path]::GetFileNameWithoutExtension($safeName)
            $ext    = [System.IO.Path]::GetExtension($safeName)
            $suffix = 1
            do {
                $newName  = "${base}_$('{0:D3}' -f $suffix)${ext}"
                $destPath = Join-Path $TargetDir $newName
                $suffix++
            } while (Test-Path $destPath)
            Write-Log "Conflitto nome — rinominato in: $newName" "WARN"
        } else {
            # Stesso contenuto, stesso nome: skip
            $HashRegistry[$hash] = $destPath
            $Stats.DuplicatesSkip++
            return
        }
    }

    if ($DryRun) {
        Write-Log "DRY: Copia $origName → $TargetDir" "DRY"
    } else {
        try {
            Copy-Item -Path $SourcePath -Destination $destPath -Force
            $Stats.FilesCopied++
            $fileSize = (Get-Item $SourcePath).Length
            $Stats.BytesCopied += $fileSize
            $HashRegistry[$hash] = $destPath

            [void]$ManifestRows.Add([PSCustomObject]@{
                FileName      = [System.IO.Path]::GetFileName($destPath)
                SourcePath    = $SourcePath
                DestPath      = $destPath
                Hash          = $hash
                SizeBytes     = $fileSize
                LastModified  = (Get-Item $SourcePath).LastWriteTime.ToString('yyyy-MM-dd HH:mm:ss')
                ZipSource     = $ZipSource
                RelativeSrc   = $RelativeSource
            })
        } catch {
            Write-Log "Errore copia $origName : $_" "ERROR"
        }
    }
}

# ═══════════════════════════════════════════════════════════════════════════════
# ELABORAZIONE FILE ZIP
# ═══════════════════════════════════════════════════════════════════════════════

function Expand-AndProcessZip {
    param(
        [string]$ZipPath,
        [string]$MasterBase,
        [string]$RelativeZip,
        [int]$Depth = 0
    )

    if ($Depth -gt 2) {
        Write-Log "ZIP annidato troppo profondo (>3): $ZipPath — copiato as-is" "WARN"
        $target = Join-Path $MasterBase (Get-TargetFolder (Split-Path $ZipPath -Leaf) $RelativeZip)
        Copy-FileWithDedup $ZipPath $target $RelativeZip
        return
    }

    $zipName  = [System.IO.Path]::GetFileNameWithoutExtension($ZipPath)
    $tmpDir   = Join-Path $env:TEMP "81PLUS_ZIP_$($zipName)_$(Get-Date -Format 'yyyyMMddHHmmssfff')"
    $Stats.ZipsProcessed++

    Write-Log "Estrazione ZIP ($($Depth+1)/3): $zipName" "INFO"

    try {
        if (-not $DryRun) {
            Add-Type -AssemblyName System.IO.Compression.FileSystem -ErrorAction SilentlyContinue
            [System.IO.Compression.ZipFile]::ExtractToDirectory($ZipPath, $tmpDir)
        }

        $extractedFiles = if (Test-Path $tmpDir) {
            Get-ChildItem -Path $tmpDir -Recurse -File
        } else { @() }

        $Stats.ZipFilesFound += $extractedFiles.Count
        Write-Log "  $($extractedFiles.Count) file trovati nel ZIP: $zipName" "INFO"

        foreach ($file in $extractedFiles) {
            $relInZip  = $file.FullName.Substring($tmpDir.Length).TrimStart('\','/')
            $fullRel   = "$RelativeZip\[ZIP:$zipName]\$relInZip"
            $targetDir = Get-TargetFolder $file.Name $fullRel

            if ($file.Extension -eq ".zip" -and $Depth -lt 2) {
                Expand-AndProcessZip $file.FullName $MasterBase $fullRel ($Depth + 1)
            } else {
                $destDir = Join-Path $MasterBase $targetDir
                Copy-FileWithDedup $file.FullName $destDir $fullRel ("[ZIP:$zipName]")
            }
        }

    } catch {
        Write-Log "Errore elaborazione ZIP $($ZipPath): $_" "ERROR"
    } finally {
        # Rimuove SOLO la cartella temp, mai gli originali
        if ((Test-Path $tmpDir) -and -not $DryRun) {
            Remove-Item -Path $tmpDir -Recurse -Force -ErrorAction SilentlyContinue
        }
    }
}

# ═══════════════════════════════════════════════════════════════════════════════
# SCANSIONE SORGENTE
# ═══════════════════════════════════════════════════════════════════════════════

function Invoke-SourceScan {
    param([string]$SourcePath, [string]$MasterBase)

    if (-not (Test-Path $SourcePath)) {
        Write-Log "ATTENZIONE: SourceRoot non esiste: $SourcePath" "WARN"
        Write-Log "Lo script creera la struttura vuota e aspettera i file." "WARN"
        return
    }

    $allFiles = Get-ChildItem -Path $SourcePath -Recurse -File -ErrorAction Continue
    Write-Log "File trovati in sorgente: $($allFiles.Count)" "INFO"

    foreach ($file in $allFiles) {
        $rel = $file.FullName.Substring($SourcePath.Length).TrimStart('\','/')

        if ($file.Extension -eq ".zip") {
            Expand-AndProcessZip $file.FullName $MasterBase $rel
        } else {
            $targetDir = Join-Path $MasterBase (Get-TargetFolder $file.Name $rel)
            Copy-FileWithDedup $file.FullName $targetDir $rel
        }

        # Progress ogni 50 file
        if ($Stats.FilesScanned % 50 -eq 0) {
            Write-Host "  ... scansionati $($Stats.FilesScanned) file, copiati $($Stats.FilesCopied)" -ForegroundColor DarkGray
        }
    }
}

# ═══════════════════════════════════════════════════════════════════════════════
# GENERAZIONE FILE AUTOMATICI
# ═══════════════════════════════════════════════════════════════════════════════

function Write-AutoFiles {
    param([string]$BasePath, [bool]$DriveFound, [string]$DrivePath)

    Write-Log "Scrittura file automatici..." "INFO"
    if ($DryRun) { Write-Log "DRY: file automatici non scritti" "DRY"; return }

    # ── README_MASTER.md ─────────────────────────────────────────────────
    $readme = @"
# 81+ GLOBAL MASTER — Cartella Principale

**Generato da:** 81PLUS_ORGANIZER_MASTER.ps1 v$ScriptVersion
**Data:** $RunDate
**Sorgente:** $SourceRoot

## Cos'e questa cartella

`81PLUS_GLOBAL_MASTER` e il cervello organizzato dell'intero ecosistema 81+ Global.
Contiene tutti i file del progetto smistati per categoria, la struttura per i 32 NotebookLM,
i team AI, i 22 nodi dell'ecosistema e le cartelle prompt per ogni AI.

## Regola fondamentale

**Questa cartella e una COPIA organizzata. Non cancella mai i file originali in `$SourceRoot`.**

## Struttura principale

| Cartella | Contenuto |
|----------|-----------|
| 00_MASTER_VIVO | Blueprint, DNA, decisioni, changelog |
| 01_HOLDING | PLANB.CASH LTD, holding |
| 02_LEGALE | Contratti, regolamenti, GDPR, MiCA |
| 03_HUB1 | 81plus.net, SIC-ID, wallet, dashboard |
| 04_HUB2 | SICURISSIMO, audit, HACCP, documenti |
| 05_HUB3 | Web3, SAF, 81X, NFT, metaverso |
| 06_22_NODI | I 22 nodi dell'ecosistema |
| 07_32_NOTEBOOKLM | Struttura per i 32 NotebookLM |
| 08_AI_OS | AI Operating System, 150 agenti |
| 09_MARKETING | Content, ads, webinar, lead magnet |
| 10_SCOUT81 | Lead pack, prospect, scouting |
| 11_SALES | CRM, networkers, script vendita |
| 12_PV_PVPLUS | Wallet, CAREER81+, EQUILIBRIUM |
| 13_GAMIFICATION | Badge, missioni, streak, Ruota |
| 14_PASS_KIT | Pass, Kit, SDP+, membership |
| 15_FRANCHISING | SICURISSIMO POINT81+, territori |
| 16_WELFARE | Welfare aziendale, HR |
| 17_VISUAL | Brand, visual, prompt immagini |
| 18_TECH | Claude Code, PHP, GitHub, deploy |
| 19_DATABASE | MySQL, schema, API docs |
| 20_ECONOMIA | Cashflow, tasse, business plan |
| 21_BANDI | Startup innovativa, Smart&Start |
| 22_INVESTITORI | Angels, banche, pitch, exit |
| 23_DAO | Governance, votazioni, charter |
| 24_GLOBAL | Dubai, Singapore, USA, Africa |
| 25_QA | Test, bonifica, approvazioni |
| 26_SECURITY | Cyber, GDPR, backup, incident |
| 27_WAVE | Lanci, go-live, release notes |
| 28_CLIENTI | Case study, testimonianze |
| 29_PROMPT | Template, prompt, script per ogni AI |
| 30_ARCHIVIO | Storico versioni precedenti |
| 99_INBOX | File non classificati da smistare |

## AI che usano questa cartella

- **Claude Code** — legge e scrive via file system locale
- **ChatGPT** — upload manuale dei file necessari
- **Gemini** — upload o Google Drive sync
- **Gemma4 Docker** — montaggio cartella come volume
- **NotebookLM** — upload manuale dei file FONTI per ogni notebook

---
*Il Delta non e dove il fiume finisce. E dove impara a diventare mare.*
"@
    Set-Content -Path (Join-Path $BasePath "README_MASTER.md") -Value $readme -Encoding UTF8

    # ── AI_SYNC_INSTRUCTIONS.md ───────────────────────────────────────────
    $aiSync = @"
# AI SYNC INSTRUCTIONS — Istruzioni di Sincronizzazione AI

**Data aggiornamento:** $RunDate

## Come ogni AI usa questa cartella

### Claude Code (questo ambiente)
- Lavora direttamente sui file locali
- Legge e scrive in `18_TECH_CLAUDE_CODE_GITHUB/`
- Usa `07_32_NOTEBOOKLM/*/TASK_CLAUDE_CODE/` per i task tecnici
- Ha accesso completo alla struttura

### Claude CoWork
- Riceve come input i file da `29_TEMPLATE_PROMPT_SCRIPT_COPY/PROMPT_CLAUDE_COWORK/`
- Produce output in `07_32_NOTEBOOKLM/*/OUTPUT_DA_VALIDARE/`
- Coordina i 32 NotebookLM producendo task settimanali

### ChatGPT
- Input: upload manuale di file da `29_TEMPLATE_PROMPT_SCRIPT_COPY/OUTPUT_PER_CHATGPT/`
- Output: copiare i risultati in `07_32_NOTEBOOKLM/*/OUTPUT_DA_VALIDARE/`

### Gemini Global
- Usa `29_TEMPLATE_PROMPT_SCRIPT_COPY/PROMPT_GEMINI/`
- Produce visual e contenuti per `17_VISUAL_MASTER_BRAND_ASSET/`
- Output in `29_TEMPLATE_PROMPT_SCRIPT_COPY/OUTPUT_PER_GEMINI/`

### Gemma4 Docker (locale)
- Monta `81PLUS_GLOBAL_MASTER/` come volume Docker
- Compiti: analisi locale, estrazione pattern, text processing
- Vedi `GEMMA4_LOCAL_TASKS.md` per i task specifici

### NotebookLM (32 notebook)
- Ogni notebook usa `07_32_NOTEBOOKLM/NN_NOME/FONTI/` come sorgente
- Upload manuale dei file FONTI su NotebookLM
- Output copiati in `OUTPUT_APPROVATI/` o `OUTPUT_DA_VALIDARE/`

## Regola AI → Human

AI propone.
Human valida.
HUB1 registra.

## Semantic Guard — Parole Vietate

NON usare nei testi pubblici:
investimento, rendimento, rendita, profitto garantito, guadagno garantito,
ROI, APY, yield, staking, capitale, zero multe, rischio zero,
liquidita garantita, partecipazione utili, denaro automatico, passivo garantito.

Usare invece:
valore operativo, credito interno, PV+, utility interna, benefit,
voucher, status, badge, accessi, governance consultiva, crescita,
reputazione, community, controllo operativo, reward variabile, vendite reali.
"@
    Set-Content -Path (Join-Path $BasePath "AI_SYNC_INSTRUCTIONS.md") -Value $aiSync -Encoding UTF8

    # ── NOTEBOOK_INDEX.md ──────────────────────────────────────────────────
    $nbIndex = @"
# NOTEBOOK INDEX — I 32 NotebookLM di 81+ Global

**Totale notebook:** 32
**Data:** $RunDate

| # | Nome | Missione |
|---|------|----------|
"@
    foreach ($nb in $Notebooks) {
        $nbIndex += "| $($nb.Id) | $($nb.Name) | $($nb.Mission.Substring(0, [Math]::Min(80, $nb.Mission.Length)))... |`n"
    }
    $nbIndex += @"

## Come caricare i file su NotebookLM

1. Apri NotebookLM (notebooklm.google.com)
2. Crea un notebook per ogni cartella in `07_32_NOTEBOOKLM/`
3. Carica i file dalla sotto-cartella `FONTI/` di quel notebook
4. Usa il `PROMPT_MASTER/` per configurare le istruzioni del notebook
5. Salva gli output in `OUTPUT_APPROVATI/` o `OUTPUT_DA_VALIDARE/`

## Nota importante
NotebookLM non si integra automaticamente con Google Drive senza configurazione API.
I file vanno caricati manualmente o via automazione Zapier/Make.
"@
    Set-Content -Path (Join-Path $BasePath "NOTEBOOK_INDEX.md") -Value $nbIndex -Encoding UTF8

    # ── CLAUDE_CODE_NEXT_STEPS.md ─────────────────────────────────────────
    $ccNext = @"
# CLAUDE CODE — Next Steps Wave1

**Generato:** $RunDate
**Priorita:** Wave1 HUB1 MVP Go-Live

## Giorni 1-7 (IN CORSO)
- [x] Landing HUB1 (index.php)
- [x] Form audit (audit.php + api/audit.php)
- [x] CRM lead (leads table + admin)
- [x] Admin dashboard (admin.php)
- [x] Regolamenti base (termini.php, privacy.php)
- [x] PayGate81+ (PayPal, Revolut, Crypto, Bonifico)
- [x] Cockpit IMPARA (cockpit81.php — Ruota, Maslow, Missioni)

## Giorni 8-14 (PROSSIMI)
- [ ] SIC-ID creation flow completo (pagina dedicata)
- [ ] PV+ wallet frontend (dashboard sezione wallet)
- [ ] api/user-score.php (calcolo dinamico 4 score)
- [ ] Networker area base (network81.php review)
- [ ] Scout Pack manuale (plp-catalog.php review)
- [ ] CSV export admin

## Giorni 15-30
- [ ] Pass/Kit frontend (shop81.php / membership.php)
- [ ] Scout81+ semiautomatico
- [ ] Social engine (Telegram/WhatsApp automation hook)
- [ ] Newsletter (Brevo integration)
- [ ] Dashboard KPI (admin.php sezione KPI)
- [ ] Follow-up automatico

## File da creare (Task tecnici)
Vedere `07_32_NOTEBOOKLM/09_HUB1_WAVE1/TASK_CLAUDE_CODE/` per task dettagliati.

## Stack tecnico
PHP 8+, MySQL, HTML, CSS, JavaScript
API REST interne, Hostinger, n8n opzionale, Brevo opzionale

## Regola Claude Code
1. Prima funzionante
2. Poi bello
3. Poi intelligente
4. Poi scalabile
"@
    Set-Content -Path (Join-Path $BasePath "CLAUDE_CODE_NEXT_STEPS.md") -Value $ccNext -Encoding UTF8

    # ── GEMMA4_LOCAL_TASKS.md ─────────────────────────────────────────────
    $gemma4 = @"
# GEMMA4 DOCKER — Task per elaborazione locale

**Generato:** $RunDate

## Setup Docker (esempio)
\`\`\`bash
docker run -it --rm \
  -v "/path/to/81PLUS_GLOBAL_MASTER:/workspace" \
  gemma4:latest
\`\`\`

## Task prioritari per Gemma4

### 1. Analisi semantica file in 99_INBOX_DA_SMISTARE/
- Leggi ogni file di testo
- Classifica per categoria 81+
- Suggerisci cartella di destinazione
- Output: smistamento_suggestions.csv

### 2. Estrazione keyword da documenti normativi
- Analizza file in 04_HUB2_SICURISSIMO/
- Estrai termini D.Lgs 81/08, HACCP, privacy
- Genera glossario 81+
- Output: GLOSSARIO_NORMATIVO_81PLUS.md

### 3. Lead scoring semantico
- Analizza email/note lead da 10_SCOUT81_LEAD_PACK/
- Classifica temperatura (freddo/tiepido/caldo)
- Output: lead_score_gemma4.csv

### 4. Deduplica testi
- Trova documenti con contenuto simile (>80% overlap)
- Genera report similarita
- Output: similarita_testi.csv

### 5. Generazione FAQ automatiche
- Analizza documenti in 00_MASTER_VIVO/
- Genera 50 FAQ per settore
- Output: FAQ_81PLUS_AUTO.md

## Note
Gemma4 lavora solo localmente. Non invia dati a server esterni.
Ideale per: analisi testi, classificazione, estrazione info, dedup semantico.
"@
    Set-Content -Path (Join-Path $BasePath "GEMMA4_LOCAL_TASKS.md") -Value $gemma4 -Encoding UTF8

    # ── DRIVE_MIRROR_INFO.md o DRIVE_SETUP_REQUIRED.md ───────────────────
    if ($DriveFound) {
        $driveInfo = @"
# GOOGLE DRIVE MIRROR — Informazioni

**Mirror creato:** $RunDate
**Percorso Drive locale:** $DrivePath
**Cartella mirror:** $(Join-Path $DrivePath $MasterFolderName)

## Sincronizzazione
Google Drive Desktop sincronizza automaticamente questa cartella sul cloud.
Le modifiche vengono sincronizzate entro pochi secondi se sei connesso a internet.

## Come accedere online
Apri: https://drive.google.com/drive/folders/
Cerca la cartella: $MasterFolderName

## Nota importante
NotebookLM non legge automaticamente da Google Drive.
Per caricare file su NotebookLM devi farlo manualmente o via Make/Zapier.
Claude Code puo lavorare direttamente su questa cartella locale sincronizzata.
"@
        Set-Content -Path (Join-Path $BasePath "DRIVE_MIRROR_INFO.md") -Value $driveInfo -Encoding UTF8
    } else {
        $driveSetup = @"
# GOOGLE DRIVE SETUP REQUIRED

**Generato:** $RunDate

## Google Drive Desktop non rilevato

Lo script ha cercato Google Drive Desktop nei percorsi standard ma non lo ha trovato.
La struttura `81PLUS_GLOBAL_MASTER` e stata creata solo sul Desktop locale.

## Come configurare Google Drive Desktop

### Opzione 1 — Google Drive Desktop (consigliata)
1. Scarica Google Drive Desktop: https://www.google.com/intl/it/drive/download/
2. Installalo e accedi con l'account Google del progetto
3. Scegli la cartella di sync locale (es. G:\Il mio Drive)
4. Sposta o copia `81PLUS_GLOBAL_MASTER` nella cartella sync
5. Riesegui lo script con: `.\81PLUS_ORGANIZER_MASTER.ps1 -OpenAtEnd`
   Lo script trovera automaticamente Drive Desktop

### Opzione 2 — Parametro manuale
Se conosci il percorso locale di Drive, specifica:
\`\`\`powershell
.\81PLUS_ORGANIZER_MASTER.ps1 -DriveMirrorRoot "G:\Il mio Drive" -OpenAtEnd
\`\`\`

### Opzione 3 — rclone (avanzato)
Per sync via API senza Drive Desktop:
\`\`\`
rclone copy "$DesktopPath\81PLUS_GLOBAL_MASTER" gdrive:81PLUS_GLOBAL_MASTER --progress
\`\`\`

## Cartella Drive del progetto
https://drive.google.com/drive/folders/1-6hUUcVoMcuOw1GWLL3WCgWeR8WziS3k

## Importante
Lo script NON ha sincronizzato nulla su Drive.
I file sono solo locali in: $BasePath
"@
        Set-Content -Path (Join-Path $BasePath "DRIVE_SETUP_REQUIRED.md") -Value $driveSetup -Encoding UTF8
    }
}

function Write-Reports {
    param([string]$BasePath)

    if ($DryRun) { Write-Log "DRY: report non scritti" "DRY"; return }

    $reportDir = $BasePath

    # ── MANIFEST CSV ─────────────────────────────────────────────────────
    $manifestPath = Join-Path $reportDir "MANIFEST_81PLUS.csv"
    $ManifestRows | Export-Csv -Path $manifestPath -NoTypeInformation -Encoding UTF8
    Write-Log "Manifest scritto: $manifestPath ($($ManifestRows.Count) righe)" "SUCCESS"

    # ── DUPLICATI ESCLUSI CSV ─────────────────────────────────────────────
    $dupPath = Join-Path $reportDir "DUPLICATES_EXCLUDED_81PLUS.csv"
    $DuplicateRows | Export-Csv -Path $dupPath -NoTypeInformation -Encoding UTF8
    Write-Log "Report duplicati: $dupPath ($($DuplicateRows.Count) righe)" "SUCCESS"

    # ── ERRORS LOG ───────────────────────────────────────────────────────
    if ($ErrorLines.Count -gt 0) {
        $errPath = Join-Path $reportDir "ERRORS.log"
        $ErrorLines | Set-Content -Path $errPath -Encoding UTF8
        Write-Log "Log errori: $errPath ($($ErrorLines.Count) errori)" "WARN"
    }

    # ── REPORT ORGANIZZAZIONE ─────────────────────────────────────────────
    $totalMB    = [Math]::Round($Stats.BytesCopied / 1MB, 2)
    $reportText = @"
# REPORT ORGANIZZAZIONE 81+ GLOBAL

**Data esecuzione:** $RunDate
**Script versione:** $ScriptVersion
**Modalita:** $(if ($DryRun) { 'DRY RUN (simulazione)' } else { 'ESECUZIONE REALE' })

## Riepilogo

| Metrica | Valore |
|---------|--------|
| File scansionati | $($Stats.FilesScanned) |
| File copiati | $($Stats.FilesCopied) |
| Duplicati esclusi | $($Stats.DuplicatesSkip) |
| Archivi ZIP elaborati | $($Stats.ZipsProcessed) |
| File estratti da ZIP | $($Stats.ZipFilesFound) |
| Errori | $($Stats.Errors) |
| Dati copiati | $totalMB MB |
| Google Drive rilevato | $(if ($Stats.DriveDetected) { 'SI' } else { 'NO' }) |
| Mirror Drive creato | $(if ($Stats.MirrorCreated) { 'SI' } else { 'NO' }) |

## Sorgente
`$SourceRoot`

## Destinazione
`$BasePath`

## Struttura creata
- $($MainFolders.Count) cartelle principali
- 32 NotebookLM con 9 sotto-cartelle ciascuno
- 22 Nodi ecosistema
- 27 team AI
- 13 cartelle prompt AI

## File generati automaticamente
- README_MASTER.md
- AI_SYNC_INSTRUCTIONS.md
- NOTEBOOK_INDEX.md
- CLAUDE_CODE_NEXT_STEPS.md
- GEMMA4_LOCAL_TASKS.md
- $(if ($Stats.DriveDetected) { 'DRIVE_MIRROR_INFO.md' } else { 'DRIVE_SETUP_REQUIRED.md' })
- MANIFEST_81PLUS.csv ($($ManifestRows.Count) file)
- DUPLICATES_EXCLUDED_81PLUS.csv ($($DuplicateRows.Count) duplicati)
$(if ($ErrorLines.Count -gt 0) { "- ERRORS.log ($($ErrorLines.Count) errori)" })

## Distribuzione file per cartella
"@
    # Conta file per cartella
    if (-not $DryRun) {
        foreach ($folder in $MainFolders) {
            $fp    = Join-Path $BasePath $folder
            $count = if (Test-Path $fp) { (Get-ChildItem $fp -Recurse -File -ErrorAction SilentlyContinue).Count } else { 0 }
            $reportText += "`n| $folder | $count file |"
        }
    }

    $reportText += @"

---
*Generato da 81PLUS_ORGANIZER_MASTER.ps1 v$ScriptVersion*
*Il Delta non e dove il fiume finisce. E dove impara a diventare mare.*
"@
    Set-Content -Path (Join-Path $reportDir "REPORT_ORGANIZZAZIONE_81PLUS.md") -Value $reportText -Encoding UTF8
    Write-Log "Report organizzazione scritto." "SUCCESS"
}

# ═══════════════════════════════════════════════════════════════════════════════
# MAIN EXECUTION
# ═══════════════════════════════════════════════════════════════════════════════

Clear-Host
Write-Host ""
Write-Host "══════════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "  81PLUS_ORGANIZER_MASTER v$ScriptVersion" -ForegroundColor Cyan
Write-Host "  Organizzatore Ecosistema 81+ Global" -ForegroundColor Cyan
Write-Host "══════════════════════════════════════════════════════════════" -ForegroundColor Cyan
if ($DryRun) {
    Write-Host "  MODALITA DRY RUN — Nessun file verra copiato o creato" -ForegroundColor Yellow
}
Write-Host ""
Write-Log "Avvio: SourceRoot=$SourceRoot | DryRun=$DryRun | OpenAtEnd=$OpenAtEnd" "INFO"

# ─── Determina destinazione principale ───────────────────────────────────────
$activeMasterPath = $MasterPath

# 1. Parametro esplicito
if ($DriveMirrorRoot -and (Test-Path $DriveMirrorRoot)) {
    $activeMasterPath = Join-Path $DriveMirrorRoot $MasterFolderName
    $Stats.DriveDetected  = $true
    $Stats.MirrorCreated  = $true
    Write-Log "Drive Mirror specificato via parametro: $DriveMirrorRoot" "SUCCESS"
}
# 2. Auto-rilevamento Google Drive Desktop
elseif (-not $DriveMirrorRoot) {
    $detectedDrive = Find-GoogleDriveDesktop
    if ($detectedDrive) {
        $Stats.DriveDetected = $true
        $Stats.MirrorCreated = $true
        $activeMasterPath    = Join-Path $detectedDrive $MasterFolderName
        Write-Log "Google Drive Desktop rilevato automaticamente: $detectedDrive" "SUCCESS"
    } else {
        Write-Log "Google Drive Desktop non rilevato — struttura solo Desktop" "WARN"
    }
}

Write-Log "Destinazione master: $activeMasterPath" "INFO"
Write-Host ""

# ─── Crea struttura cartelle ─────────────────────────────────────────────────
New-MasterStructure $activeMasterPath

# Se mirror Drive attivo, crea anche copia sul Desktop (riferimento rapido locale)
if ($Stats.MirrorCreated -and $activeMasterPath -ne $MasterPath) {
    Write-Log "Creazione symlink o shortcut Desktop → Mirror Drive..." "INFO"
    if (-not $DryRun) {
        # Crea un file indicatore sul Desktop
        $indicatorPath = Join-Path $DesktopPath "81PLUS_MASTER_SU_DRIVE.txt"
        Set-Content $indicatorPath "Il master 81+ e su: $activeMasterPath`nData: $RunDate" -Encoding UTF8
    }
}

# ─── Scansiona sorgente ───────────────────────────────────────────────────────
Write-Host ""
Write-Log "Avvio scansione sorgente: $SourceRoot" "INFO"
Invoke-SourceScan $SourceRoot $activeMasterPath

# ─── Scrivi file automatici ───────────────────────────────────────────────────
Write-Host ""
Write-AutoFiles $activeMasterPath $Stats.DriveDetected $activeMasterPath

# ─── Scrivi report ────────────────────────────────────────────────────────────
Write-Reports $activeMasterPath

# ─── Riepilogo finale ─────────────────────────────────────────────────────────
Write-Host ""
Write-Host "══════════════════════════════════════════════════════════════" -ForegroundColor Green
Write-Host "  COMPLETATO$(if ($DryRun) { ' (DRY RUN)' })" -ForegroundColor Green
Write-Host "══════════════════════════════════════════════════════════════" -ForegroundColor Green
Write-Host "  File scansionati : $($Stats.FilesScanned)" -ForegroundColor White
Write-Host "  File copiati     : $($Stats.FilesCopied)" -ForegroundColor Green
Write-Host "  Duplicati skip   : $($Stats.DuplicatesSkip)" -ForegroundColor Yellow
Write-Host "  ZIP elaborati    : $($Stats.ZipsProcessed) ($($Stats.ZipFilesFound) file interni)" -ForegroundColor White
Write-Host "  Errori           : $($Stats.Errors)" -ForegroundColor $(if ($Stats.Errors -gt 0) { 'Red' } else { 'Green' })
Write-Host "  Dati copiati     : $([Math]::Round($Stats.BytesCopied/1MB,2)) MB" -ForegroundColor White
Write-Host "  Drive rilevato   : $($Stats.DriveDetected)" -ForegroundColor $(if ($Stats.DriveDetected) { 'Green' } else { 'Yellow' })
Write-Host "  Destinazione     : $activeMasterPath" -ForegroundColor Cyan
Write-Host ""
Write-Host "  Il Delta non e dove il fiume finisce." -ForegroundColor DarkGray
Write-Host "  E dove impara a diventare mare." -ForegroundColor DarkGray
Write-Host "══════════════════════════════════════════════════════════════" -ForegroundColor Green

# ─── Apri cartella ────────────────────────────────────────────────────────────
if ($OpenAtEnd -and -not $DryRun -and (Test-Path $activeMasterPath)) {
    Start-Process "explorer.exe" $activeMasterPath
}
