# Integração SonarQube - Guia de Configuração

## 🎯 Overview

Este projeto está configurado para integrar automaticamente com SonarQube, fornecendo análise de qualidade de código e cobertura de testes para PHP e JavaScript/TypeScript.

## ⚙️ Configuração

### 1. **Secrets Necessários**

Configure os seguintes secrets no GitHub:

-   `SONAR_TOKEN` - Token de autenticação do SonarQube
-   `SONAR_HOST_URL` - URL do servidor SonarQube (ex: https://sonarcloud.io)

### 2. **Como Obter os Tokens**

#### **SonarCloud (Recomendado)**

1. Acesse [sonarcloud.io](https://sonarcloud.io)
2. Faça login com GitHub
3. Importe seu repositório
4. Vá em Account > Security > Generate Token
5. Configure os secrets no GitHub

#### **SonarQube Self-Hosted**

1. Acesse sua instância SonarQube
2. Crie um novo projeto
3. Generate token em User > My Account > Security
4. Configure SONAR_HOST_URL com sua URL

## 📊 Relatórios Gerados

### **PHP (Laravel)**

-   ✅ Cobertura de testes PHPUnit
-   ✅ Análise de qualidade de código
-   ✅ Detecção de code smells
-   ✅ Métricas de complexidade

### **JavaScript/TypeScript/Vue**

-   ✅ Cobertura de testes Vitest
-   ✅ Análise de código ES6+
-   ✅ Detecção de problemas Vue.js
-   ✅ Métricas de manutenibilidade

## 🔧 Arquivos de Configuração

### **sonar-project.properties**

Configuração principal do SonarQube:

```properties
sonar.projectKey=laravel-inertia-project
sonar.sources=src/app,src/resources/js
sonar.php.coverage.reportPaths=src/coverage/clover.xml
sonar.javascript.lcov.reportPaths=src/coverage/lcov.info
```

### **phpunit.xml**

Configurado para gerar relatórios de cobertura:

```xml
<coverage>
    <report>
        <clover outputFile="coverage/clover.xml"/>
    </report>
</coverage>
```

### **vitest.config.ts**

Configuração para testes JavaScript:

```typescript
coverage: {
    provider: 'v8',
    reporter: ['lcov', 'text', 'html']
}
```

## 🚀 Como Executar

### **Localmente**

```bash
# Testes PHP com cobertura
npm run test:coverage

# Testes JavaScript com cobertura
npm run test:js:coverage

# Análise SonarQube local (requer sonar-scanner)
sonar-scanner
```

### **CI/CD**

O workflow automaticamente:

1. Executa testes PHP e JS
2. Gera relatórios de cobertura
3. Envia para SonarQube
4. Avalia Quality Gate

## 📈 Métricas Coletadas

### **Cobertura**

-   Cobertura de linhas
-   Cobertura de branches
-   Cobertura de condições

### **Qualidade**

-   Bugs detectados
-   Vulnerabilidades
-   Code smells
-   Duplicação de código

### **Manutenibilidade**

-   Complexidade ciclomática
-   Dívida técnica
-   Linhas de código

## 🛡️ Quality Gates

### **Critérios Padrão**

-   Cobertura > 80%
-   0 bugs críticos
-   0 vulnerabilidades
-   Rating A para manutenibilidade

### **Personalizar**

1. Acesse seu projeto no SonarQube
2. Quality Gates > Create
3. Defina suas métricas
4. Associe ao projeto

## 🐛 Troubleshooting

### **"SONAR_TOKEN not found"**

-   ✅ **Solução:** Configure o secret no GitHub
-   ℹ️ **Alternativa:** Pipeline funciona sem SonarQube

### **"No coverage reports found"**

-   ✅ **Verificar:** Testes estão executando?
-   ✅ **Verificar:** Diretório coverage/ está sendo criado?

### **"Quality Gate failed"**

-   ✅ **Verificar:** Métricas no dashboard SonarQube
-   ✅ **Ajustar:** Critérios do Quality Gate

## 🔗 Links Úteis

-   [SonarCloud](https://sonarcloud.io)
-   [SonarQube Documentation](https://docs.sonarqube.org/)
-   [GitHub Actions Integration](https://github.com/SonarSource/sonarqube-scan-action)
-   [Quality Gates](https://docs.sonarqube.org/latest/user-guide/quality-gates/)
