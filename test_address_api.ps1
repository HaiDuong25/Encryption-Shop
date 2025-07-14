# Test script cho Address API
Write-Host "=== Test Address API ===" -ForegroundColor Green

# Khởi động server
Write-Host "Khởi động Laravel server..." -ForegroundColor Yellow
Start-Process -FilePath "php" -ArgumentList "artisan", "serve", "--host=127.0.0.1", "--port=8000" -NoNewWindow
Start-Sleep 3

Write-Host "Testing API endpoints..." -ForegroundColor Yellow

# Test districts API
Write-Host "`n1. Test Districts API với Hà Nội:" -ForegroundColor Cyan
$response1 = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/districts?province=H%C3%A0%20N%E1%BB%99i" -Method Get
Write-Host "Số lượng quận/huyện: $($response1.Count)" -ForegroundColor White
$response1[0..4] | ForEach-Object { Write-Host "  - $_" -ForegroundColor Gray }

# Test wards API
Write-Host "`n2. Test Wards API với Quận Ba Đình:" -ForegroundColor Cyan
$response2 = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/wards?district=Qu%E1%BA%ADn%20Ba%20%C4%90%C3%ACnh" -Method Get
Write-Host "Số lượng phường/xã: $($response2.Count)" -ForegroundColor White
$response2[0..4] | ForEach-Object { Write-Host "  - $_" -ForegroundColor Gray }

# Test với TP.HCM
Write-Host "`n3. Test Districts API với TP.HCM:" -ForegroundColor Cyan
$response3 = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/districts?province=TP%20H%E1%BB%93%20Ch%C3%AD%20Minh" -Method Get
Write-Host "Số lượng quận/huyện: $($response3.Count)" -ForegroundColor White
$response3[0..4] | ForEach-Object { Write-Host "  - $_" -ForegroundColor Gray }

# Test wards với Quận 1
Write-Host "`n4. Test Wards API với Quận 1:" -ForegroundColor Cyan
$response4 = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/wards?district=Qu%E1%BA%ADn%201" -Method Get
Write-Host "Số lượng phường/xã: $($response4.Count)" -ForegroundColor White
$response4[0..4] | ForEach-Object { Write-Host "  - $_" -ForegroundColor Gray }

Write-Host "`n=== Test completed ===" -ForegroundColor Green
Write-Host "Bạn có thể truy cập: http://127.0.0.1:8000/admin/shipping-addresses/create để test form" -ForegroundColor Yellow
Write-Host "Nhấn Enter để dừng server..." -ForegroundColor Red
Read-Host

# Dừng server
Get-Process | Where-Object {$_.ProcessName -eq "php"} | Stop-Process -Force
Write-Host "Server đã dừng!" -ForegroundColor Green
