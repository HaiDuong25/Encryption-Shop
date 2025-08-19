# start-local.ps1
# Usage: Run this in PowerShell from project root (or via VS Code terminal):
#   .\start-local.ps1
# This script starts `php artisan schedule:work` in background and saves its PID,
# then starts `php artisan serve` in foreground so you can work as usual.

$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
if (-not (Test-Path $projectRoot)) { $projectRoot = Get-Location }

# Ensure storage folder exists for PID file
$storageDir = Join-Path $projectRoot 'storage'
if (-not (Test-Path $storageDir)) { New-Item -ItemType Directory -Path $storageDir | Out-Null }

$pidFile = Join-Path $storageDir 'scheduler.pid'
$php = 'php' # assume php is in PATH

# If a scheduler PID file exists, warn (don't start duplicate)
if (Test-Path $pidFile) {
    try {
        $existingPid = Get-Content $pidFile -ErrorAction Stop
        if (Get-Process -Id $existingPid -ErrorAction SilentlyContinue) {
            Write-Host "Scheduler already running with PID $existingPid. If stale, run .\stop-local.ps1 to stop it." -ForegroundColor Yellow
        } else {
            # stale pid file, remove
            Remove-Item $pidFile -ErrorAction SilentlyContinue
        }
    } catch {
        # ignore
    }
}

# Start scheduler in background
$startInfo = @{ FilePath = $php; ArgumentList = @('artisan','schedule:work'); WorkingDirectory = $projectRoot; WindowStyle = 'Hidden'; PassThru = $true }
$proc = Start-Process @startInfo
if ($proc -and $proc.Id) {
    Set-Content -Path $pidFile -Value $proc.Id
    Write-Host "Started scheduler (artisan schedule:work) with PID $($proc.Id)" -ForegroundColor Green
} else {
    Write-Host "Failed to start scheduler process." -ForegroundColor Red
}

# Start the dev server in foreground
Write-Host "Starting php artisan serve (foreground). Ctrl+C to stop serve. Scheduler will keep running in background." -ForegroundColor Cyan
& $php artisan serve

Write-Host "php artisan serve stopped. Scheduler continues to run in background until you stop it with .\stop-local.ps1 or remove the PID file." -ForegroundColor Yellow
