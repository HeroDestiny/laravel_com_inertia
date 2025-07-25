#!/usr/bin/env python3
"""
UML Image Generator
Converts PlantUML (.puml) files to various image formats using PlantUML server API
Supported formats: png, svg, eps, pdf, txt, utxt
"""

import os
import sys
import base64
import zlib
import urllib.request
import urllib.parse
from pathlib import Path

# PlantUML server configurations
PLANTUML_SERVERS = [
    "http://www.plantuml.com/plantuml",
    "https://www.plantuml.com/plantuml",
    "http://plantuml.com/plantuml"
]

# Supported output formats
SUPPORTED_FORMATS = {
    'png': {'ext': '.png', 'desc': 'PNG image (default)'},
    'svg': {'ext': '.svg', 'desc': 'SVG vector image'},
    'eps': {'ext': '.eps', 'desc': 'EPS vector image'},
    'pdf': {'ext': '.pdf', 'desc': 'PDF document'},
    'txt': {'ext': '.txt', 'desc': 'ASCII art text'},
    'utxt': {'ext': '.utxt', 'desc': 'Unicode ASCII art text'}
}


def encode_plantuml_url(plantuml_text):
    def deflate(data):
        compressor = zlib.compressobj(level=9, wbits=-15)
        return compressor.compress(data) + compressor.flush()

    def encode_6bit(b):
        if b < 10:
            return chr(48 + b)
        b -= 10
        if b < 26:
            return chr(65 + b)
        b -= 26
        if b < 26:
            return chr(97 + b)
        b -= 26
        if b == 0:
            return '-'
        if b == 1:
            return '_'
        return '?'

    def encode_bytes(data):
        res = ""
        i = 0
        while i < len(data):
            b1 = data[i]
            b2 = data[i+1] if i+1 < len(data) else 0
            b3 = data[i+2] if i+2 < len(data) else 0
            c1 = b1 >> 2
            c2 = ((b1 & 0x3) << 4) | (b2 >> 4)
            c3 = ((b2 & 0xF) << 2) | (b3 >> 6)
            c4 = b3 & 0x3F
            res += encode_6bit(c1 & 0x3F)
            res += encode_6bit(c2 & 0x3F)
            res += encode_6bit(c3 & 0x3F)
            res += encode_6bit(c4 & 0x3F)
            i += 3
        return res

    data = plantuml_text.strip().encode('utf-8')
    compressed = deflate(data)
    return encode_bytes(compressed)


def download_uml_image(puml_content, output_path, image_format='png'):
    """
    Download UML diagram image from PlantUML server
    """
    if image_format not in SUPPORTED_FORMATS:
        print(f"ERROR: Unsupported format '{image_format}'")
        print(f"Supported formats: {', '.join(SUPPORTED_FORMATS.keys())}")
        return False

    encoded_content = encode_plantuml_url(puml_content)

    for server_url in PLANTUML_SERVERS:
        try:
            # Construct URL
            image_url = f"{server_url}/{image_format}/{encoded_content}"

            print(f"Trying server: {server_url}")
            print(
                f"Format: {image_format.upper()} ({SUPPORTED_FORMATS[image_format]['desc']})")
            print(f"Request URL: {image_url[:100]}...")

            # Download image
            with urllib.request.urlopen(image_url, timeout=30) as response:
                if response.status == 200:
                    with open(output_path, 'wb') as f:
                        f.write(response.read())

                    print(f"Image saved: {output_path}")
                    print(f"File size: {os.path.getsize(output_path)} bytes")
                    return True
                else:
                    print(f"Server returned status: {response.status}")

        except Exception as e:
            print(f"Failed with {server_url}: {str(e)}")
            continue

    return False


def main():
    """Main function"""
    print("UML Image Generator")
    print("=" * 50)

    # Parse command line arguments
    import argparse
    parser = argparse.ArgumentParser(
        description='Generate UML diagrams from PlantUML files')
    parser.add_argument('--format', '-f',
                        choices=list(SUPPORTED_FORMATS.keys()),
                        default='png',
                        help='Output format (default: png)')
    parser.add_argument('--all-formats', '-a',
                        action='store_true',
                        help='Generate all supported formats')
    parser.add_argument('--list-formats', '-l',
                        action='store_true',
                        help='List all supported formats')

    args = parser.parse_args()

    # List formats and exit
    if args.list_formats:
        print("Supported output formats:")
        for fmt, info in SUPPORTED_FORMATS.items():
            print(f"  {fmt:6} - {info['desc']}")
        return 0

    # Define paths
    script_dir = Path(__file__).parent
    project_root = script_dir.parent
    puml_file = project_root / 'storage' / 'uml' / 'domain-models.puml'

    # Check if PUML file exists
    if not puml_file.exists():
        print(f"ERROR: PlantUML file not found: {puml_file}")
        print("HINT: Run 'php artisan generate:uml' first to create the .puml file")
        return 1

    # Read PUML content
    try:
        with open(puml_file, 'r', encoding='utf-8') as f:
            puml_content = f.read()

        print(f"Reading PUML file: {puml_file}")
        print(f"Content length: {len(puml_content)} characters")

    except Exception as e:
        print(f"ERROR: Error reading PUML file: {e}")
        return 1

    # Determine formats to generate
    if args.all_formats:
        formats_to_generate = list(SUPPORTED_FORMATS.keys())
        print(f"\nGenerating all formats: {', '.join(formats_to_generate)}")
    else:
        formats_to_generate = [args.format]
        print(f"\nGenerating format: {args.format}")

    success_count = 0

    for fmt in formats_to_generate:
        ext = SUPPORTED_FORMATS[fmt]['ext']
        output_file = project_root / 'storage' / 'uml' / f'domain-models{ext}'

        # Ensure output directory exists
        output_file.parent.mkdir(parents=True, exist_ok=True)

        print(f"\n--- Generating {fmt.upper()} ---")
        success = download_uml_image(puml_content, output_file, fmt)

        if success:
            success_count += 1
            print(f"SUCCESS: {fmt.upper()} generated")
            print(f"   Location: {output_file}")

            # Verify file was created and has content
            if output_file.exists() and os.path.getsize(output_file) > 0:
                print(f"   Verification: OK")
            else:
                print(f"   WARNING: File may be corrupted")
        else:
            print(f"ERROR: Failed to generate {fmt.upper()}")

    # Final summary
    print(f"\n{'='*50}")
    print(
        f"Generated {success_count}/{len(formats_to_generate)} format(s) successfully")

    if success_count > 0:
        print(
            f"Files location: {project_root / 'storage' / 'uml' / 'domain-models.*'}")
        print(f"Open with: code {project_root / 'storage' / 'uml'}/")
        return 0
    else:
        print(f"Troubleshooting:")
        print(f"   • Check internet connection")
        print(f"   • Verify PUML syntax: {puml_file}")
        print(f"   • Try online: https://www.plantuml.com/plantuml/uml/")
        return 1


if __name__ == "__main__":
    try:
        exit_code = main()
        sys.exit(exit_code)
    except KeyboardInterrupt:
        print("\nInterrupted by user")
        sys.exit(130)
    except Exception as e:
        print(f"\nUnexpected error: {e}")
        sys.exit(1)
