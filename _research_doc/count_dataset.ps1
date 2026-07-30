$datasetDir = "D:\Skripsi\infrastruktur_permukiman"

if (-not (Test-Path $datasetDir)) {
    Write-Output "FOLDER TIDAK DITEMUKAN: $datasetDir"
    exit
}

Write-Output "=== DISTRIBUSI DATASET ==="
Write-Output ""

$total = 0
foreach ($jenisFolder in Get-ChildItem $datasetDir -Directory) {
    $jenisName = $jenisFolder.Name
    $jenisTotal = 0
    Write-Output "[$jenisName]"
    
    foreach ($kondisiFolder in Get-ChildItem $jenisFolder.FullName -Directory) {
        $kondisiName = $kondisiFolder.Name
        $files = Get-ChildItem $kondisiFolder.FullName -File | Where-Object { $_.Extension -match '\.(jpg|jpeg|png)$' }
        $count = ($files | Measure-Object).Count
        Write-Output "  $kondisiName : $count"
        $jenisTotal += $count
    }
    
    Write-Output "  SUBTOTAL $jenisName : $jenisTotal"
    Write-Output ""
    $total += $jenisTotal
}

Write-Output "=== GRAND TOTAL: $total ==="
