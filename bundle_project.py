import os

# Root directory of the project
project_dir = os.path.dirname(os.path.abspath(__file__))
output_file = os.path.join(project_dir, 'project_codebase.txt')

# Directories to exclude
exclude_dirs = {'.git', '.agents', 'tmp'}

# Files to exclude
exclude_files = {'bundle_project.py', 'project_codebase.txt', 'content.txt'}

# Allowed text-based extensions
allowed_extensions = {
    '.php', '.sql', '.html', '.css', '.js', '.md', '.json', '.htaccess', '.gitignore'
}

def build_tree(dir_path, prefix=""):
    """Generates a text-based tree representation of the directory structure."""
    tree_lines = []
    try:
        items = sorted(os.listdir(dir_path))
    except OSError:
        return []
    
    # Filter items
    items = [item for item in items if item not in exclude_dirs and item not in exclude_files]
    
    for idx, item in enumerate(items):
        item_path = os.path.join(dir_path, item)
        is_last = (idx == len(items) - 1)
        connector = "└── " if is_last else "├── "
        
        # Check if item is a directory
        if os.path.isdir(item_path):
            tree_lines.append(f"{prefix}{connector}{item}/")
            new_prefix = prefix + ("    " if is_last else "│   ")
            tree_lines.extend(build_tree(item_path, new_prefix))
        else:
            _, ext = os.path.splitext(item)
            if ext.lower() in allowed_extensions or item in {'.htaccess', '.gitignore'}:
                tree_lines.append(f"{prefix}{connector}{item}")
                
    return tree_lines

def bundle_code():
    print("Bundling project codebase...")
    
    # Write directory structure header
    with open(output_file, 'w', encoding='utf-8') as outfile:
        outfile.write("========================================================================\n")
        outfile.write("PROJECT STRUCTURE & DIRECTORY TREE\n")
        outfile.write("========================================================================\n")
        outfile.write("Healthy Bite QR Menu and Ordering System\n\n")
        
        tree_lines = build_tree(project_dir)
        outfile.write(".\n" + "\n".join(tree_lines) + "\n\n")
        outfile.write("========================================================================\n")
        outfile.write("PROJECT SOURCE FILES & CONTENTS\n")
        outfile.write("========================================================================\n\n")
        
        # Traverse directory and append file contents
        for root, dirs, files in os.walk(project_dir):
            # Modify dirs in-place to exclude unwanted directories
            dirs[:] = [d for d in dirs if d not in exclude_dirs]
            
            for file in sorted(files):
                if file in exclude_files:
                    continue
                
                _, ext = os.path.splitext(file)
                if ext.lower() not in allowed_extensions and file not in {'.htaccess', '.gitignore'}:
                    continue
                
                file_path = os.path.join(root, file)
                rel_path = os.path.relpath(file_path, project_dir)
                
                outfile.write(f"// ====================================================================\n")
                outfile.write(f"// FILE: {rel_path}\n")
                outfile.write(f"// ====================================================================\n\n")
                
                try:
                    with open(file_path, 'r', encoding='utf-8', errors='ignore') as infile:
                        outfile.write(infile.read())
                except Exception as e:
                    outfile.write(f"Error reading file: {str(e)}")
                
                outfile.write("\n\n")
                
    print(f"Success! Bundled codebase written to: {output_file}")

if __name__ == '__main__':
    bundle_code()
