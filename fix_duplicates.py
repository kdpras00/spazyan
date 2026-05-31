import os
import glob

def fix_duplicates(directory):
    patterns = ['admin/*.php', 'admin/action/*.php']
    files = []
    for p in patterns:
        files.extend(glob.glob(os.path.join(directory, p)))
    
    dup1 = '            <li class="nav-item"><a class="nav-link" href="solusi.php"><i class="fas fa-fw fa-wrench"></i><span>Data Solusi</span></a></li>\n            <li class="nav-item"><a class="nav-link" href="solusi.php"><i class="fas fa-fw fa-wrench"></i><span>Data Solusi</span></a></li>'
    rep1 = '            <li class="nav-item"><a class="nav-link" href="solusi.php"><i class="fas fa-fw fa-wrench"></i><span>Data Solusi</span></a></li>'

    dup2 = '            <li class="nav-item"><a class="nav-link" href="../solusi.php"><i class="fas fa-fw fa-wrench"></i><span>Data Solusi</span></a></li>\n            <li class="nav-item"><a class="nav-link" href="../solusi.php"><i class="fas fa-fw fa-wrench"></i><span>Data Solusi</span></a></li>'
    rep2 = '            <li class="nav-item"><a class="nav-link" href="../solusi.php"><i class="fas fa-fw fa-wrench"></i><span>Data Solusi</span></a></li>'

    for f in files:
        with open(f, 'r', encoding='utf-8') as file:
            content = file.read()
        
        new_content = content.replace(dup1, rep1).replace(dup2, rep2)
        
        if new_content != content:
            with open(f, 'w', encoding='utf-8') as file:
                file.write(new_content)
            print(f"Fixed {f}")

fix_duplicates('.')
