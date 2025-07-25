# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.1] - 2025-07-25

### � Corrigido - CI/CD Pipeline

-   Corrigido erro de cache Docker no GitHub Actions
-   Corrigido erro de secrets não configurados (Slack, Snyk, Codecov)
-   Pipeline agora funciona sem configuração externa obrigatória
-   Adicionado fallbacks e mensagens informativas

### ✨ Adicionado - SonarQube Integration

-   Configuração completa para análise de qualidade com SonarQube
-   Suporte a cobertura de testes PHP (PHPUnit) e JavaScript (Vitest)
-   Quality Gates automatizados no pipeline CI/CD
-   Documentação detalhada de configuração e uso

## [3.0.0] - 2025-07-22

### 🔒 Adicionado - Segurança

-   Análise completa de segurança baseada no OWASP Top 10 2021
-   Documento de análise de segurança (`docs/SECURITY_ANALYSIS.md`)
-   Scripts seguros com validação de entrada e sanitização de output
-   Logs seguros sem exposição de dados sensíveis

### ✨ Adicionado - Sistema UML

-   Comando Artisan `generate:uml` para geração automática de diagramas
-   Script de diagnóstico completo do sistema UML (`src/scripts/check_uml_system.py`)
-   Suporte a múltiplos métodos de encoding com fallback automático
-   Visualização online integrada com PlantUML
-   Testes automatizados para validação de diagramas UML

### 🐳 DevContainer

-   PostgreSQL integrado no ambiente DevContainer
-   Scripts de setup automático pós-rebuild
-   Configuração zero-hassle para novos desenvolvedores
-   Conectividade PostgreSQL validada automaticamente

### 🔧 Corrigido - Scripts PostgreSQL

-   **BREAKING**: Removidas credenciais hardcoded do código
-   Implementação de prepared statements para prevenir SQL injection
-   Uso de variáveis de ambiente para configurações sensíveis
-   Parsing seguro de arquivos .env com `parse_ini_file()`
-   Sanitização de output com `htmlspecialchars()`
-   Error handling sem exposição de informações sensíveis
-   Timeout de conexão para prevenir ataques DoS

### 📊 Documentação

-   Documentação UML atualizada com sistema robusto
-   Guias de segurança com exemplos práticos
-   Índice principal reorganizado com categorias claras
-   Emojis para melhor navegação visual
-   Links externos para ferramentas de segurança

### 🧪 Adicionado - Testes

-   Testes para comando de geração UML (`GenerateUmlDiagramTest`)
-   Validação automática de diagramas gerados
-   Cobertura de casos edge (modelos não encontrados, diretórios inexistentes)

## [2.0.0] - 2025-01-XX

### Adicionado

-   Configuração DevContainer inicial
-   Integração Inertia.js + Vue.js
-   Setup básico Laravel 11
-   Documentação inicial

## [1.0.0] - 2024-XX-XX

### Adicionado

-   Projeto inicial Laravel + Inertia
-   Configuração básica

---

## Guia de Versioning

-   **MAJOR** (X.0.0): Mudanças incompatíveis na API ou breaking changes
-   **MINOR** (x.Y.0): Novas funcionalidades mantendo compatibilidade
-   **PATCH** (x.y.Z): Correções de bugs mantendo compatibilidade

## Status de Segurança

-   ✅ **OWASP A01:2021** - Controle de Acesso: Implementado
-   ✅ **OWASP A02:2021** - Falhas Criptográficas: Verificado
-   ✅ **OWASP A03:2021** - Injection\*\*: Prepared statements implementados
-   ✅ **OWASP A05:2021** - Configuração Incorreta: Corrigido
-   ✅ **OWASP A06:2021** - Componentes Vulneráveis: Atualizado
-   ✅ **OWASP A07:2021** - Identificação e Autenticação: Validado

## Próximas Melhorias

### v3.1.0 (Planejado)

-   [ ] Cache inteligente para geração UML
-   [ ] Relacionamentos automáticos entre models
-   [ ] Geração de múltiplos formatos (SVG, PDF)
-   [ ] Dashboard de métricas de segurança

### v3.2.0 (Futuro)

-   [ ] Integração com Docker Compose para produção
-   [ ] Monitoramento automático de vulnerabilidades
-   [ ] Pipeline de segurança no CI/CD
-   [ ] Documentação automática de APIs
