param(
    [string]$MasterRoot = "C:\81PLUS_GLOBAL_MASTER",
    [string]$OllamaUrl = "http://localhost:11434/api/generate",
    [string]$Model = "gemma4",
    [int]$IntervalSeconds = 30,
    [int]$MaxChars = 12000,
    [switch]$RunOnce,
    [switch]$MoveRejected,
    [switch]$OpenAtStart
)

$ErrorActionPreference = "Continue"

# ============================================================
# 81PLUS LOCAL AI WATCHDOG
# Versione pulita ASCII per Windows PowerShell
#
# Cosa fa:
# - monitora C:\81PLUS_GLOBAL_MASTER
# - usa Ollama/Gemma4 in locale
# - analizza file testuali nuovi o modificati
# - scrive indice CSV
# - scrive analisi JSON
# - copia o sposta file problematici in _QUARANTENA
# - crea cartelle di controllo
#
# Non cancella file originali, salvo se usi -MoveRejected.
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

function Get-HashSafe {
    param([string]$Path)
    try {
        return (Get-FileHash -Algorithm SHA256 -LiteralPath $Path).Hash
    } catch {
        return $null
    }
}

function Wait-FileReady {
    param(
        [string]$Path,
        [int]$Retries = 8,
        [int]$DelayMs = 500
    )

    for ($i = 0; $i -lt $Retries; $i++) {
        try {
            $s1 = (Get-Item -LiteralPath $Path -ErrorAction Stop).Length
            Start-Sleep -Milliseconds $DelayMs
            $s2 = (Get-Item -LiteralPath $Path -ErrorAction Stop).Length

            if ($s1 -eq $s2) {
                $stream = [System.IO.File]::Open($Path, "Open", "Read", "ReadWrite")
                $stream.Close()
                return $true
            }
        } catch {
            Start-Sleep -Milliseconds $DelayMs
        }
    }

    return $false
}

function Read-TextSafe {
    param([string]$Path)

    try {
        return Get-Content -LiteralPath $Path -Raw -Encoding UTF8
    } catch {
        try {
            return Get-Content -LiteralPath $Path -Raw
        } catch {
            return $null
        }
    }
}

function Is-TextFile {
    param([string]$Path)

    $ext = [System.IO.Path]::GetExtension($Path).ToLowerInvariant()

    $allowed = @(
        ".txt",
        ".md",
        ".csv",
        ".json",
        ".xml",
        ".html",
        ".htm",
        ".css",
        ".js",
        ".php",
        ".sql",
        ".ps1",
        ".py",
        ".yaml",
        ".yml",
        ".ini",
        ".log"
    )

    return $allowed -contains $ext
}

