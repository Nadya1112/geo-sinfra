$utf8NoBom = New-Object System.Text.UTF8Encoding $false
$style = "<style>`n    @media (min-width: 768px) { html { zoom: 0.9 !important; } }`n    @media (max-width: 767px) { html { zoom: 0.5 !important; } }`n</style>`n</head>"

Get-ChildItem -Path c:\laragon1\laragon\www\geo-sinfra\resources\views -Recurse -Filter *.blade.php | ForEach-Object {
    $content = [System.IO.File]::ReadAllText($_.FullName)
    
    # Hapus style zoom lama jika ada agar tidak menumpuk
    $content = $content -replace '(?s)<style>\s*@media \(min-width: 768px\) \{ html \{ zoom:.*?</style>\s*', ''
    
    if ($content -match '</head>') {
        $content = $content -replace '</head>', $style
        [System.IO.File]::WriteAllText($_.FullName, $content, $utf8NoBom)
        Write-Host "Berhasil menerapkan zoom di: $($_.Name)"
    }
}
