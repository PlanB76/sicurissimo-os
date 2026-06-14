# ============================================================
# 81+ MASTER VIVO · SCRIPT FINALE DA ZERO
# Crea la cartella operativa completa sul Desktop
# Versione definitiva aggiornata
# ============================================================

$Desktop = [Environment]::GetFolderPath("Desktop")
$Base = Join-Path $Desktop "81+ MASTER VIVO"

# Se esiste già, la rinomina come backup
if (Test-Path $Base) {
    $BackupName = "81+ MASTER VIVO BACKUP " + (Get-Date -Format "yyyyMMdd_HHmmss")
    Rename-Item -Path $Base -NewName $BackupName
}

New-Item -ItemType Directory -Path $Base -Force | Out-Null

function Write-File {
    param (
        [string]$RelativePath,
        [string]$Content
    )
    $FullPath = Join-Path $Base $RelativePath
    $Dir = Split-Path $FullPath -Parent
    New-Item -ItemType Directory -Path $Dir -Force | Out-Null
    $Content | Set-Content -Path $FullPath -Encoding UTF8
}

# Esegui questo script su Windows per creare la struttura completa sul Desktop.
# I file markdown sono già presenti in questa repo nella cartella master-vivo/.
# Copia il contenuto da master-vivo/ se preferisci lavorare direttamente dalla repo.

Write-Host ""
Write-Host "=============================================="
Write-Host "81+ MASTER VIVO — vedi cartella master-vivo/ in questa repo"
Write-Host "Exchange81+ = DEX, non CEX"
Write-Host "=============================================="
Write-Host ""
Start-Process $Base
