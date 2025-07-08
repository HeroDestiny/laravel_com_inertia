#!/usr/bin/env python3

import base64
import urllib.parse
import urllib.request
import sys
import os
import zlib
import string

def plantuml_encode_legacy(plantuml_text):
    """
    Método de codificação legado do PlantUML (para compatibilidade)
    """
    # Tabela de caracteres do PlantUML
    encode_chars = string.ascii_uppercase + string.ascii_lowercase + string.digits + '-_'
    
    def encode6bit(b):
        if b < 26:
            return chr(ord('A') + b)
        b -= 26
        if b < 26:
            return chr(ord('a') + b)
        b -= 26
        if b < 10:
            return chr(ord('0') + b)
        b -= 10
        if b == 0:
            return '-'
        if b == 1:
            return '_'
        return '?'
    
    def append3bytes(b1, b2, b3):
        c1 = b1 >> 2
        c2 = ((b1 & 0x3) << 4) | (b2 >> 4)
        c3 = ((b2 & 0xF) << 2) | (b3 >> 6)
        c4 = b3 & 0x3F
        return encode6bit(c1 & 0x3F) + encode6bit(c2 & 0x3F) + encode6bit(c3 & 0x3F) + encode6bit(c4 & 0x3F)
    
    # Converte texto para bytes
    text_bytes = plantuml_text.encode('utf-8')
    
    # Comprime
    compressed = zlib.compress(text_bytes, 9)[2:-4]
    
    # Codifica usando algoritmo PlantUML
    result = ""
    for i in range(0, len(compressed), 3):
        if i + 2 < len(compressed):
            result += append3bytes(compressed[i], compressed[i + 1], compressed[i + 2])
        elif i + 1 < len(compressed):
            result += append3bytes(compressed[i], compressed[i + 1], 0)
        else:
            result += append3bytes(compressed[i], 0, 0)
    
    return result

def plantuml_encode_modern(plantuml_text):
    """
    Método moderno de codificação DEFLATE
    """
    # Normaliza o texto
    plantuml_text = plantuml_text.strip()
    
    # Converte para UTF-8
    utf8_bytes = plantuml_text.encode('utf-8')
    
    # Comprime usando DEFLATE
    compressed = zlib.compress(utf8_bytes, 9)[2:-4]
    
    # Base64 com caracteres URL-safe
    b64 = base64.b64encode(compressed).decode('ascii')
    url_safe = b64.replace('+', '-').replace('/', '_').rstrip('=')
    
    return url_safe

def test_url(url, output_file):
    """
    Testa uma URL e verifica se gera uma imagem PNG válida
    """
    try:
        urllib.request.urlretrieve(url, output_file)
        
        # Verifica se é PNG válido
        with open(output_file, 'rb') as f:
            header = f.read(8)
            if header.startswith(b'\x89PNG\r\n\x1a\n'):
                return True
        return False
    except:
        return False

def generate_uml_image_robust(puml_file, output_file):
    """
    Gera imagem UML testando múltiplos métodos de codificação
    """
    if not os.path.exists(puml_file):
        print(f"❌ Erro: Arquivo {puml_file} não encontrado!")
        return False
    
    # Lê o conteúdo
    with open(puml_file, 'r', encoding='utf-8') as f:
        puml_content = f.read().strip()
    
    print(f"📄 Conteúdo PlantUML: {len(puml_content)} caracteres")
    
    # Lista de métodos para testar
    methods = [
        ("DEFLATE Moderno", lambda: plantuml_encode_modern(puml_content), "~1"),
        ("DEFLATE Legacy", lambda: plantuml_encode_legacy(puml_content), ""),
        ("HEX", lambda: puml_content.encode('utf-8').hex(), "~h"),
        ("Base64 Simples", lambda: base64.b64encode(puml_content.encode('utf-8')).decode('ascii'), ""),
    ]
    
    for method_name, encoder, prefix in methods:
        try:
            print(f"\n🔄 Tentando método: {method_name}")
            
            encoded = encoder()
            url = f"http://www.plantuml.com/plantuml/png/{prefix}{encoded}"
            
            print(f"   URL: {url[:80]}...")
            
            if test_url(url, output_file):
                print(f"✅ Sucesso com {method_name}!")
                print(f"   Arquivo: {output_file}")
                return True
            else:
                print(f"❌ {method_name} falhou")
                
        except Exception as e:
            print(f"❌ {method_name} erro: {e}")
    
    print(f"\n❌ Todos os métodos falharam!")
    return False

if __name__ == "__main__":
    puml_file = sys.argv[1] if len(sys.argv) > 1 else "storage/uml/domain-models.puml"
    output_file = "storage/uml/domain-models.png"
    
    # Cria diretório se necessário
    os.makedirs(os.path.dirname(output_file), exist_ok=True)
    
    generate_uml_image_robust(puml_file, output_file)
