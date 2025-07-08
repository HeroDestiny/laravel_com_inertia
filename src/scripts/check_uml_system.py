#!/usr/bin/env python3

import os
import sys

def check_uml_system():
    """
    Script de diagnóstico para o sistema UML
    """
    print("🔍 Diagnóstico do Sistema UML\n")
    
    # Verifica arquivos necessários
    files_to_check = [
        "storage/uml/domain-models.puml",
        "storage/uml/domain-models.png",
        "scripts/generate_uml_image_robust.py",
        "app/Console/Commands/GenerateUmlDiagram.php"
    ]
    
    print("📁 Verificando arquivos:")
    for file in files_to_check:
        if os.path.exists(file):
            size = os.path.getsize(file)
            print(f"   ✅ {file} ({size} bytes)")
        else:
            print(f"   ❌ {file} - FALTANDO!")
    
    # Verifica se a imagem PNG é válida
    png_file = "storage/uml/domain-models.png"
    if os.path.exists(png_file):
        with open(png_file, 'rb') as f:
            header = f.read(8)
            if header.startswith(b'\x89PNG\r\n\x1a\n'):
                print(f"   ✅ PNG válido")
            else:
                print(f"   ❌ PNG inválido ou corrompido")
    
    # Testa conectividade com PlantUML
    print("\n🌐 Testando conectividade:")
    try:
        import urllib.request
        response = urllib.request.urlopen("http://www.plantuml.com/plantuml/", timeout=5)
        if response.status == 200:
            print("   ✅ PlantUML online acessível")
        else:
            print(f"   ⚠️  PlantUML retornou status {response.status}")
    except Exception as e:
        print(f"   ❌ Erro de conectividade: {e}")
    
    # Verifica dependências Python
    print("\n🐍 Verificando dependências Python:")
    required_modules = ['zlib', 'base64', 'urllib.request']
    for module in required_modules:
        try:
            __import__(module)
            print(f"   ✅ {module}")
        except ImportError:
            print(f"   ❌ {module} - FALTANDO!")
    
    # Lê e analisa o arquivo PUML
    puml_file = "storage/uml/domain-models.puml"
    if os.path.exists(puml_file):
        print("\n📄 Análise do arquivo PlantUML:")
        with open(puml_file, 'r') as f:
            content = f.read()
            lines = content.split('\n')
            print(f"   📏 Linhas: {len(lines)}")
            print(f"   📝 Caracteres: {len(content)}")
            print(f"   🎯 Início: {lines[0] if lines else 'vazio'}")
            print(f"   🏁 Fim: {lines[-1] if lines else 'vazio'}")
            
            # Conta classes
            classes = [line for line in lines if line.strip().startswith('class ')]
            print(f"   🏗️  Classes encontradas: {len(classes)}")
            for cls in classes:
                cls_name = cls.split()[1].replace('{', '')
                print(f"      • {cls_name}")
    
    print("\n✅ Diagnóstico concluído!")
    return True

if __name__ == "__main__":
    check_uml_system()
