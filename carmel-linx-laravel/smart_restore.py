import os, re
import html

# Fix junk characters in all blade files
def fix_encoding_junk(filepath):
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()
    
    # login.blade.php placeholder
    content = content.replace('placeholder="Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢"', 'placeholder="********"')
    
    with open(filepath, "w", encoding="utf-8") as f:
        f.write(content)

for root, _, files in os.walk("resources/views"):
    for file in files:
        if file.endswith(".blade.php"):
            fix_encoding_junk(os.path.join(root, file))

# Now try to restore font sizes from the 9 AM backup using a smart regex
backup_dir = "../carmel-linx-laravel_restoration_point_1_26june26_9am/resources/views"
current_dir = "resources/views"

def extract_classes(text):
    return re.findall(r'class="([^"]*)"', text)

def replace_fonts(current_class_str, backup_class_str):
    # Extract only the font size classes from backup
    font_pattern = r'\btext-(?:\[.*?px\]|xs|sm|base|lg|xl|2xl|3xl|4xl|5xl)\b'
    backup_fonts = set(re.findall(font_pattern, backup_class_str))
    
    # Remove any font size classes from current
    cleaned_current = re.sub(font_pattern, "", current_class_str)
    
    # Add back the fonts from backup
    final_classes = " ".join(cleaned_current.split() + list(backup_fonts))
    return final_classes

def smart_restore_fonts(file_name):
    backup_path = os.path.join(backup_dir, file_name)
    current_path = os.path.join(current_dir, file_name)
    
    if not os.path.exists(backup_path) or not os.path.exists(current_path):
        return

    with open(backup_path, "r", encoding="utf-8") as f:
        backup_lines = f.readlines()
        
    with open(current_path, "r", encoding="utf-8") as f:
        current_lines = f.readlines()
        
    # We will just do a simple line-by-line comparison. If the lines are identical when ignoring classes, we sync the classes.
    def strip_classes(line):
        return re.sub(r'class="[^"]*"', 'class=""', line.strip())

    for i in range(min(len(current_lines), len(backup_lines))):
        c_strip = strip_classes(current_lines[i])
        b_strip = strip_classes(backup_lines[i])
        
        if c_strip == b_strip:
            # Lines match structurally!
            b_classes = extract_classes(backup_lines[i])
            c_classes = extract_classes(current_lines[i])
            
            if len(b_classes) == len(c_classes) and len(c_classes) > 0:
                # We do a replacement for each class string
                new_line = current_lines[i]
                for j in range(len(c_classes)):
                    new_class_str = replace_fonts(c_classes[j], b_classes[j])
                    new_line = new_line.replace(f'class="{c_classes[j]}"', f'class="{new_class_str}"')
                current_lines[i] = new_line

    with open(current_path, "w", encoding="utf-8") as f:
        f.writelines(current_lines)

for file in os.listdir(current_dir):
    if file.endswith(".blade.php"):
        smart_restore_fonts(file)

print("Done restoring fonts smartly.")
