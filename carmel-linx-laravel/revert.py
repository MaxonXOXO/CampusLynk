import os, re

def revert_blade(filepath):
    if not os.path.exists(filepath): return
    # Read with latin1 so we don't crash, then we can clean up
    with open(filepath, "r", encoding="latin1") as f:
        content = f.read()

    # Revert <?php echo e(...) ?> to {{ ... }}
    content = re.sub(r"<\?php\s+echo\s+e\((.*?)\);\s*\?>", r"{{ \1 }}", content)
    
    # Revert <?php if(...): ?> to @if(...)
    content = re.sub(r"<\?php\s+if\((.*?)\):\s*\?>", r"@if(\1)", content)
    
    # Revert <?php elseif(...): ?> to @elseif(...)
    content = re.sub(r"<\?php\s+elseif\((.*?)\):\s*\?>", r"@elseif(\1)", content)
    
    # Revert <?php else: ?> to @else
    content = re.sub(r"<\?php\s+else:\s*\?>", r"@else", content)
    
    # Revert <?php endif; ?> to @endif
    content = re.sub(r"<\?php\s+endif;\s*\?>", r"@endif", content)
    
    # Revert <?php foreach(...): ?> to @foreach(...)
    content = re.sub(r"<\?php\s+foreach\((.*?)\):\s*\?>", r"@foreach(\1)", content)
    
    # Revert <?php endforeach; ?> to @endforeach
    content = re.sub(r"<\?php\s+endforeach;\s*\?>", r"@endforeach", content)

    # Remove endpath comments
    content = re.sub(r"<\?php\s+/\*\*PATH.*?\*/\s*\?>", "", content)

    with open(filepath, "w", encoding="utf-8") as f:
        f.write(content)

for root, _, files in os.walk("resources/views"):
    for file in files:
        if file.endswith(".blade.php"):
            revert_blade(os.path.join(root, file))
