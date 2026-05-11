import os
import re

directories_to_scan = [
    'resources/views/frontend',
    'resources/views/order',
    'resources/views/meeting-room',
    'resources/views/podcast-room',
    'resources/views/services',
]

def generate_seo_content(filepath):
    # Extract filename without extension
    basename = os.path.basename(filepath).replace('.blade.php', '')
    
    # Format name
    name_parts = basename.split('-')
    formatted_name = ' '.join(word.capitalize() for word in name_parts)
    
    if basename == 'index' or basename == 'dashboard':
        formatted_name = 'Beranda'
    elif basename == 'show':
        # get parent dir
        parent = os.path.basename(os.path.dirname(filepath))
        formatted_name = parent.replace('-', ' ').capitalize() + ' Detail'
        
    title = f"{formatted_name} | Lawgika - Konsultan Legal & Bisnis"
    desc = f"Layanan {formatted_name} terbaik dan terpercaya di Indonesia oleh Lawgika.co.id. Proses cepat, legal, dan aman untuk kebutuhan bisnis Anda."
    keywords = f"{formatted_name}, Jasa {formatted_name}, Konsultan {formatted_name}, Lawgika, Legalitas Usaha, Jasa Hukum Bisnis"
    
    # Specific tweaks for common terms
    if 'pt' in basename.lower():
        title = f"Jasa Pendirian {formatted_name} | Lawgika"
        desc = f"Butuh jasa Pendirian {formatted_name}? Lawgika siap membantu pengurusan legalitas perusahaan Anda dengan proses cepat dan harga transparan."
    elif 'virtual-office' in basename.lower():
        title = "Sewa Virtual Office Murah & Prestisius | Lawgika"
        desc = "Sewa Virtual Office dengan alamat bergengsi untuk pendirian perusahaan Anda. Fasilitas lengkap, surat domisili, dan resepsionis."
        keywords = "Virtual office jakarta, sewa virtual office, alamat bisnis, domisili perusahaan, Lawgika"
    elif 'meeting-room' in basename.lower():
        title = "Sewa Meeting Room Profesional & Nyaman | Lawgika"
        desc = "Sewa ruang meeting untuk keperluan rapat bisnis, negosiasi, atau presentasi. Dilengkapi dengan fasilitas modern dan Smart TV."
    elif 'podcast' in basename.lower():
        title = "Sewa Ruang Podcast Studio | Lawgika"
        desc = "Sewa studio podcast profesional dengan peralatan lengkap, soundproofing, dan operator. Mulai produksi konten Anda sekarang."
    elif 'pajak' in basename.lower() or 'pembukuan' in basename.lower() or 'spt' in basename.lower() or 'pkp' in basename.lower() or 'lkpm' in basename.lower():
        title = f"Jasa {formatted_name} & Konsultan Pajak | Lawgika"
        desc = f"Layanan {formatted_name} profesional untuk mengurus administrasi dan kepatuhan pajak perusahaan Anda dengan tepat waktu."
        keywords = f"{formatted_name}, Konsultan Pajak, Jasa Pembukuan, Lapor Pajak, Tax Accounting Lawgika"
    elif 'karir' in basename.lower():
        title = "Karir & Lowongan Kerja | Lawgika"
        desc = "Bergabunglah dengan Lawgika. Kami mencari talenta terbaik untuk berkembang bersama di ekosistem hukum dan bisnis digital."
    
    seo_block = f"""
@section('title', '{title}')
@section('meta_description', '{desc}')
@section('meta_keywords', '{keywords}')
"""
    return seo_block

files_processed = 0

for directory in directories_to_scan:
    if not os.path.exists(directory):
        continue
    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith('.blade.php'):
                filepath = os.path.join(root, file)
                with open(filepath, 'r') as f:
                    content = f.read()
                
                # Check if it extends layout.app
                if "@extends('layout.app')" in content:
                    # Check if already has SEO
                    if "@section('title'" in content:
                        continue # Skip
                    
                    seo_content = generate_seo_content(filepath)
                    
                    # Insert right after extends
                    new_content = content.replace("@extends('layout.app')", f"@extends('layout.app'){seo_content}", 1)
                    
                    with open(filepath, 'w') as f:
                        f.write(new_content)
                    
                    files_processed += 1
                    print(f"Added SEO to {filepath}")

print(f"Total files updated: {files_processed}")
