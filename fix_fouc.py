import os
import re
import glob

# Path to the views directory
views_dir = r"d:\Self Project App Farhan\ProjectIdeathon\geo-sinfra\resources\views"

# Pattern to find the Tailwind CDN
cdn_pattern = re.compile(r'<script\s+src="https://cdn\.tailwindcss\.com"></script>')

# Pattern to find the tailwind config script block.
# We will look for <script> tailwind.config = ... </script>
config_pattern = re.compile(r'<script>\s*tailwind\.config\s*=\s*\{.*?\}(?:\s*|.*?)*?</script>', re.DOTALL)

vite_directive = "@vite(['resources/css/app.css', 'resources/js/app.js'])"

files = glob.glob(os.path.join(views_dir, "**/*.blade.php"), recursive=True)

modified_count = 0

for file in files:
    if "layouts\\app.blade.php" in file or "layouts/app.blade.php" in file:
        continue
    
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if cdn_pattern.search(content):
        # Replace CDN with Vite
        content = cdn_pattern.sub(vite_directive, content)
        
        # Remove tailwind config block if it exists
        content = config_pattern.sub("", content)
        
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        
        modified_count += 1
        print(f"Fixed FOUC in: {file}")

print(f"Total files modified: {modified_count}")
