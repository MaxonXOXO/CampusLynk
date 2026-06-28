import os
import subprocess

with open('temp.js', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace('<script>', '')
content = content.replace('</script>', '')

with open('temp.js', 'w', encoding='utf-8') as f:
    f.write(content)

result = subprocess.run(['node', '-c', 'temp.js'], capture_output=True, text=True)
print("Return code:", result.returncode)
print("Stderr:", result.stderr)
