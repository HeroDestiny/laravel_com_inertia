# Laravel + Inertia + Vue Development Container

Este devcontainer está otimizado para desenvolvimento com Laravel, Inertia.js e Vue 3 + TypeScript.

## Recursos Incluídos

### Backend

-   **PHP 8.2** com Xdebug configurado
-   **Composer** para gerenciamento de dependências PHP

### Frontend

**PlantUML** para geração de diagramas

-   **Node.js LTS** para desenvolvimento frontend
-   **Vue 3** com TypeScript
-   **Vite** como bundler
-   **TailwindCSS v4** para estilização
-   **ESLint** e **Prettier** para qualidade de código
    **Lint & Format** - Formata e verifica o código

### Ferramentas de Desenvolvimento

-   **Python 3.11** para scripts de documentação UML
-   **Git** para controle de versão
-   **VS Code** com extensões pré-configuradas

4. Cache de configurações para desempenho

## Extensões VS Code Incluídas

-   **PHP**: Intelephense, Laravel Blade, Laravel IntelliSense
    **Inicialização automática**: habilitada
-   **Análise**: PHPStan, Psalm
-   **Produtividade**: GitHub Copilot

## Tarefas Pré-configuradas

4. Acesse: http://localhost:8000

Execute via `Ctrl+Shift+P` → "Tasks: Run Task":

-   **Dev Environment: Start All** - Inicia Laravel + Vite juntos
-   **Laravel: Serve** - Servidor Laravel (porta 8000)
    `node_modules` persistido em volume do Docker
    `vendor` persistido em volume do Docker
    `storage/framework/cache` persistido em volume do Docker
-   **Generate UML Docs** - Gera documentação UML
-   **Lint & Format** - Formata e verifica código

## Script de Setup manual

O devcontainer executa automaticamente:

1. Instalação de dependências PHP e Node.js
2. Configuração do arquivo `.env`
3. Geração da chave da aplicação Laravel
   │ └── devcontainer.json # Configuração do Dev Container
4. Instalação do PlantUML para documentação

## Debug PHP

O Xdebug está pré-configurado:

-   **Porta**: 9000
-   **Modo**: debug
-   **Auto-start**: habilitado

Use `F5` para iniciar o debug ou a configuração "Listen for Xdebug".

## Portas Expostas

-   **8000**: Laravel Application
-   **5173**: Vite Dev Server
-   **9000**: Xdebug (debug)

## Início Rápido

1. Abra o projeto no VS Code com Dev Containers
2. Aguarde a configuração automática
3. Execute a tarefa "Dev Environment: Start All"
4. Acesse http://localhost:8000

## Volumes Persistentes

Para evitar reinstalações:

-   `node_modules` persistido em volume Docker
-   `vendor` persistido em volume Docker
-   `storage/framework/cache` persistido em volume Docker

## Script de Setup Manual

Se necessário, execute:

```bash
/workspaces/laravel_com_inertia/scripts/setup-dev.sh
```

## Estrutura de Arquivos

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
