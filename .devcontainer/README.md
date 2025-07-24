# Laravel + Inertia + Vue Development Container

Este devcontainer está otimizado para desenvolvimento com Laravel, Inertia.js, Vue 3 + TypeScript e **PostgreSQL**.

## 🚀 Recursos Incluídos

### Backend

-   **PHP 8.2** com Xdebug configurado
-   **PostgreSQL 16** pré-configurado e pronto para uso
-   **Composer** para gerenciamento de dependências PHP
-   **Laravel Framework** com Inertia.js
-   **PHPStan** e **Psalm** para análise estática

### Frontend

-   **Node.js LTS** para desenvolvimento frontend
-   **Vue 3** com TypeScript
-   **Vite** como bundler
-   **TailwindCSS v4** para estilização
-   **ESLint** e **Prettier** para qualidade de código

### Banco de Dados

-   **PostgreSQL 16** rodando em container Docker
-   **Migrações automáticas** na inicialização
-   **Conexão pré-configurada** via variáveis de ambiente

### Ferramentas de Desenvolvimento

-   **Python 3.11** para scripts de documentação UML
-   **Git** para controle de versão
-   **VS Code** com extensões pré-configuradas

## ⚡ Inicialização Automática

Quando você abrir o devcontainer, tudo será configurado automaticamente:

1. 🔧 **Container builds** com PHP + PostgreSQL drivers
2. 📦 **Dependências instaladas** (Composer + NPM)
3. 🗄️ **PostgreSQL iniciado** e aguardando conexão
4. 🔄 **Migrações executadas** automaticamente
5. ✅ **Ambiente pronto** para desenvolvimento

## 📋 Comandos Rápidos

Execute via `Ctrl+Shift+P` → "Tasks: Run Task":

-   **🚀 Start Dev Environment** - Inicia Laravel + Vite juntos
-   **Laravel: Serve** - Servidor Laravel (porta 8000)
-   **Vite: Dev Server** - Servidor de desenvolvimento (porta 5173)
-   **Build Production** - Build de produção
-   **Run Tests** - Executa testes PHPUnit
-   **Generate UML Docs** - Gera documentação UML
-   **Lint & Format** - Formata e verifica código

## 🌐 URLs de Acesso

-   **Laravel App**: http://localhost:8000
-   **Vite Dev Server**: http://localhost:5173

## 🗄️ Configuração PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel_inertia
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

-   **Frontend**: Vue.js (Volar), ESLint, Prettier, TailwindCSS
-   **Análise**: PHPStan, Psalm
-   **Produtividade**: GitHub Copilot

## 🎯 Tarefas Pré-configuradas

Execute via `Ctrl+Shift+P` → "Tasks: Run Task":

-   **Dev Environment: Start All** - Inicia Laravel + Vite juntos
-   **Laravel: Serve** - Servidor Laravel (porta 8000)
-   **Vite: Dev Server** - Servidor de desenvolvimento (porta 5173)
-   **Build Production** - Build de produção
-   **Run Tests** - Executa testes PHPUnit
-   **Generate UML Docs** - Gera documentação UML
-   **Lint & Format** - Formata e verifica código

## 🔧 Configuração Automática

O devcontainer executa automaticamente:

1. Instalação de dependências PHP e Node.js
2. Configuração do arquivo `.env`
3. Geração da chave da aplicação Laravel
4. Cache de configurações para performance
5. Instalação do PlantUML para documentação

## 🐛 Debug PHP

O Xdebug está pré-configurado:

-   **Porta**: 9000
-   **Modo**: debug
-   **Auto-start**: habilitado

Use `F5` para iniciar o debug ou a configuração "Listen for Xdebug".

## 🌐 Portas Expostas

-   **8000**: Laravel Application
-   **5173**: Vite Dev Server
-   **9000**: Xdebug (debug)

## 🚀 Início Rápido

1. Abra o projeto no VS Code com Dev Containers
2. Aguarde a configuração automática
3. Execute a tarefa "Dev Environment: Start All"
4. Acesse http://localhost:8000

## 💾 Volumes Persistentes

Para evitar reinstalações:

-   `node_modules` persistido em volume Docker
-   `vendor` persistido em volume Docker
-   `storage/framework/cache` persistido em volume Docker

## 🔄 Script de Setup Manual

Se necessário, execute:

```bash
/workspaces/laravel_com_inertia/scripts/setup-dev.sh
```

## 📁 Estrutura de Arquivos

```
├── .devcontainer/
│   └── devcontainer.json     # Configuração do container
├── .vscode/
│   ├── tasks.json           # Tarefas VS Code
│   ├── launch.json          # Configurações de debug
│   └── extensions.json      # Extensões recomendadas
├── scripts/
│   └── setup-dev.sh         # Script de configuração
└── src/                     # Código do projeto Laravel
```