function Get-AreaByPath {
    param([string]$Path)

    $p = $Path.ToLowerInvariant()

    if ($p -match "master|blueprint|dna|decisioni|changelog|roadmap|naming|semantic") { return "MASTER_VIVO" }
    if ($p -match "legale|regolamento|privacy|cookie|gdpr|contratto|disclaimer|dpo|mica|termini") { return "LEGALE_REGOLAMENTI" }
    if ($p -match "hub1|sic-id|sso|kyc|dashboard|wallet|paygate|missione|ruota|piramide") { return "HUB1" }
    if ($p -match "hub2|sicurissimo|dvr|pos|duvri|haccp|privacy|corsi|formazione|documenti") { return "HUB2" }
    if ($p -match "hub3|web3|saf|81x|nft|metaverso|dao|token") { return "HUB3" }
    if ($p -match "marketing|social|ads|newsletter|webinar|lead magnet|funnel|copy|aida|epppa|repppa") { return "MARKETING" }
    if ($p -match "scout81|lead pack|prospect|osint|scraping|opt-out") { return "SCOUT81" }
    if ($p -match "sales|crm|networker|pipeline|follow|closing|script vendita") { return "SALES_CRM" }
    if ($p -match "pvplus|pv plus|pv\+|career81|equilibrium|eq1|eq2|eq3|bounty|exchange") { return "PV_PVPLUS_CAREER_EQ" }
    if ($p -match "gamification|retention|badge|leaderboard|wall of fame|buddy|diario|arena|mystery|streak|maslow") { return "GAMIFICATION" }
    if ($p -match "pass|kit|sdp|membership") { return "PASS_KIT_MEMBERSHIP" }
    if ($p -match "sicurissimo point|franchising|territorio|franchiser|point81") { return "SICURISSIMO_POINT81" }
    if ($p -match "welfare|hr|dipendenti|micro-learning|clima") { return "WELFARE" }
    if ($p -match "visual|brand|logo|figma|canva|mockup|prompt gemini") { return "VISUAL" }
    if ($p -match "claude code|github|php|javascript|html|css|backend|frontend|deploy|hostinger|n8n") { return "TECH" }
    if ($p -match "database|mysql|schema|erd|table|api docs|data dictionary|event taxonomy") { return "DATABASE" }
    if ($p -match "cashflow|economia|tasse|fisc|commercialista|iva|fatture|business plan|margini|pricing") { return "ECONOMIA_TASSE" }
    if ($p -match "startup innovativa|bando|bandi|finanziamenti|smart|invitalia|voucher|funding") { return "STARTUP_BANDI" }
    if ($p -match "investitori|angels|banche|fondi|pitch|term sheet|valuation|due diligence|exit") { return "INVESTITORI" }
    if ($p -match "qa|quality|bonifica|test|bug|approval|go no go|release") { return "QA_BONIFICA" }
    if ($p -match "security|cyber|backup|incident|access control|api security") { return "SECURITY" }

    return "DA_ANALIZZARE"
}

function Call-Gemma4 {
    param(
        [string]$Content,
        [string]$FilePath,
        [string]$Area
    )

    if ($Content.Length -gt $MaxChars) {
        $Content = $Content.Substring(0, $MaxChars)
    }

    $prompt = @"
Sei Gemma4 locale per 81+ Global.
Analizza il seguente file del progetto 81+.

Regole fondamentali:
- Non usare SafePoint. Il nome corretto e SICURISSIMO POINT81+.
- Segnala se trovi promesse rischiose come investimento, rendimento, ROI, APY, staking, guadagno garantito, rischio zero, zero multe, liquidita garantita.
- Segnala errori gravi di compliance, privacy, marketing, fiscale, Web3 o payout.
- Segnala se il file e utile per MVP Go-Live.
- Suggerisci la cartella corretta.
- Rispondi SOLO con JSON valido.
- Non usare markdown.

Schema JSON obbligatorio:
{
  "status": "APPROVED oppure REVIEW oppure REJECTED",
  "area": "$Area",
  "summary": "breve sintesi",
  "risks": ["rischio 1", "rischio 2"],
  "actions": ["azione 1", "azione 2"],
  "destination_hint": "cartella consigliata",
  "human_approval_required": true
}

File:
$FilePath

Contenuto:
$Content
"@

    $body = @{
        model = $Model
        prompt = $prompt
        stream = $false
    } | ConvertTo-Json -Depth 5

    try {
        $response = Invoke-RestMethod -Uri $OllamaUrl -Method Post -Body $body -ContentType "application/json" -TimeoutSec 180
        return $response.response
    } catch {
        return "{""status"":""REVIEW"",""area"":""$Area"",""summary"":""Errore chiamata Ollama o modello non disponibile."",""risks"":[""OLLAMA_ERROR""],""actions"":[""Verificare che Ollama sia avviato e che il modello $Model esista.""],""destination_hint"":""_DA_VALIDARE"",""human_approval_required"":true}"
    }
}

