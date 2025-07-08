#!/usr/bin/env python3

import base64
import urllib.parse
import urllib.request
import sys
import os
import zlib

def plantuml_encode(plantuml_text):
    """
    Codifica o texto PlantUML usando o método DEFLATE correto do PlantUML
    Baseado em: https://plantuml.com/text-encoding
    """
    # Remove quebras de linha extras e normaliza
    plantuml_text = plantuml_text.strip()
    
    # Converte para bytes UTF-8
    utf8_bytes = plantuml_text.encode('utf-8')
    
    # Comprime usando DEFLATE (sem cabeçalho/rodapé zlib)
    compressed = zlib.compress(utf8_bytes)[2:-4]
    
    # Codifica para base64
    b64 = base64.b64encode(compressed).decode('ascii')
    
    # Aplica as transformações específicas do PlantUML para URLs
    # Substitui caracteres problemáticos em URLs
    url_safe = b64.replace('+', '-').replace('/', '_').rstrip('=')
    
    return url_safe

def plantuml_encode_hex(plantuml_text):
    """
    Método alternativo: codificação hex simples
    """
    hex_string = plantuml_text.encode('utf-8').hex()
    return f"~h{hex_string}"

def generate_uml_image(puml_file, output_file):
    """
    Gera uma imagem PNG a partir de um arquivo PlantUML usando o serviço web
    """
    if not os.path.exists(puml_file):
        print(f"Erro: Arquivo {puml_file} não encontrado!")
        return False
    
    try:
        # Lê o conteúdo do arquivo PlantUML
        with open(puml_file, 'r', encoding='utf-8') as f:
            puml_content = f.read()
        
        print(f"Tentando método DEFLATE...")
        
        # Primeiro tenta com codificação DEFLATE
        encoded_content = plantuml_encode(puml_content)
        url = f"http://www.plantuml.com/plantuml/png/~1{encoded_content}"
        
        print(f"URL DEFLATE: {url[:100]}...")
        
        try:
            urllib.request.urlretrieve(url, output_file)
            
            # Verifica se a imagem foi gerada corretamente (não é uma página de erro)
            with open(output_file, 'rb') as f:
                file_header = f.read(8)
                if file_header.startswith(b'\x89PNG\r\n\x1a\n'):
                    print(f"✅ Imagem DEFLATE gerada com sucesso: {output_file}")
                    return True
                else:
                    print("❌ Arquivo gerado não é uma imagem PNG válida")
                    raise Exception("Arquivo inválido")
                    
        except Exception as e:
            print(f"❌ Método DEFLATE falhou: {e}")
            print(f"Tentando método HEX...")
            
            # Tenta com codificação HEX
            hex_encoded = plantuml_encode_hex(puml_content)
            url_hex = f"http://www.plantuml.com/plantuml/png/{hex_encoded}"
            
            print(f"URL HEX: {url_hex[:100]}...")
            
            urllib.request.urlretrieve(url_hex, output_file)
            
            # Verifica se a imagem HEX foi gerada corretamente
            with open(output_file, 'rb') as f:
                file_header = f.read(8)
                if file_header.startswith(b'\x89PNG\r\n\x1a\n'):
                    print(f"✅ Imagem HEX gerada com sucesso: {output_file}")
                    return True
                else:
                    print("❌ Método HEX também falhou")
                    return False
        
    except Exception as e:
        print(f"Erro geral ao gerar a imagem: {e}")
        return False
        
    except Exception as e:
        print(f"Erro ao gerar a imagem: {e}")
        return False

if __name__ == "__main__":
    puml_file = sys.argv[1] if len(sys.argv) > 1 else "storage/uml/domain-models.puml"
    output_file = "storage/uml/domain-models.png"
    
    # Cria o diretório de saída se não existir
    os.makedirs(os.path.dirname(output_file), exist_ok=True)
    
    generate_uml_image(puml_file, output_file)
