import os
import re
import glob

def add_solusi_menu(directory):
    # files with sidebar
    patterns = ['admin/*.php', 'admin/action/*.php']
    files = []
    for p in patterns:
        files.extend(glob.glob(os.path.join(directory, p)))
    
    for f in files:
        # Ignore solutions files themselves
        if 'solusi.php' in f: continue
        with open(f, 'r', encoding='utf-8') as file:
            content = file.read()
        
        # Check if already has solusi
        if '<span>Data Solusi</span>' in content:
            continue
            
        # Determine correct path prefix depending on if it's in action folder
        path_prefix = '../' if 'action/' in f else ''
        
        # Find the Data Kerusakan menu item using regex
        # It can be single line or multiline.
        pattern = r'(<li class="nav-item(?: active)?">\s*<a class="nav-link" href=".*?kerusakan\.php">\s*<i class="fas fa-fw fa-oil-can"></i>\s*<span>Data Kerusakan</span></a>\s*</li>)'
        
        # Replacement text (formatted as single line to be safe)
        replacement = r'\1\n            <li class="nav-item"><a class="nav-link" href="' + path_prefix + r'solusi.php"><i class="fas fa-fw fa-wrench"></i><span>Data Solusi</span></a></li>'
        
        new_content = re.sub(pattern, replacement, content, flags=re.IGNORECASE)
        
        # Try a slightly different pattern for index.php which has '</a> </li>'
        pattern2 = r'(<li class="nav-item(?: active)?">\s*<a class="nav-link" href=".*?kerusakan\.php">\s*<i class="fas fa-fw fa-oil-can"></i>\s*<span>Data Kerusakan</span></a>\s*</li>)'
        
        new_content = re.sub(pattern2, replacement, new_content, flags=re.IGNORECASE)

        if new_content != content:
            with open(f, 'w', encoding='utf-8') as file:
                file.write(new_content)
            print(f"Updated {f}")
        else:
            print(f"No match in {f}")

add_solusi_menu('.')