function Parse-AnalysisStatus {
    param([string]$Raw)

    $result = @{
        Status = "REVIEW"
        Summary = ""
        Risks = ""
        Actions = ""
        DestinationHint = ""
        HumanApproval = "true"
    }

    try {
        $jsonStart = $Raw.IndexOf("{")
        $jsonEnd = $Raw.LastIndexOf("}")

        if ($jsonStart -ge 0 -and $jsonEnd -gt $jsonStart) {
            $jsonText = $Raw.Substring($jsonStart, $jsonEnd - $jsonStart + 1)
            $obj = $jsonText | ConvertFrom-Json

            if ($obj.status) { $result.Status = [string]$obj.status }
            if ($obj.summary) { $result.Summary = [string]$obj.summary }
            if ($obj.risks) { $result.Risks = ($obj.risks -join " | ") }
            if ($obj.actions) { $result.Actions = ($obj.actions -join " | ") }
            if ($obj.destination_hint) { $result.DestinationHint = [string]$obj.destination_hint }
            if ($null -ne $obj.human_approval_required) { $result.HumanApproval = [string]$obj.human_approval_required }
        } else {
            if ($Raw -match "REJECTED") { $result.Status = "REJECTED" }
            elseif ($Raw -match "APPROVED") { $result.Status = "APPROVED" }
            else { $result.Status = "REVIEW" }

            $result.Summary = $Raw.Substring(0, [Math]::Min(300, $Raw.Length))
        }
    } catch {
        $result.Status = "REVIEW"
        $result.Summary = "Parsing JSON fallito. Revisione umana richiesta."
        $result.Risks = "JSON_PARSE_ERROR"
    }

    $statusUpper = $result.Status.ToUpperInvariant()

    if ($statusUpper -notin @("APPROVED", "REVIEW", "REJECTED")) {
        $result.Status = "REVIEW"
    } else {
        $result.Status = $statusUpper
    }

    return $result
}

function Append-CsvRow {
    param(
        [string]$CsvPath,
        [object]$Row
    )

    if (!(Test-Path -LiteralPath $CsvPath)) {
        $Row | Export-Csv -LiteralPath $CsvPath -NoTypeInformation -Encoding UTF8
    } else {
        $Row | Export-Csv -LiteralPath $CsvPath -NoTypeInformation -Append -Encoding UTF8
    }
}

function Should-SkipFile {
    param([string]$Path)

    $p = $Path.ToLowerInvariant()

    if ($p -match "\\_logs\\") { return $true }
    if ($p -match "\\_state\\") { return $true }
    if ($p -match "\\_quarantena\\") { return $true }
    if ($p -match "\\_gemma_outputs\\") { return $true }
    if ($p -match "\\_approved\\") { return $true }
    if ($p -match "\\_review\\") { return $true }
    if ($p -match "\\_outbox\\") { return $true }
    if ($p -match "\\30_archivio_storico\\") { return $true }

    $name = [System.IO.Path]::GetFileName($Path).ToLowerInvariant()

    if ($name -eq "_index.csv") { return $true }
    if ($name -eq "processed_hashes.txt") { return $true }
    if ($name -eq "errors.log") { return $true }

    return $false
}

# ============================================================
# Setup folders
# ============================================================

New-Folder $MasterRoot

$LogsDir = Join-Path $MasterRoot "_LOGS"
$StateDir = Join-Path $MasterRoot "_STATE"
$QuarantineDir = Join-Path $MasterRoot "_QUARANTENA"
$ReviewDir = Join-Path $MasterRoot "_REVIEW"
$ApprovedDir = Join-Path $MasterRoot "_APPROVED"
$GemmaOutDir = Join-Path $MasterRoot "_GEMMA_OUTPUTS"
$OutboxDir = Join-Path $MasterRoot "_OUTBOX"

New-Folder $LogsDir
New-Folder $StateDir
New-Folder $QuarantineDir
New-Folder $ReviewDir
New-Folder $ApprovedDir
New-Folder $GemmaOutDir
New-Folder $OutboxDir

New-Folder (Join-Path $OutboxDir "CLAUDE_CODE")
New-Folder (Join-Path $OutboxDir "CLAUDE_COWORK")
New-Folder (Join-Path $OutboxDir "CHATGPT")
New-Folder (Join-Path $OutboxDir "GEMINI")
New-Folder (Join-Path $OutboxDir "NOTEBOOKLM")
New-Folder (Join-Path $OutboxDir "GEMMA4")

