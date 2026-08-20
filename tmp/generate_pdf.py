import re
import base64
import json
import zlib
import urllib.request
import os
import html
from reportlab.lib.pagesizes import letter
from reportlab.platypus import BaseDocTemplate, PageTemplate, Frame, Paragraph, Spacer, PageBreak, NextPageTemplate, Table, TableStyle
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.graphics.shapes import Drawing, Rect
from reportlab.lib import colors
from reportlab.pdfgen import canvas
from PIL import Image as PILImage
from reportlab.platypus import Image as RLImage

# Define color palette
PRIMARY = colors.HexColor("#1A365D")   # Deep navy blue
SECONDARY = colors.HexColor("#2B6CB0") # Slate blue
ACCENT = colors.HexColor("#D69E2E")    # Muted gold/amber
DARK_TEXT = colors.HexColor("#2D3748") # Charcoal
LIGHT_BG = colors.HexColor("#F7FAFC")  # Off-white
BORDER_COLOR = colors.HexColor("#E2E8F0") # Light grey border

# --- NumberedCanvas for professional header/footer ---
class NumberedCanvas(canvas.Canvas):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_number(num_pages)
            super().showPage()
        super().save()

    def draw_page_number(self, page_count):
        self.saveState()
        self.setFont("Helvetica", 9)
        self.setFillColor(DARK_TEXT)
        
        # Suppress headers/footers on page 1 (cover page)
        if self._pageNumber > 1:
            width, height = self._pagesize
            
            # Header
            self.drawString(54, height - 42, "Healthy Bite — Advanced Database Design & ERD")
            self.setStrokeColor(BORDER_COLOR)
            self.setLineWidth(0.5)
            self.line(54, height - 48, width - 54, height - 48)
            
            # Footer
            self.line(54, 54, width - 54, 54)
            page_text = f"Page {self._pageNumber} of {page_count}"
            self.drawRightString(width - 54, 38, page_text)
            self.drawString(54, 38, "Confidential — BCA Sem V Project Submission")
            
        self.restoreState()

# --- Markdown Text Utilities ---
def clean_md(text):
    # Escape HTML entities to prevent ReportLab XML crashes
    text = html.escape(text)
    
    # Unescape some entities that ReportLab Paragraph supports
    text = text.replace("&lt;b&gt;", "<b>").replace("&lt;/b&gt;", "</b>")
    text = text.replace("&lt;i&gt;", "<i>").replace("&lt;/i&gt;", "</i>")
    
    # Bold: **bold** -> <b>bold</b>
    text = re.sub(r'\*\*(.*?)\*\*', r'<b>\1</b>', text)
    # Italic: *italic* -> <i>italic</i>
    text = re.sub(r'\*(.*?)\*', r'<i>\1</i>', text)
    # Code: `code` -> <font face="Courier" color="#C53030"><b>\1</b></font>
    text = re.sub(r'`(.*?)`', r'<font face="Courier" size="8.5" color="#C53030"><b>\1</b></font>', text)
    
    return text

def gen_pako_link(graph_markdown: str):
    j_graph = {"code": graph_markdown, "mermaid": {"theme": "default"}}
    byte_str = json.dumps(j_graph).encode('utf-8')
    
    # Compress using standard zlib deflate (windowBits = 15)
    compress = zlib.compressobj(9, zlib.DEFLATED, 15, 8, zlib.Z_DEFAULT_STRATEGY)
    compressed_data = compress.compress(byte_str) + compress.flush()
    
    b64_encoded = base64.b64encode(compressed_data).decode('ascii')
    url_safe_b64 = b64_encoded.replace('+', '-').replace('/', '_').rstrip('=')
    
    return f"https://mermaid.ink/img/pako:{url_safe_b64}"

