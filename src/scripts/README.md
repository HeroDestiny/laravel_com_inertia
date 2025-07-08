# Scripts UML - Sistema Simplificado

Esta pasta contém apenas os scripts essenciais para geração de diagramas UML.

## 📋 Fluxo Simplificado

1. **Gerar arquivo .puml:** `npm run docs:uml`
2. **Visualizar online:** http://www.plantuml.com/plantuml/uml/
3. **Copiar conteúdo** de `storage/uml/domain-models.puml`
4. **Colar no editor** online do PlantUML

## Comandos NPM

```bash
# Gera arquivo .puml apenas
npm run docs:uml

# Verifica sistema
npm run docs:uml:check

# Mostra localização do arquivo
npm run docs:uml:view

# Link para visualização online
npm run docs:uml:online
```

## Como Visualizar

### 🌐 Online (Recomendado)
1. Acesse: http://www.plantuml.com/plantuml/uml/
2. Copie conteúdo de `storage/uml/domain-models.puml`
3. Cole na caixa de texto
4. Veja o diagrama gerado automaticamente

### 🔧 VS Code (Com Extensão)
1. Instale extensão "PlantUML"
2. Abra arquivo `.puml`
3. Use `Ctrl+Alt+P` para preview

## Arquivos Mantidos

### `check_uml_system.py` ⭐ **ÚNICO SCRIPT**
- Diagnóstico do sistema UML
- Verifica arquivos e configuração
- **Uso:** `python3 scripts/check_uml_system.py`

### `README.md`
- Este arquivo de documentação

## Arquivos Removidos ❌

Scripts de geração PNG foram removidos por serem desnecessários:
- ~~`generate_uml_image_robust.py`~~
- ~~`generate_uml_image.py`~~  
- ~~`generate-uml-image.sh`~~

## Solução de Problemas

```bash
# Diagnóstico
python3 scripts/check_uml_system.py

# Regenerar arquivo
npm run docs:uml

# Ver conteúdo
cat storage/uml/domain-models.puml
```

## Arquivos Principais

### `generate_uml_image_robust.py` ⭐ **PRINCIPAL**

- Script robusto para geração de imagens PNG
- Testa múltiplos métodos de codificação e servidores
- Melhor tratamento de erros e validação
- **Uso:** `python3 scripts/generate_uml_image_robust.py [arquivo.puml]`

### `generate_uml_image.py`

- Script básico de geração UML
- Implementa métodos DEFLATE e HEX
- Fallback simples entre métodos
- **Uso:** `python3 scripts/generate_uml_image.py [arquivo.puml]`

### `generate-uml-image.sh`

- Script shell que chama o método robusto
- Interface bash para geração UML
- **Uso:** `./scripts/generate-uml-image.sh [arquivo.puml]`

### `check_uml_system.py`

- Diagnóstico completo do sistema UML
- Verifica arquivos, conectividade e dependências
- Analisa configuração e estados dos arquivos
- **Uso:** `python3 scripts/check_uml_system.py`

## Comandos NPM

```bash
# Geração completa (Laravel + Python)
npm run docs:uml

# Verificação do sistema
npm run docs:uml:check

# Visualizar resultado
npm run docs:uml:view
```

## Fluxo de Geração

1. **Laravel:** `php artisan generate:uml` → Gera arquivo `.puml`
2. **Python:** `generate_uml_image_robust.py` → Converte para `.png`
3. **Resultado:** Diagrama visual em `storage/uml/domain-models.png`

## Solução de Problemas

```bash
# Diagnóstico completo
python3 scripts/check_uml_system.py

# Teste manual
python3 scripts/generate_uml_image_robust.py storage/uml/domain-models.puml

# Fallback simples
python3 scripts/generate_uml_image.py storage/uml/domain-models.puml
```

## Métodos de Codificação

- **DEFLATE Moderno** (padrão): Compressão + Base64 URL-safe
- **DEFLATE Legacy**: Algoritmo de codificação original PlantUML
- **Múltiplos Servidores**: HTTP/HTTPS, diferentes domínios

## Dependências

- Python 3.x
- Módulos: `zlib`, `base64`, `urllib.request` (built-in)
- Conectividade com internet (PlantUML online)