$IndexCsv = Join-Path $MasterRoot "_INDEX.csv"
$ProcessedHashesPath = Join-Path $StateDir "processed_hashes.txt"
$ErrorLog = Join-Path $LogsDir "errors.log"

if (!(Test-Path -LiteralPath $ProcessedHashesPath)) {
    New-Item -ItemType File -Path $ProcessedHashesPath -Force | Out-Null
}

$StartupReadme = @"
# 81PLUS LOCAL AI WATCHDOG

MasterRoot:
$MasterRoot

Ollama:
$OllamaUrl

Model:
$Model

Cartelle:
- _INDEX.csv: indice file analizzati
- _GEMMA_OUTPUTS: analisi JSON
- _QUARANTENA: file respinti o rischiosi
- _REVIEW: file da rivedere
- _APPROVED: copie dei file approvati
- _OUTBOX: output per Claude, ChatGPT, Gemini, NotebookLM, Gemma4

Nota:
Questo sistema non sostituisce legale, commercialista, DPO, HSE o validatore tecnico.
"@

Set-Content -LiteralPath (Join-Path $MasterRoot "_README_LOCAL_AI_WATCHDOG.md") -Value $StartupReadme -Encoding UTF8

if ($OpenAtStart) {
    Start-Process explorer.exe $MasterRoot
}

# ============================================================
# Main loop
# ============================================================

Write-Ok "81PLUS Local AI Watchdog started."
Write-Info "MasterRoot: $MasterRoot"
Write-Info "OllamaUrl: $OllamaUrl"
Write-Info "Model: $Model"
Write-Info "Interval seconds: $IntervalSeconds"
Write-Info "Press CTRL+C to stop."

