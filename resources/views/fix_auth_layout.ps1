$utf8NoBom = New-Object System.Text.UTF8Encoding $false
Get-ChildItem -Path c:\laragon1\laragon\www\geo-sinfra\resources\views\auth -Filter *.blade.php | ForEach-Object {
    $content = [System.IO.File]::ReadAllText($_.FullName)
    $newContent = $content -replace 'h-screen overflow-hidden', 'min-h-screen' -replace 'overflow-y-auto', ''
    if ($content -ne $newContent) {
        [System.IO.File]::WriteAllText($_.FullName, $newContent, $utf8NoBom)
        Write-Host "Fixed $($_.FullName)"
    }
}
