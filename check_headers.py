import os

directory = r'c:\laragon1\laragon\www\geo-sinfra\resources\views\surveyor'
files = ['dashboard.blade.php', 'show.blade.php', 'history.blade.php', 'map.blade.php', 'laporan.blade.php', 'input.blade.php', 'edit.blade.php', 'profile.blade.php']

for f in files:
    path = os.path.join(directory, f)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as file:
            lines = file.readlines()
            for i, line in enumerate(lines):
                if 'gap-6' in line and 'flex' in line and 'items-center' in line:
                    print(f'\n--- {f} ---')
                    for j in range(i, i+15):
                        if j < len(lines):
                            print(lines[j].rstrip())
                    break
