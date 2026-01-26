$baseUrl = "http://api-cursos.test/api"

# 1. Login to get token
Write-Host "1. Logging in..." -ForegroundColor Cyan
$loginBody = @{
    email = "admin@example.com"
    password = "password123"
    device_name = "PowerShell Client"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/login" -Method Post -Body $loginBody -ContentType "application/json"
    $token = $loginResponse.token
    Write-Host "Success! Token received." -ForegroundColor Green
} catch {
    Write-Host "Login failed. Ensure database has seeded users." -ForegroundColor Red
    Write-Host $_.Exception.Message
    exit
}

# 2. Get Current User Profile
Write-Host "`n2. Getting current profile..." -ForegroundColor Cyan
$headers = @{
    Authorization = "Bearer $token"
    Accept = "application/json"
}
$user = Invoke-RestMethod -Uri "$baseUrl/user" -Method Get -Headers $headers
Write-Host "Current Name: $($user.user.name)" -ForegroundColor Yellow

# 3. Update Profile
$newName = "Admin Updated " + (Get-Random)
Write-Host "`n3. Updating profile name to: $newName" -ForegroundColor Cyan
$updateBody = @{
    name = $newName
    email = "admin@example.com"
} | ConvertTo-Json

try {
    $updateResponse = Invoke-RestMethod -Uri "$baseUrl/user" -Method Put -Headers $headers -Body $updateBody -ContentType "application/json"
    Write-Host "Success! Response:" -ForegroundColor Green
    Write-Host ($updateResponse | ConvertTo-Json -Depth 2)
} catch {
    Write-Host "Update failed." -ForegroundColor Red
    Write-Host $_.Exception.Message
    Write-Host $_.ErrorDetails
}
