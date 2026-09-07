import re

files = [
    'resources/views/landing.blade.php',
    'resources/views/surveyor/map.blade.php',
    'resources/views/tim_teknis/monitoring.blade.php'
]

old_url = "'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png'"
# For Surveyor/Tim Teknis:
new_url_surv = "'https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] }"

# For Landing Page, the format might be slightly different
# Let's just do a regex replace for the tileLayer call when it contains openstreetmap.fr/hot

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # In surveyor/map.blade.php and tim_teknis/monitoring.blade.php:
    # banjir: L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', { maxZoom: 19 })
    if "banjir: L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', { maxZoom: 19 })" in content:
        content = content.replace(
            "banjir: L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', { maxZoom: 19 })",
            "banjir: L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] })"
        )
        
    # In landing.blade.php:
    # activeBasemap = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
    #                 maxZoom: 19,
    #                 attribution: '© OpenStreetMap contributors'
    #             }).addTo(map);
    if "https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png" in content:
        # replace the whole block
        old_block = """            } else if(type === 'banjir') {
                activeBasemap = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                showBanjir = true;
            }"""
            
        new_block = """            } else if(type === 'banjir') {
                activeBasemap = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains:['mt0','mt1','mt2','mt3']
                }).addTo(map);
                showBanjir = true;
            }"""
            
        content = content.replace(old_block, new_block)
        
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

for f in files:
    fix_file(f)

print("Tiles fixed")