def download_mermaid_image(md_path, img_path):
    print("Reading markdown file...")
    with open(md_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    match = re.search(r'```mermaid\s*(.*?)\s*```', content, re.DOTALL)
    if not match:
        raise ValueError("Mermaid block not found in the markdown file!")
        
    mermaid_code = match.group(1).strip()
    url = gen_pako_link(mermaid_code)
    
    print("Downloading rendered ER diagram from mermaid.ink...")
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'})
    with urllib.request.urlopen(req) as response:
        with open(img_path, 'wb') as out_file:
            out_file.write(response.read())
    print(f"ER diagram downloaded successfully. Saved to {img_path}")

def generate_pdf(md_path, img_path, pdf_path):
    # Initialize Document
    # Page size definitions
    portrait_size = letter  # 612 x 792
    landscape_size = (792, 612)
    
    # Frames (Left, Bottom, Width, Height)
    frame_portrait = Frame(54, 72, 504, 648, id='F_portrait')
    frame_landscape = Frame(54, 72, 684, 468, id='F_landscape')
    
    # Page Templates
    temp_portrait = PageTemplate(id='Portrait', frames=frame_portrait, pagesize=portrait_size)
    temp_landscape = PageTemplate(id='Landscape', frames=frame_landscape, pagesize=landscape_size)
    
    doc = BaseDocTemplate(pdf_path, pageTemplates=[temp_portrait, temp_landscape])
    
    # Setup Stylesheet
    styles = getSampleStyleSheet()
    styles['Normal'].textColor = DARK_TEXT
    
    style_body = ParagraphStyle('CustomBody', parent=styles['Normal'], fontSize=9.5, leading=14, spaceAfter=8)
    style_bullet = ParagraphStyle('CustomBullet', parent=style_body, leftIndent=15, firstLineIndent=-8, spaceAfter=4)
    style_num_list = ParagraphStyle('CustomNumList', parent=style_body, leftIndent=15, firstLineIndent=-10, spaceAfter=4)
    
    style_h1 = ParagraphStyle('CustomH1', parent=styles['Heading1'], fontName='Helvetica-Bold', fontSize=18, leading=22, textColor=PRIMARY, spaceBefore=18, spaceAfter=10, keepWithNext=True)
    style_h2 = ParagraphStyle('CustomH2', parent=styles['Heading2'], fontName='Helvetica-Bold', fontSize=13, leading=16, textColor=SECONDARY, spaceBefore=14, spaceAfter=8, keepWithNext=True)
    style_h3 = ParagraphStyle('CustomH3', parent=styles['Heading3'], fontName='Helvetica-Bold', fontSize=10.5, leading=13, textColor=DARK_TEXT, spaceBefore=10, spaceAfter=6, keepWithNext=True)
    
    style_cover_title = ParagraphStyle('CoverTitle', fontName='Helvetica-Bold', fontSize=32, leading=38, textColor=PRIMARY, alignment=0)
    style_cover_subtitle = ParagraphStyle('CoverSubtitle', fontName='Helvetica', fontSize=16, leading=22, textColor=SECONDARY, alignment=0)
    style_cover_desc = ParagraphStyle('CoverDesc', fontName='Helvetica', fontSize=10.5, leading=16, textColor=DARK_TEXT, alignment=4)
    style_meta_label = ParagraphStyle('MetaLabel', fontName='Helvetica-Bold', fontSize=9.5, leading=14, textColor=SECONDARY)
    style_meta_value = ParagraphStyle('MetaValue', fontName='Helvetica', fontSize=9.5, leading=14, textColor=DARK_TEXT)
    
    style_table_header = ParagraphStyle('TableHeader', fontName='Helvetica-Bold', fontSize=8.5, leading=11, textColor=colors.white)
    style_table_cell = ParagraphStyle('TableCell', fontName='Helvetica', fontSize=7.5, leading=10, textColor=DARK_TEXT)

    story = []
    
    # --- 1. COVER PAGE ---
    story.append(Spacer(1, 40))
    story.append(Paragraph("HEALTHY BITE", style_cover_title))
    story.append(Spacer(1, 10))
    story.append(Paragraph("Advanced Entity Relationship Diagram (ERD)<br/>&amp; Database Schema Design", style_cover_subtitle))
    story.append(Spacer(1, 20))
    
    # Divider line
    div_drawing = Drawing(504, 4)
    div_drawing.add(Rect(0, 0, 504, 4, fillColor=ACCENT, strokeColor=None))
    story.append(div_drawing)
    story.append(Spacer(1, 25))
    
    story.append(Paragraph(
        "This document details the enterprise-grade, multi-tenant database design for the "
        "<b>Healthy Bite</b> digital menu and table-ordering platform. Structured in Third Normal Form (3NF), "
        "it preserves strict referential integrity under ACID compliance and supports multi-branch scaling, "
        "cryptographic table ordering sessions, real-time kitchen routing, and predictive inventory logging.",
        style_cover_desc
    ))
    story.append(Spacer(1, 140))
    
    # Cover page metadata block
    metadata_data = [
        [Paragraph("Project Title:", style_meta_label), Paragraph("Healthy Bite QR-based Table Ordering MVP", style_meta_value)],
        [Paragraph("Academic Context:", style_meta_label), Paragraph("BCA Semester V Database Design Submission", style_meta_value)],
        [Paragraph("System Architecture:", style_meta_label), Paragraph("Custom PHP MVC MVC Foundation / MySQL 8.0+", style_meta_value)],
        [Paragraph("Target Environment:", style_meta_label), Paragraph("XAMPP Local Server Integration (Apache &amp; InnoDB Engine)", style_meta_value)],
        [Paragraph("Version / Date:", style_meta_label), Paragraph("v1.0.0 / July 13, 2026", style_meta_value)]
    ]
    meta_table = Table(metadata_data, colWidths=[120, 384])
    meta_table.setStyle(TableStyle([
        ('VALIGN', (0, 0), (-1, -1), 'TOP'),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 6),
        ('GRID', (0, 0), (-1, -1), 0, colors.white),
    ]))
    story.append(meta_table)
    story.append(PageBreak())
    
    # --- 2. STATE MACHINE PARSER FOR DOCUMENT CONTENT ---
    with open(md_path, 'r', encoding='utf-8') as f:
        lines = f.readlines()
        
    in_mermaid = False
    in_table = False
    table_lines = []
    
    def parse_and_append_table(tbl_lines):
        rows = []
        for line in tbl_lines:
            # Strip trailing/leading spaces and split by '|'
            parts = [p.strip() for p in line.split('|')[1:-1]]
            # Skip divider rows like |---|---|
            if all(re.match(r'^:?-+:?$', p) for p in parts if p):
                continue
            rows.append(parts)
            
        if not rows:
            return
            
        table_data = []
        for r_idx, row in enumerate(rows):
            formatted_row = []
            for cell in row:
                cleaned = clean_md(cell)
                if r_idx == 0:
                    p = Paragraph(cleaned, style_table_header)
                else:
                    p = Paragraph(cleaned, style_table_cell)
                formatted_row.append(p)
            table_data.append(formatted_row)
            
        num_cols = len(table_data[0])
        # Determine smart column widths
        if num_cols == 5 and "Parent" in rows[0][0]: # Cardinality table
            col_widths = [115, 115, 74, 100, 100]
        elif num_cols == 5 and "Foreign" in rows[0][0]: # FK mapping table
            col_widths = [115, 110, 149, 65, 65]
        elif num_cols == 2: # Documentation map
            col_widths = [150, 354]
        else:
            col_widths = [504 / num_cols] * num_cols
            
        t = Table(table_data, colWidths=col_widths)
        t_style = [
            ('BACKGROUND', (0, 0), (-1, 0), PRIMARY),
            ('ALIGN', (0, 0), (-1, -1), 'LEFT'),
            ('VALIGN', (0, 0), (-1, -1), 'TOP'),
            ('BOTTOMPADDING', (0, 0), (-1, -1), 4),
            ('TOPPADDING', (0, 0), (-1, -1), 4),
            ('GRID', (0, 0), (-1, -1), 0.5, BORDER_COLOR),
        ]
        
        # Alternating rows
        for i in range(1, len(table_data)):
            if i % 2 == 0:
                t_style.append(('BACKGROUND', (0, i), (-1, i), LIGHT_BG))
            else:
                t_style.append(('BACKGROUND', (0, i), (-1, i), colors.white))
                
        t.setStyle(TableStyle(t_style))
        story.append(t)
        story.append(Spacer(1, 10))

    # Skip document title and main header to avoid duplicates since we have a cover page
    skip_title_headers = True
    
    for line in lines:
        line_stripped = line.strip()
        
        # Mermaid block handling
        if line_stripped.startswith("```mermaid"):
            in_mermaid = True
            continue
        if in_mermaid:
            if line_stripped.startswith("```"):
                in_mermaid = False
                
                # We reached the end of the mermaid block. Insert ER diagram on a landscape page!
                story.append(NextPageTemplate('Landscape'))
                story.append(PageBreak())
                
                # Compute aspect ratio dynamically using Pillow
                with PILImage.open(img_path) as im:
                    orig_w, orig_h = im.size
                
                # Landscape printable area is 684 x 468.
                scale = min(684 / orig_w, 420 / orig_h)
                w = orig_w * scale
                h = orig_h * scale
                
                story.append(Spacer(1, 10))
                story.append(Paragraph("<b>Entity Relationship Diagram (ERD) — Main Schema</b>", style_h2))
                story.append(Spacer(1, 10))
                story.append(RLImage(img_path, width=w, height=h))
                
                # Switch back to portrait for subsequent pages
                story.append(NextPageTemplate('Portrait'))
                story.append(PageBreak())
            continue
            
        # Table block handling
        if line_stripped.startswith("|"):
            in_table = True
            table_lines.append(line_stripped)
            continue
        else:
            if in_table:
                parse_and_append_table(table_lines)
                table_lines = []
                in_table = False
                
        if line_stripped == "":
            continue
            
        # Skip top title header
        if skip_title_headers:
            if line_stripped.startswith("#") and "Entity Relationship" in line_stripped:
                continue
            if line_stripped.startswith("---") or "This document details" in line_stripped:
                continue
            # End of headers skip after first major content heading
            if line_stripped.startswith("## "):
                skip_title_headers = False
                
        # Heading detection
        if line_stripped.startswith("#"):
            level = len(line_stripped) - len(line_stripped.lstrip('#'))
            title_text = line_stripped.lstrip('#').strip()
            cleaned_title = clean_md(title_text)
            
            if level == 1:
                story.append(Paragraph(cleaned_title, style_h1))
            elif level == 2:
                # Add page break before major section headings for clean layouts
                if not cleaned_title.startswith("1."):
                    story.append(PageBreak())
                story.append(Paragraph(cleaned_title, style_h2))
            else:
                story.append(Paragraph(cleaned_title, style_h3))
            continue
            
        # List block handling
        if line_stripped.startswith("- ") or line_stripped.startswith("* "):
            item_text = line_stripped[2:].strip()
            cleaned_item = clean_md(item_text)
            story.append(Paragraph(f"&bull; {cleaned_item}", style_bullet))
        elif re.match(r'^\d+\.\s', line_stripped):
            match = re.match(r'^(\d+)\.\s(.*)', line_stripped)
            num = match.group(1)
            item_text = match.group(2).strip()
            cleaned_item = clean_md(item_text)
            story.append(Paragraph(f"{num}. {cleaned_item}", style_num_list))
        elif line_stripped == "---" or line_stripped == "***":
            story.append(Spacer(1, 10))
        else:
            cleaned_p = clean_md(line_stripped)
            story.append(Paragraph(cleaned_p, style_body))
            
    # Build Document
    print(f"Building PDF at {pdf_path}...")
    doc.build(story, canvasmaker=NumberedCanvas)
    print("PDF build successful!")

if __name__ == "__main__":
    md_file = r"d:\Healty Bite\docs\ER_DIAGRAM.md"
    img_file = r"d:\Healty Bite\tmp\er_diagram.png"
    pdf_file = r"d:\Healty Bite\docs\ER_DIAGRAM.pdf"
    
    # 1. Download image
    download_mermaid_image(md_file, img_file)
    
    # 2. Build PDF
    generate_pdf(md_file, img_file, pdf_file)
