$utf8NoBom = New-Object System.Text.UTF8Encoding $false
Get-ChildItem -Path c:\laragon1\laragon\www\geo-sinfra\resources\views -Recurse -Filter *.blade.php | ForEach-Object {
    $content = [System.IO.File]::ReadAllText($_.FullName)
    if ($content -match 'zoom:\s*80%') {
        $content = $content -replace '(?i)body\s*\{\s*zoom:\s*80%;\s*\}', "html {`n                font-size: 12.8px;`n            }"
        [System.IO.File]::WriteAllText($_.FullName, $content, $utf8NoBom)
        Write-Host "Fixed $($_.FullName)"
    }
}
