import re
import base64
import json
import zlib
import urllib.request
import os

def gen_pako_link(graph_markdown: str):
    j_graph = {"code": graph_markdown, "mermaid": {"theme": "default"}}
    byte_str = json.dumps(j_graph).encode('utf-8')
    
    # Try standard zlib deflate (15) which is compatible with pako
    compress = zlib.compressobj(9, zlib.DEFLATED, 15, 8, zlib.Z_DEFAULT_STRATEGY)
    compressed_data = compress.compress(byte_str) + compress.flush()
    
    b64_encoded = base64.b64encode(compressed_data).decode('ascii')
    url_safe_b64 = b64_encoded.replace('+', '-').replace('/', '_').rstrip('=')
    
    return f"https://mermaid.ink/img/pako:{url_safe_b64}"

def test():
    md_path = r"d:\Healty Bite\docs\ER_DIAGRAM.md"
    with open(md_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Extract mermaid block
    match = re.search(r'```mermaid\s*(.*?)\s*```', content, re.DOTALL)
    if not match:
        print("Mermaid block not found!")
        return
        
    mermaid_code = match.group(1).strip()
    
    url = gen_pako_link(mermaid_code)
    print("Compressed Mermaid Ink URL:", url[:150] + "...")
    
    os.makedirs("tmp", exist_ok=True)
    img_path = "tmp/er_diagram.png"
    
    print("Downloading image...")
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'})
        with urllib.request.urlopen(req) as response:
            with open(img_path, 'wb') as out_file:
                out_file.write(response.read())
        print(f"Success! Saved image to {img_path}, size: {os.path.getsize(img_path)} bytes")
    except Exception as e:
        print("Error downloading:", e)

if __name__ == "__main__":
    test()
