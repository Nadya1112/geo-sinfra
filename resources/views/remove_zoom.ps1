$utf8NoBom = New-Object System.Text.UTF8Encoding $false
Get-ChildItem -Path c:\laragon1\laragon\www\geo-sinfra\resources\views -Recurse -Filter *.blade.php | ForEach-Object {
    $content = [System.IO.File]::ReadAllText($_.FullName)
    $pattern1 = '(?s)\s*@media\s*\(min-width:\s*1024px\)\s*\{\s*body\s*\{\s*zoom:\s*80%;\s*\}\s*\}'
    $pattern2 = '(?s)\s*@media\s*\(min-width:\s*1024px\)\s*\{\s*html\s*\{\s*font-size:\s*12.8px;\s*\}\s*\}'
    
    $newContent = $content -replace $pattern1, '' -replace $pattern2, ''
    if ($content -ne $newContent) {
        [System.IO.File]::WriteAllText($_.FullName, $newContent, $utf8NoBom)
        Write-Host "Reverted $($_.FullName)"
    }
}
