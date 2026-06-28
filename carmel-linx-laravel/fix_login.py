import os, re
filepath = "resources/views/login.blade.php"
with open(filepath, "r", encoding="utf-8") as f:
    content = f.read()

# Replace any crazy placeholder in password field
content = re.sub(r'placeholder="Ã¢[^"]*"', 'placeholder="********"', content)

with open(filepath, "w", encoding="utf-8") as f:
    f.write(content)
print("Done")
