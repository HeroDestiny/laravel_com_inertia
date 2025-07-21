# DevContainer Configuration - OTIMIZADO

Este diretório contém a configuração para o ambiente de desenvolvimento Docker.

## ✅ Problemas Resolvidos

**ANTES**: O devcontainer estava baixando imagens duplicadas devido a:

-   Múltiplos arquivos de configuração
-   Features redundantes (Composer, Git já vêm na imagem base)
-   Conflitos entre configurações

**AGORA**: Configuração limpa e otimizada:

## 📁 Arquivos

-   **`devcontainer.json`** - ✅ ÚNICA configuração ativa
-   **`docker-compose.postgres.yml`** - 📝 Apenas referência para PostgreSQL
-   ~~`devcontainer-backup.json`~~ - ❌ Removido
-   ~~`devcontainer-fallback.json`~~ - ❌ Removido

## 🎯 Imagem Única

```json
"image": "mcr.microsoft.com/devcontainers/php:1-8.2-bullseye"
```

### Features Otimizadas:

-   ✅ Node.js LTS (para Vue + Vite)
-   ✅ Python 3.11 (para scripts UML)
-   ❌ ~~Composer~~ (já vem na imagem)
-   ❌ ~~Git~~ (já vem na imagem)

## 🚀 Resultado

-   **1 única imagem** baixada
-   **Tempo de build reduzido**
-   **Sem conflitos de configuração**
-   **Melhor performance**

O VS Code agora usa apenas o `devcontainer.json` principal!
