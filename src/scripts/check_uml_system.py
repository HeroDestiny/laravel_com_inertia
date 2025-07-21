#!/usr/bin/env python3

import os
import sys


def check_uml_system():
    """
    Script de diagnóstico para o sistema UML
    """
    print("Diagnóstico do Sistema UML\n")
    # Verifica arquivos necessários
    files_to_check = [
        "storage/uml/domain-models.puml",
        "app/Console/Commands/GenerateUmlDiagram.php"
    ]

    print("Verificando arquivos:")
    for file in files_to_check:
        if os.path.exists(file):
            size = os.path.getsize(file)
            print(f"   OK {file} ({size} bytes)")
        else:
            print(f"   ERRO {file} - FALTANDO!")

    # Verifica se o arquivo PUML é válido
    puml_file = "storage/uml/domain-models.puml"
    if os.path.exists(puml_file):
        with open(puml_file, 'r', encoding='utf-8') as f:
            content = f.read()
            if content.strip().startswith('@startuml') and content.strip().endswith('@enduml'):
                print(f"   OK PlantUML válido")
            else:
                print(
                    f"   ERRO PlantUML inválido (deve começar com @startuml e terminar com @enduml)")

    # Testa conectividade com PlantUML (opcional)
    print("\nTestando conectividade (opcional para visualização online):")
    try:
        import urllib.request
        response = urllib.request.urlopen(
            "http://www.plantuml.com/plantuml/", timeout=5)
        if response.status == 200:
            print("   OK PlantUML online acessível")
            print(
                "   INFO Você pode visualizar o diagrama em: http://www.plantuml.com/plantuml/uml/")
        else:
            print(f"   AVISO PlantUML retornou status {response.status}")
    except Exception as e:
        print(f"   ERRO Erro de conectividade: {e}")
        print("   INFO Sem problema! Você ainda pode usar extensões locais do VS Code")

    # Verifica dependências Python (não mais necessárias para geração de PNG)
    print("\nDependências Python (para scripts opcionais):")
    print("   INFO Não são mais necessárias para geração básica de UML")
    print("   INFO Arquivo .puml pode ser visualizado diretamente")

    # Lê e analisa o arquivo PUML
    puml_file = "storage/uml/domain-models.puml"
    if os.path.exists(puml_file):
        print("\nAnálise do arquivo PlantUML:")
        with open(puml_file, 'r') as f:
            content = f.read()
            lines = content.split('\n')
            print(f"   LINHAS: {len(lines)}")
            print(f"   CARACTERES: {len(content)}")
            print(f"   INICIO: {lines[0] if lines else 'vazio'}")
            print(f"   FIM: {lines[-1] if lines else 'vazio'}")

            # Conta classes
            classes = [
                line for line in lines if line.strip().startswith('class ')]
            print(f"   CLASSES encontradas: {len(classes)}")
            for cls in classes:
                cls_name = cls.split()[1].replace('{', '')
                print(f"      • {cls_name}")

    print("\nDiagnóstico concluído!")

    # Verifica configuração npm
    print("\nVerificando configuração npm:")
    if os.path.exists("package.json"):
        with open("package.json", 'r') as f:
            import json
            try:
                package_data = json.load(f)
                scripts = package_data.get('scripts', {})
                if 'docs:uml' in scripts:
                    print(f"   OK npm run docs:uml: {scripts['docs:uml']}")
                    print("   INFO Gera apenas arquivo .puml (sem PNG)")
                else:
                    print("   ERRO Script docs:uml não encontrado no package.json")
            except:
                print("   ERRO Erro ao ler package.json")
    else:
        print("   ERRO package.json não encontrado")

    return True


if __name__ == "__main__":
    check_uml_system()
