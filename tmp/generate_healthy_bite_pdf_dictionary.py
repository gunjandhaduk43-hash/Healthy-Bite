import os
import sys
from reportlab.lib.pagesizes import letter
from reportlab.lib import colors
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, KeepTogether, HRFlowable
)
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.pdfgen import canvas

class NumberedCanvas(canvas.Canvas):
    def __init__(self, *args, **kwargs):
        super(NumberedCanvas, self).__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_decorations(num_pages)
            super(NumberedCanvas, self).showPage()
        super(NumberedCanvas, self).save()

    def draw_page_decorations(self, page_count):
        self.saveState()
        self.setFont("Helvetica-Bold", 8)
        self.setFillColor(colors.HexColor("#1A365D"))
        
        # Header (pages > 1)
        if self._pageNumber > 1:
            self.drawString(36, 762, "HEALTHY BITE — ADVANCED DATABASE DATA DICTIONARY & SCHEMA DESIGN")
            self.setFont("Helvetica", 8)
            self.drawRightString(576, 762, "SCHEMA V5 (3NF)")
            self.setStrokeColor(colors.HexColor("#cbd5e1"))
            self.setLineWidth(0.5)
            self.line(36, 754, 576, 754)

        # Footer (all pages)
        self.setStrokeColor(colors.HexColor("#cbd5e1"))
        self.setLineWidth(0.5)
        self.line(36, 45, 576, 45)
        
        self.setFont("Helvetica", 8)
        self.setFillColor(colors.HexColor("#64748b"))
        self.drawString(36, 30, "Healthy Bite — Digital Menu & QR Table Ordering System (BCA Sem V)")
        
        page_str = f"Page {self._pageNumber} of {page_count}"
        self.drawRightString(576, 30, page_str)
        self.restoreState()

