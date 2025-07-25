# Comandos NPM Disponíveis

Este documento lista todos os comandos NPM configurados no projeto, incluindo scripts do projeto principal e scripts auxiliares.

## Comandos de Desenvolvimento

### Build e Vite

```bash
npm run build           # Build para produção
npm run build:ssr       # Build com SSR
npm run dev            # Servidor de desenvolvimento
```

### Qualidade de Código

```bash
npm run format         # Formatar código com Prettier
npm run format:check   # Verificar formatação
npm run lint           # ESLint com correção automática
npm run type-check     # Verificação de tipos TypeScript
```

### Testes Laravel

```bash
npm run test           # Todos os testes
npm run test:unit      # Apenas testes unitários
npm run test:feature   # Apenas testes de feature
npm run test:coverage  # Testes com cobertura
```

## Comandos UML

### Geração de Diagramas

```bash
npm run docs:uml       # Gera .puml + PNG (completo)
npm run docs:uml:puml  # Gera apenas arquivo .puml
```

### Formatos Específicos

```bash
npm run docs:uml:png   # Imagem PNG
npm run docs:uml:svg   # Imagem SVG (vetorial)
npm run docs:uml:pdf   # Documento PDF
npm run docs:uml:all   # Todos os formatos
```

### Utilitários UML

```bash
npm run docs:uml:formats  # Lista formatos disponíveis
npm run docs:uml:check    # Diagnóstico do sistema
```

## Scripts de Sistema (Pasta ../scripts/)

### Docker e Infraestrutura

```bash
npm run docker:manager   # Gerenciador Docker
npm run docker:validate  # Validação de segurança Docker
```

### Verificações e Testes

```bash
npm run check:fast       # Verificações rápidas (Pint, PHPStan)
npm run check:local      # Verificações locais completas
npm run test:postgres    # Teste de conexão PostgreSQL
```

### Setup e Configuração

```bash
npm run setup:rebuild    # Setup após rebuild do container
```

## Como Usar Scripts .sh

Os comandos NPM podem executar scripts bash de várias formas:

### 1. Diretamente

```json
{
    "scripts": {
        "meu-script": "bash scripts/meu-script.sh"
    }
}
```

### 2. Com argumentos

```bash
npm run docker:manager -- status
npm run docker:manager -- --help
```

### 3. Com mudança de diretório

```json
{
    "scripts": {
        "script-raiz": "cd .. && bash scripts/script.sh"
    }
}
```

## Estrutura dos Scripts

```
laravel_com_inertia/
├── src/
│   ├── package.json          # Comandos NPM
│   └── scripts/              # Scripts UML (Python)
│       ├── generate_uml_image.py
│       └── check_uml_system.py
└── scripts/                  # Scripts de sistema (Bash/PHP)
    ├── docker-manager.sh
    ├── quick-check-fast.sh
    ├── quick-check-local.sh
    ├── setup-after-rebuild.sh
    ├── test-postgres-connection.php
    └── validate-docker-security.sh
```

## Dicas de Uso

### Para desenvolvimento diário:

```bash
npm run dev                  # Inicia servidor
npm run docs:uml            # Gera documentação UML
npm run lint                # Lint e formatação de código
```

### Para verificações:

```bash
npm run check:fast          # Verificação rápida
npm run test                # Testes completos
npm run test:postgres       # Testa conexão com banco de dados
```

### Para deployment:

```bash
npm run build               # Build para produção
npm run docker:validate     # Valida segurança do Docker
```

### Para troubleshooting:

```bash
npm run docs:uml:check      # Diagnóstico UML
npm run docker:manager -- status  # Status dos containers
```
