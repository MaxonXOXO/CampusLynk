import os
import glob

# Replace href="/logout" with href="{{ url('/logout') }}" in all blade files
directory = "resources/views"
count = 0

for filename in glob.glob(os.path.join(directory, "*_dashboard.blade.php")):
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if 'href="/logout"' in content:
        content = content.replace('href="/logout"', 'href="{{ url(\'/logout\') }}"')
        with open(filename, 'w', encoding='utf-8') as f:
            f.write(content)
        count += 1
        print(f"Updated {filename}")

print(f"Total files updated: {count}")