def create_healthy_bite_pdf(output_filename=r"d:\Healty Bite\docs\Healthy_Bite_Data_Dictionary.pdf"):
    os.makedirs(os.path.dirname(output_filename), exist_ok=True)
    
    doc = SimpleDocTemplate(
        output_filename,
        pagesize=letter,
        leftMargin=36,
        rightMargin=36,
        topMargin=54,
        bottomMargin=54
    )

    styles = getSampleStyleSheet()
    
    title_style = ParagraphStyle(
        'DocTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=22,
        leading=26,
        textColor=colors.HexColor("#1A365D"),
        alignment=TA_LEFT,
        spaceAfter=4
    )
    
    subtitle_style = ParagraphStyle(
        'DocSubTitle',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=11,
        leading=15,
        textColor=colors.HexColor("#2B6CB0"),
        alignment=TA_LEFT,
        spaceAfter=12
    )
    
    meta_style = ParagraphStyle(
        'MetaText',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.5,
        leading=12,
        textColor=colors.HexColor("#2D3748")
    )
    
    section_heading = ParagraphStyle(
        'SectionHeading',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=13,
        leading=16,
        textColor=colors.HexColor("#1A365D"),
        spaceBefore=8,
        spaceAfter=6
    )

    table_title = ParagraphStyle(
        'TableTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=11,
        leading=14,
        textColor=colors.HexColor("#2B6CB0"),
        spaceBefore=0,
        spaceAfter=3
    )

    table_desc = ParagraphStyle(
        'TableDesc',
        parent=styles['Normal'],
        fontName='Helvetica-Oblique',
        fontSize=8.5,
        leading=11.5,
        textColor=colors.HexColor("#4A5568"),
        spaceAfter=5
    )

    cell_header = ParagraphStyle(
        'CellHeader',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=10,
        textColor=colors.white,
        alignment=TA_LEFT
    )

    cell_bold = ParagraphStyle(
        'CellBold',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=7.5,
        leading=9.5,
        textColor=colors.HexColor("#1A202C")
    )

    cell_type = ParagraphStyle(
        'CellType',
        parent=styles['Normal'],
        fontName='Courier-Bold',
        fontSize=7,
        leading=9,
        textColor=colors.HexColor("#2B6CB0")
    )

    cell_text = ParagraphStyle(
        'CellText',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=7.5,
        leading=9.5,
        textColor=colors.HexColor("#2D3748")
    )

    cell_key_pk = ParagraphStyle(
        'CellKeyPK',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=7,
        leading=9,
        textColor=colors.HexColor("#C53030")
    )

    cell_key_fk = ParagraphStyle(
        'CellKeyFK',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=7,
        leading=9,
        textColor=colors.HexColor("#6B46C1")
    )

    cell_key_idx = ParagraphStyle(
        'CellKeyIDX',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=7,
        leading=9,
        textColor=colors.HexColor("#2F855A")
    )

    elements = []

    # Cover / Header
    elements.append(Paragraph("Healthy Bite", title_style))
    elements.append(Paragraph("Complete Relational Database Data Dictionary (16 Tables - 3NF)", subtitle_style))
    elements.append(HRFlowable(width="100%", thickness=1.5, color=colors.HexColor("#1A365D"), spaceAfter=10))

    # Meta Overview
    overview_html = """
    <b>Database Name:</b> <code>healthy_bite</code> &nbsp;&nbsp;|&nbsp;&nbsp; 
    <b>Engine:</b> InnoDB &nbsp;&nbsp;|&nbsp;&nbsp; 
    <b>Charset:</b> utf8mb4_unicode_ci<br/>
    <b>Architecture:</b> Multi-Tenant QR Ordering (3NF) &nbsp;&nbsp;|&nbsp;&nbsp; 
    <b>Total Tables:</b> 16 &nbsp;&nbsp;|&nbsp;&nbsp; 
    <b>Primary Key Standard:</b> BIGINT UNSIGNED AUTO_INCREMENT
    """
    
    overview_table = Table(
        [[Paragraph(overview_html, meta_style)]],
        colWidths=[540]
    )
    overview_table.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#F7FAFC")),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor("#E2E8F0")),
        ('PADDING', (0,0), (-1,-1), 6),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
    ]))
    elements.append(overview_table)
    elements.append(Spacer(1, 10))

    elements.append(Paragraph("System Database Overview", section_heading))
    summary_text = """
    The <b>Healthy Bite</b> relational database architecture comprises 16 normalized tables designed for real-time QR code table ordering, 
    multi-branch tenant management, dynamic digital menus, nutritional/allergen logging, order processing, and payment settlement. 
    Strict foreign key constraints ensure referential integrity across all transactional entities with cascading updates and deletes.
    """
    elements.append(Paragraph(summary_text, cell_text))
    elements.append(Spacer(1, 10))

    # All 16 Tables definition
    tables_data = [
        {
            "name": "1. admin",
            "desc": "System administrator entities with platform-wide management permissions.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique identifier for platform super admin."),
                ("name", "VARCHAR(120)", "No", "None", "NULL", "Full display name of administrator."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Record creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Timestamp of last administrative update.")
            ]
        },
        {
            "name": "2. users",
            "desc": "User authentication accounts linked to managing administrators and restaurant tenants.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique user account ID."),
                ("admin_id", "BIGINT UNSIGNED", "No", "FK (admin.id)", "NULL", "Foreign key referencing assigned managing admin."),
                ("restaurant_id", "BIGINT UNSIGNED", "Yes", "FK (restaurants.id)", "NULL", "Foreign key linking user to restaurant tenant."),
                ("name", "VARCHAR(120)", "No", "None", "NULL", "Full user display name."),
                ("email", "VARCHAR(190)", "No", "UNIQUE INDEX", "NULL", "Unique user email address for login."),
                ("password_hash", "VARCHAR(255)", "No", "None", "NULL", "Bcrypt hashed password credential."),
                ("status", "ENUM('active', 'inactive')", "No", "None", "'active'", "Account activity state."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Account creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Profile update timestamp.")
            ]
        },
        {
            "name": "3. restaurants",
            "desc": "Restaurant tenant establishments, business profiles, contact info, and approval statuses.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique restaurant organization ID."),
                ("owner_user_id", "BIGINT UNSIGNED", "Yes", "FK (users.id)", "NULL", "Foreign key linking restaurant to owner user account."),
                ("name", "VARCHAR(160)", "No", "None", "NULL", "Brand or establishment name."),
                ("email", "VARCHAR(190)", "Yes", "None", "NULL", "Business contact email address."),
                ("phone", "VARCHAR(30)", "Yes", "None", "NULL", "Business telephone number."),
                ("address", "VARCHAR(500)", "Yes", "None", "NULL", "Street address of primary establishment."),
                ("city", "VARCHAR(120)", "Yes", "None", "NULL", "City location."),
                ("state", "VARCHAR(120)", "Yes", "None", "NULL", "State or province location."),
                ("cuisine_type", "VARCHAR(120)", "Yes", "None", "NULL", "Cuisine classification (e.g. Italian, Multi-Cuisine)."),
                ("description", "VARCHAR(1000)", "Yes", "None", "NULL", "Detailed promotional overview text."),
                ("approval_status", "ENUM('pending','approved','suspended')", "No", "None", "'approved'", "Platform verification state."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Organization creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Modification timestamp.")
            ]
        },
        {
            "name": "4. branches",
            "desc": "Specific branch outlets operated under a parent restaurant organization.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique branch outlet ID."),
                ("restaurant_id", "BIGINT UNSIGNED", "No", "FK (restaurants.id)", "NULL", "Foreign key linking branch to parent restaurant."),
                ("name", "VARCHAR(160)", "No", "None", "NULL", "Branch outlet location name (e.g. Downtown Branch)."),
                ("phone", "VARCHAR(30)", "Yes", "None", "NULL", "Branch direct phone number."),
                ("address", "VARCHAR(500)", "Yes", "None", "NULL", "Physical address of outlet."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Branch creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Branch update timestamp.")
            ]
        },
        {
            "name": "5. categories",
            "desc": "Digital menu category groupings (e.g., Starters, Salads, Beverages) per restaurant.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique category ID."),
                ("restaurant_id", "BIGINT UNSIGNED", "No", "FK (restaurants.id)", "NULL", "Foreign key linking category to restaurant."),
                ("name", "VARCHAR(120)", "No", "UNIQUE (rest, name)", "NULL", "Category display name."),
                ("sort_order", "INT UNSIGNED", "No", "None", "0", "Display order sequence index on menu UI."),
                ("is_active", "TINYINT(1)", "No", "None", "1", "Active menu visibility flag (1 = Active, 0 = Hidden)."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Category creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Category update timestamp.")
            ]
        },
        {
            "name": "6. food_items",
            "desc": "Dishes and food items, nutritional metrics, prices, allergen tags, and stock availability.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique food item ID."),
                ("category_id", "BIGINT UNSIGNED", "No", "FK (categories.id)", "NULL", "Foreign key linking dish to menu category."),
                ("name", "VARCHAR(160)", "No", "None", "NULL", "Dish name."),
                ("ingredients", "TEXT", "Yes", "None", "NULL", "Ingredients list."),
                ("base_price", "DECIMAL(10,2)", "No", "None", "NULL", "Base item price."),
                ("image", "VARCHAR(255)", "Yes", "None", "NULL", "Image relative path or URL."),
                ("calories", "INT", "Yes", "None", "NULL", "Calorie count (kcal)."),
                ("protein", "INT", "Yes", "None", "NULL", "Protein content in grams."),
                ("carbs", "INT", "Yes", "None", "NULL", "Carbohydrates in grams."),
                ("fat", "INT", "Yes", "None", "NULL", "Fat content in grams."),
                ("allergens", "VARCHAR(255)", "Yes", "None", "NULL", "Allergen warnings (e.g. Nuts, Gluten)."),
                ("preparation_time", "INT", "Yes", "None", "NULL", "Estimated cooking time in minutes."),
                ("spice_level", "VARCHAR(50)", "Yes", "None", "NULL", "Spice rating (e.g., Mild, Medium, Hot)."),
                ("food_type", "VARCHAR(50)", "No", "None", "'veg'", "Classification (veg, non-veg, vegan, egg)."),
                ("is_available", "BOOLEAN", "No", "None", "1", "In-stock availability toggle."),
                ("is_featured", "BOOLEAN", "No", "None", "0", "Featured special item flag."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Item creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Item update timestamp.")
            ]
        },
        {
            "name": "7. restaurant_tables",
            "desc": "Physical dining tables situated at branch locations with occupancy statuses.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique dining table ID."),
                ("branch_id", "BIGINT UNSIGNED", "No", "FK (branches.id)", "NULL", "Foreign key linking table to branch location."),
                ("table_number", "VARCHAR(80)", "No", "UNIQUE (branch, num)", "NULL", "Table designation code/number (e.g. T-01)."),
                ("status", "ENUM('available','occupied','cleaning','out_of_service')", "No", "None", "'available'", "Real-time table state."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Table creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Table update timestamp.")
            ]
        },
        {
            "name": "8. qr_tokens",
            "desc": "Cryptographic QR session tokens assigned to physical dining tables for instant mobile ordering.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique QR token session ID."),
                ("restaurant_table_id", "BIGINT UNSIGNED", "No", "FK (restaurant_tables.id)", "NULL", "Foreign key linking token to physical table."),
                ("token", "VARCHAR(255)", "No", "UNIQUE INDEX", "NULL", "Cryptographic token string."),
                ("expires_at", "DATETIME", "Yes", "None", "NULL", "Session expiration timestamp."),
                ("is_active", "TINYINT(1)", "No", "None", "1", "Active session validity flag."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Token creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Token update timestamp.")
            ]
        },
        {
            "name": "9. food_variants",
            "desc": "Portion size variants for food items (e.g. Small, Medium, Large) with price adjustments.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique variant ID."),
                ("food_item_id", "BIGINT UNSIGNED", "No", "FK (food_items.id)", "NULL", "Foreign key linking variant to food item."),
                ("name", "VARCHAR(80)", "No", "None", "NULL", "Variant name (e.g. Half, Full, 500ml)."),
                ("price_adjustment", "DECIMAL(10,2)", "No", "None", "0.00", "Price difference relative to base price."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Variant creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Variant update timestamp.")
            ]
        },
        {
            "name": "10. food_customizations",
            "desc": "Custom add-on options and toppings (e.g., Extra Cheese, Low Salt) with pricing.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique customization option ID."),
                ("food_item_id", "BIGINT UNSIGNED", "No", "FK (food_items.id)", "NULL", "Foreign key linking add-on to food item."),
                ("name", "VARCHAR(120)", "No", "None", "NULL", "Add-on name (e.g., Extra Dip, Extra Cheese)."),
                ("price_adjustment", "DECIMAL(10,2)", "No", "None", "0.00", "Additional price for customization."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Add-on creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Add-on update timestamp.")
            ]
        },
        {
            "name": "11. customers",
            "desc": "Customer profiles, guest names, and mobile numbers for digital receipts and order tracking.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique customer profile ID."),
                ("name", "VARCHAR(120)", "No", "None", "NULL", "Guest customer name."),
                ("phone", "VARCHAR(30)", "Yes", "None", "NULL", "Contact telephone number."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Profile creation timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Profile update timestamp.")
            ]
        },
        {
            "name": "12. reviews",
            "desc": "Ratings and text comments submitted by customers for dishes and dining experiences.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique review record ID."),
                ("customer_id", "BIGINT UNSIGNED", "No", "FK (customers.id)", "NULL", "Foreign key referencing reviewing customer."),
                ("restaurant_id", "BIGINT UNSIGNED", "No", "FK (restaurants.id)", "NULL", "Foreign key referencing reviewed restaurant."),
                ("food_item_id", "BIGINT UNSIGNED", "Yes", "FK (food_items.id)", "NULL", "Optional dish reference for item reviews."),
                ("restaurant_table_id", "BIGINT UNSIGNED", "Yes", "FK (restaurant_tables.id)", "NULL", "Optional table location reference."),
                ("rating", "INT", "No", "None", "NULL", "Star rating score (1 to 5)."),
                ("comment", "TEXT", "Yes", "None", "NULL", "Customer feedback text."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Review submission timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Review update timestamp.")
            ]
        },
        {
            "name": "13. orders",
            "desc": "Customer table orders, unique tracking order numbers, status workflow, and bill subtotal/total.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique order transaction ID."),
                ("branch_id", "BIGINT UNSIGNED", "No", "FK (branches.id)", "NULL", "Foreign key referencing branch outlet."),
                ("customer_id", "BIGINT UNSIGNED", "No", "FK (customers.id)", "NULL", "Foreign key referencing customer."),
                ("restaurant_table_id", "BIGINT UNSIGNED", "No", "FK (restaurant_tables.id)", "NULL", "Foreign key referencing dining table."),
                ("order_number", "VARCHAR(32)", "No", "UNIQUE INDEX", "NULL", "Unique tracking order number string."),
                ("status", "ENUM('pending','accepted','preparing','ready','served','completed','cancelled')", "No", "INDEX", "'pending'", "Kitchen order status."),
                ("customer_note", "VARCHAR(500)", "Yes", "None", "NULL", "Special instructions from guest."),
                ("subtotal", "DECIMAL(10,2)", "No", "None", "NULL", "Order subtotal before tax/discounts."),
                ("total_amount", "DECIMAL(10,2)", "No", "None", "NULL", "Final total bill payable."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Order placement timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Order status update timestamp.")
            ]
        },
        {
            "name": "14. order_items",
            "desc": "Individual line items associated with an order, item snapshots, quantities, and line totals.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique order line item ID."),
                ("order_id", "BIGINT UNSIGNED", "No", "FK (orders.id)", "NULL", "Foreign key referencing parent order."),
                ("food_item_id", "BIGINT UNSIGNED", "No", "FK (food_items.id)", "NULL", "Foreign key linking item to menu."),
                ("food_variant_id", "BIGINT UNSIGNED", "Yes", "FK (food_variants.id)", "NULL", "Selected variant portion size reference."),
                ("item_name", "VARCHAR(160)", "No", "None", "NULL", "Historical snapshot of item name."),
                ("unit_price", "DECIMAL(10,2)", "No", "None", "NULL", "Unit price charged."),
                ("quantity", "INT", "No", "None", "NULL", "Item quantity ordered."),
                ("line_total", "DECIMAL(10,2)", "No", "None", "NULL", "Calculated line total (unit_price * quantity)."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Line item creation timestamp.")
            ]
        },
        {
            "name": "15. order_item_customizations",
            "desc": "Pivot mapping tracking add-ons and customizations chosen for each ordered line item.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique line item customization ID."),
                ("order_item_id", "BIGINT UNSIGNED", "No", "FK (order_items.id)", "NULL", "Foreign key linking customization to order line item."),
                ("food_customization_id", "BIGINT UNSIGNED", "No", "FK (food_customizations.id)", "NULL", "Foreign key referencing selected add-on."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Selection timestamp.")
            ]
        },
        {
            "name": "16. payments",
            "desc": "Payment transaction records, settlement channels (cash, UPI, card), and payment verification state.",
            "columns": [
                ("id", "BIGINT UNSIGNED", "No", "PRIMARY (AI)", "NULL", "Unique payment transaction ID."),
                ("order_id", "BIGINT UNSIGNED", "No", "FK (orders.id)", "NULL", "Foreign key linking payment to settled order."),
                ("amount", "DECIMAL(10,2)", "No", "None", "NULL", "Paid monetary amount."),
                ("method", "ENUM('cash','upi','card')", "No", "None", "NULL", "Payment channel method."),
                ("status", "ENUM('pending','completed','failed')", "No", "None", "'pending'", "Payment status."),
                ("created_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Payment timestamp."),
                ("updated_at", "TIMESTAMP", "No", "None", "CURRENT_TIMESTAMP", "Payment update timestamp.")
            ]
        }
    ]

    col_widths = [85, 95, 38, 85, 75, 162]

    for idx, table_info in enumerate(tables_data):
        table_block = []
        
        table_block.append(Paragraph(table_info["name"], table_title))
        table_block.append(Paragraph(table_info["desc"], table_desc))
        
        headers = [
            Paragraph("Attribute Name", cell_header),
            Paragraph("Data Type", cell_header),
            Paragraph("Null", cell_header),
            Paragraph("Key / Constraint", cell_header),
            Paragraph("Default Value", cell_header),
            Paragraph("Description", cell_header)
        ]
        grid_rows = [headers]

        for col in table_info["columns"]:
            name, dtype, nullable, key, default_val, desc = col
            
            if "PRIMARY" in key:
                key_para = Paragraph(key, cell_key_pk)
            elif "FK" in key:
                key_para = Paragraph(key, cell_key_fk)
            elif "INDEX" in key or "UNIQUE" in key:
                key_para = Paragraph(key, cell_key_idx)
            else:
                key_para = Paragraph(key, cell_text)

            row = [
                Paragraph(f"<b>{name}</b>", cell_bold),
                Paragraph(dtype, cell_type),
                Paragraph(nullable, cell_text),
                key_para,
                Paragraph(f"<code>{default_val}</code>", cell_text),
                Paragraph(desc, cell_text)
            ]
            grid_rows.append(row)

        grid_table = Table(grid_rows, colWidths=col_widths, repeatRows=1)
        grid_table.setStyle(TableStyle([
            ('BACKGROUND', (0,0), (-1,0), colors.HexColor("#1A365D")),
            ('ALIGN', (0,0), (-1,-1), 'LEFT'),
            ('VALIGN', (0,0), (-1,-1), 'TOP'),
            ('GRID', (0,0), (-1,-1), 0.5, colors.HexColor("#cbd5e1")),
            ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, colors.HexColor("#F7FAFC")]),
            ('TOPPADDING', (0,0), (-1,-1), 3),
            ('BOTTOMPADDING', (0,0), (-1,-1), 3),
            ('LEFTPADDING', (0,0), (-1,-1), 4),
            ('RIGHTPADDING', (0,0), (-1,-1), 4),
        ]))
        
        table_block.append(grid_table)
        table_block.append(Spacer(1, 10))

        if len(table_info["columns"]) > 10:
            elements.extend(table_block)
        else:
            elements.append(KeepTogether(table_block))

    doc.build(elements, canvasmaker=NumberedCanvas)
    print(f"Healthy Bite PDF Data Dictionary successfully generated: {os.path.abspath(output_filename)}")

if __name__ == "__main__":
    create_healthy_bite_pdf()
