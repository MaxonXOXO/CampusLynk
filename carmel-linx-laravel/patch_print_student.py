import os

with open('app/Http/Controllers/MentoringController.php', 'r', encoding='utf-8') as f:
    content = f.read()

old = "$data = (array) ($responseData->data ?? $responseData);"
new = "$data = (array) ($responseData->data ?? $responseData);\n        $data['student'] = $data['profile'] ?? null;"

content = content.replace(old, new)

with open('app/Http/Controllers/MentoringController.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Patched MentoringController print mapping")
