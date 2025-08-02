# Test script for checkout modal functionality
Write-Host "Testing checkout modal address system..." -ForegroundColor Green

# Test API endpoints that modal uses
Write-Host "`nTesting /api/provinces endpoint..." -ForegroundColor Yellow
try {
    $response = Invoke-RestMethod -Uri "http://localhost/DATN/Encryption-Shop/public/api/provinces" -Method GET
    Write-Host "✓ Provinces API working - Count: $($response.Count)" -ForegroundColor Green
} catch {
    Write-Host "✗ Provinces API failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`nTesting /api/wards endpoint..." -ForegroundColor Yellow
try {
    $response = Invoke-RestMethod -Uri "http://localhost/DATN/Encryption-Shop/public/api/wards?province=Hà Nội" -Method GET
    if ($response.value) {
        Write-Host "✓ Wards API working - Count: $($response.value.Count)" -ForegroundColor Green
        Write-Host "✓ Response format: {value: [wards]} - Correct!" -ForegroundColor Green
    } else {
        Write-Host "✗ Wards API response format incorrect" -ForegroundColor Red
        Write-Host "Response: $($response | ConvertTo-Json -Depth 3)" -ForegroundColor Gray
    }
} catch {
    Write-Host "✗ Wards API failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`nTesting checkout page load..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost/DATN/Encryption-Shop/public/checkout" -Method GET
    if ($response.StatusCode -eq 200) {
        Write-Host "✓ Checkout page loads successfully" -ForegroundColor Green
        
        # Check if modal exists in page
        if ($response.Content -match "addAddressModal") {
            Write-Host "✓ Address modal found in checkout page" -ForegroundColor Green
        } else {
            Write-Host "✗ Address modal not found in checkout page" -ForegroundColor Red
        }
        
        # Check if loadModalWards function exists
        if ($response.Content -match "loadModalWards") {
            Write-Host "✓ loadModalWards function found" -ForegroundColor Green
        } else {
            Write-Host "✗ loadModalWards function not found" -ForegroundColor Red
        }
        
        # Check for modal province select
        if ($response.Content -match "modal_province") {
            Write-Host "✓ Modal province select found" -ForegroundColor Green
        } else {
            Write-Host "✗ Modal province select not found" -ForegroundColor Red
        }
        
        # Check for modal ward select  
        if ($response.Content -match "modal_ward") {
            Write-Host "✓ Modal ward select found" -ForegroundColor Green
        } else {
            Write-Host "✗ Modal ward select not found" -ForegroundColor Red
        }
    } else {
        Write-Host "✗ Checkout page load failed" -ForegroundColor Red
    }
} catch {
    Write-Host "✗ Checkout page test failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`nCheckout modal test completed!" -ForegroundColor Cyan
