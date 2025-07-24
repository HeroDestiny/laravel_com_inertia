# Testing - Documentação de Testes

Bem-vindo à documentação completa de testes do projeto Laravel + Inertia.js.

## 📊 Status Atual

-   ✅ **55 testes** com **190 assertions** (100% passando)
-   ⏱️ **~7 segundos** de execução total
-   🎯 **Cobertura completa**: Autenticação, CRUD, UML, Configurações
-   🏗️ **Estrutura sólida**: Unit Tests + Feature Tests

## 📚 Documentação

### 🚀 [README.md](./README.md) - Visão Geral dos Testes

**O que você encontrará:**

-   Status e métricas dos testes
-   Estrutura detalhada por área (Auth, Pacientes, UML, etc.)
-   Como executar testes (completos, específicos, com cobertura)
-   Configuração do ambiente de teste
-   Ferramentas e patterns utilizados

**Ideal para**: Entender o que já está testado e como executar.

### 📋 [BEST_PRACTICES.md](./BEST_PRACTICES.md) - Guia de Boas Práticas

**O que você encontrará:**

-   AAA Pattern (Arrange, Act, Assert)
-   Nomenclatura e organização de testes
-   Uso adequado de Factories, Mocks e Assertions
-   Patterns por tipo de componente (Models, Controllers, Commands)
-   Otimizações de performance
-   Integração com CI/CD

**Ideal para**: Criar novos testes seguindo padrões estabelecidos.

### 🔧 [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) - Soluções para Problemas

**O que você encontrará:**

-   Problemas de configuração (banco, autoload, chaves)
-   Issues de execução (lentidão, memory limit)
-   Problemas com mocks e autenticação
-   Debugging e diagnóstico
-   Comandos de emergência

**Ideal para**: Resolver problemas quando testes falham ou têm comportamento inesperado.

## 🎯 Navegação Rápida

### Por Necessidade:

**Quero executar testes:**

```bash
# Execução rápida (3s) - apenas essencial
./scripts/quick-check-fast.sh

# Execução completa (~7s) - todos os testes
vendor/bin/phpunit

# Execução com detalhes
vendor/bin/phpunit --testdox
```

**Quero criar um novo teste:**

1. 📋 Leia [Best Practices](./BEST_PRACTICES.md) primeiro
2. 🚀 Veja exemplos em [README.md](./README.md)
3. 🔧 Use [Troubleshooting](./TROUBLESHOOTING.md) se algo der errado

**Algo não está funcionando:**

1. 🔧 Consulte [Troubleshooting](./TROUBLESHOOTING.md) - checklist completo
2. 📋 Verifique [Best Practices](./BEST_PRACTICES.md) - pode ser pattern incorreto
3. 🚀 Compare com exemplos em [README.md](./README.md)

### Por Tipo de Teste:

| Tipo              | Localização              | Exemplos no README                    |
| ----------------- | ------------------------ | ------------------------------------- |
| **Unit Tests**    | `tests/Unit/`            | ✅ PacienteTest (9 testes)            |
| **Feature Tests** | `tests/Feature/`         | ✅ PacienteControllerTest (10 testes) |
| **Auth Tests**    | `tests/Feature/Auth/`    | ✅ AuthenticationTest (4 testes)      |
| **Console Tests** | `tests/Feature/Console/` | ✅ GenerateUmlDiagramTest (6 testes)  |

## 🏆 Destaques da Suíte de Testes

### ✅ **Cobertura Exemplar:**

-   **20 testes** de autenticação completa (login, registros, reset, verificação)
-   **19 testes** de CRUD Pacientes (unit + feature + validações)
-   **6 testes** de comando UML customizado
-   **2 testes** de dashboard e navegação

### ✅ **Qualidade Superior:**

-   Uso adequado de **Factories** para dados consistentes
-   **RefreshDatabase** para isolamento
-   **Inertia Assertions** específicas para frontend
-   **Validação completa** de regras de negócio

### ✅ **Performance Otimizada:**

-   SQLite em memória para velocidade
-   Configurações específicas de teste
-   Execução em ~7 segundos para 55 testes

## 📈 Próximos Passos

### Se você é novo no projeto:

1. 📖 Leia [README.md](./README.md) para entender a estrutura atual
2. 🚀 Execute `vendor/bin/phpunit --testdox` para ver todos os testes
3. 📋 Familiarize-se com [Best Practices](./BEST_PRACTICES.md)

### Se vai criar novos testes:

1. 📋 Siga os patterns em [Best Practices](./BEST_PRACTICES.md)
2. 🔍 Use os exemplos existentes como referência
3. 🔧 Mantenha [Troubleshooting](./TROUBLESHOOTING.md) à mão

### Se está com problemas:

1. 🔧 Consulte [Troubleshooting](./TROUBLESHOOTING.md) primeiro
2. 📋 Verifique se está seguindo [Best Practices](./BEST_PRACTICES.md)
3. 🚀 Compare com exemplos funcionais em [README.md](./README.md)

---

## 🎯 TL;DR

**Para executar**: `vendor/bin/phpunit` ou `./scripts/quick-check-fast.sh`  
**Para criar**: Leia [BEST_PRACTICES.md](./BEST_PRACTICES.md)  
**Para debugar**: Use [TROUBLESHOOTING.md](./TROUBLESHOOTING.md)  
**Para entender**: Veja [README.md](./README.md)

**Status**: 🟢 **55/55 testes passando** - Suite robusta e confiável!
