# stop-local.ps1
# Usage: Run this in PowerShell from project root:
#   .\stop-local.ps1
# This script stops the background scheduler started by start-local.ps1

$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
if (-not (Test-Path $projectRoot)) { $projectRoot = Get-Location }

$pidFile = Join-Path $projectRoot 'storage' 'scheduler.pid'

if (-not (Test-Path $pidFile)) {
    Write-Host "No PID file found at $pidFile. Scheduler may not be running." -ForegroundColor Yellow
    exit 0
}

try {
    $pid = Get-Content $pidFile -ErrorAction Stop
    $proc = Get-Process -Id $pid -ErrorAction SilentlyContinue
    if ($proc) {
        Write-Host "Stopping scheduler process PID $pid..." -ForegroundColor Cyan
        Stop-Process -Id $pid -Force -ErrorAction SilentlyContinue
        Start-Sleep -Seconds 1
    } else {
        Write-Host "Process with PID $pid not found. Removing stale PID file." -ForegroundColor Yellow
    }
    Remove-Item $pidFile -ErrorAction SilentlyContinue
    Write-Host "Scheduler stopped and PID file removed." -ForegroundColor Green
} catch {
    Write-Host "Failed to stop scheduler: $_" -ForegroundColor Red
}