while ($true) {
    try {
        $processedHashes = @{}

        if (Test-Path -LiteralPath $ProcessedHashesPath) {
            Get-Content -LiteralPath $ProcessedHashesPath -ErrorAction SilentlyContinue | ForEach-Object {
                if (![string]::IsNullOrWhiteSpace($_)) {
                    $processedHashes[$_] = $true
                }
            }
        }

        $files = Get-ChildItem -LiteralPath $MasterRoot -Recurse -File -ErrorAction SilentlyContinue |
            Where-Object {
                -not (Should-SkipFile $_.FullName) -and
                $_.Length -gt 0
            }

        foreach ($file in $files) {
            if (!(Wait-FileReady -Path $file.FullName)) {
                continue
            }

            $hash = Get-HashSafe $file.FullName

            if ($null -eq $hash) {
                Add-Content -LiteralPath $ErrorLog -Value ("HASH_ERROR;" + $file.FullName)
                continue
            }

            if ($processedHashes.ContainsKey($hash)) {
                continue
            }

            $area = Get-AreaByPath -Path $file.FullName
            $ext = [System.IO.Path]::GetExtension($file.FullName).ToLowerInvariant()

            if (!(Is-TextFile -Path $file.FullName)) {
                $row = [PSCustomObject]@{
                    Date = (Get-Date).ToString("yyyy-MM-dd HH:mm:ss")
                    Status = "SKIPPED_BINARY"
                    Area = $area
                    FilePath = $file.FullName
                    Hash = $hash
                    SizeBytes = $file.Length
                    AnalysisPath = ""
                    Summary = "File non testuale. Non inviato a Gemma4."
                    Risks = ""
                    Actions = "Se serve, convertire in testo o analizzare manualmente."
                    HumanApprovalRequired = "true"
                }

                Append-CsvRow -CsvPath $IndexCsv -Row $row
                Add-Content -LiteralPath $ProcessedHashesPath -Value $hash
                continue
            }

            $content = Read-TextSafe -Path $file.FullName

            if ($null -eq $content -or [string]::IsNullOrWhiteSpace($content)) {
                $row = [PSCustomObject]@{
                    Date = (Get-Date).ToString("yyyy-MM-dd HH:mm:ss")
                    Status = "EMPTY_OR_UNREADABLE"
                    Area = $area
                    FilePath = $file.FullName
                    Hash = $hash
                    SizeBytes = $file.Length
                    AnalysisPath = ""
                    Summary = "File vuoto o non leggibile."
                    Risks = "READ_ERROR"
                    Actions = "Controllare manualmente."
                    HumanApprovalRequired = "true"
                }

                Append-CsvRow -CsvPath $IndexCsv -Row $row
                Add-Content -LiteralPath $ProcessedHashesPath -Value $hash
                continue
            }

            Write-Info ("Analyzing: " + $file.FullName)

            $rawAnalysis = Call-Gemma4 -Content $content -FilePath $file.FullName -Area $area
            $parsed = Parse-AnalysisStatus -Raw $rawAnalysis

            $baseName = Safe-Name ([System.IO.Path]::GetFileNameWithoutExtension($file.Name))
            $analysisFile = Join-Path $GemmaOutDir ((Get-Date -Format "yyyyMMdd_HHmmss") + "__" + $baseName + "__analysis.json")

            $analysisPack = @{
                date = (Get-Date).ToString("yyyy-MM-dd HH:mm:ss")
                file = $file.FullName
                hash = $hash
                area = $area
                raw_response = $rawAnalysis
                parsed_status = $parsed
            } | ConvertTo-Json -Depth 8

            Set-Content -LiteralPath $analysisFile -Value $analysisPack -Encoding UTF8

            $targetCopy = ""

            if ($parsed.Status -eq "REJECTED") {
                $targetCopy = Join-Path $QuarantineDir $file.Name

                if ($MoveRejected) {
                    try {
                        Move-Item -LiteralPath $file.FullName -Destination $targetCopy -Force
                    } catch {
                        Add-Content -LiteralPath $ErrorLog -Value ("MOVE_REJECTED_ERROR;" + $file.FullName + ";" + $_.Exception.Message)
                    }
                } else {
                    try {
                        Copy-Item -LiteralPath $file.FullName -Destination $targetCopy -Force
                    } catch {
                        Add-Content -LiteralPath $ErrorLog -Value ("COPY_QUARANTINE_ERROR;" + $file.FullName + ";" + $_.Exception.Message)
                    }
                }
            }
            elseif ($parsed.Status -eq "REVIEW") {
                $targetCopy = Join-Path $ReviewDir $file.Name
                try {
                    Copy-Item -LiteralPath $file.FullName -Destination $targetCopy -Force
                } catch {
                    Add-Content -LiteralPath $ErrorLog -Value ("COPY_REVIEW_ERROR;" + $file.FullName + ";" + $_.Exception.Message)
                }
            }
            elseif ($parsed.Status -eq "APPROVED") {
                $targetCopy = Join-Path $ApprovedDir $file.Name
                try {
                    Copy-Item -LiteralPath $file.FullName -Destination $targetCopy -Force
                } catch {
                    Add-Content -LiteralPath $ErrorLog -Value ("COPY_APPROVED_ERROR;" + $file.FullName + ";" + $_.Exception.Message)
                }
            }

            $row = [PSCustomObject]@{
                Date = (Get-Date).ToString("yyyy-MM-dd HH:mm:ss")
                Status = $parsed.Status
                Area = $area
                FilePath = $file.FullName
                Hash = $hash
                SizeBytes = $file.Length
                AnalysisPath = $analysisFile
                Summary = $parsed.Summary
                Risks = $parsed.Risks
                Actions = $parsed.Actions
                DestinationHint = $parsed.DestinationHint
                HumanApprovalRequired = $parsed.HumanApproval
                OutputCopy = $targetCopy
            }

            Append-CsvRow -CsvPath $IndexCsv -Row $row
            Add-Content -LiteralPath $ProcessedHashesPath -Value $hash

            Write-Ok ("Status: " + $parsed.Status + " | Area: " + $area)
        }
    } catch {
        Add-Content -LiteralPath $ErrorLog -Value ("LOOP_ERROR;" + (Get-Date).ToString("yyyy-MM-dd HH:mm:ss") + ";" + $_.Exception.Message)
    }

    if ($RunOnce) {
        break
    }

    Start-Sleep -Seconds $IntervalSeconds
}
