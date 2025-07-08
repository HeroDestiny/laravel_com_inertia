# Diagramas UML - Documentação

Este projeto inclui geração automática de diagramas UML das classes de domínio.

## 📋 Visão Geral

Os diagramas UML são gerados automaticamente a partir das classes de modelo do Laravel localizadas em `app/Models/`. O sistema gera tanto o código PlantUML quanto uma imagem PNG do diagrama.

## 🚀 Como Gerar os Diagramas

### Via Linha de Comando

```bash
# Usando Artisan (gera apenas o arquivo .puml)
php artisan generate:uml

# Usando npm (gera .puml e .png)
npm run docs:uml

# Usando composer
composer docs:uml
```

### Via Pipeline CI/CD

Os diagramas são gerados automaticamente:
- Em todo push para `main` ou `develop`
- Em todo pull request
- Os arquivos ficam disponíveis nos artifacts do GitHub Actions

## 📁 Arquivos Gerados

- `storage/uml/domain-models.puml` - Código fonte PlantUML
- `storage/uml/domain-models.png` - Imagem do diagrama

## 🔧 Personalização

### Modificando o Comando

O comando está localizado em `app/Console/Commands/GenerateUmlDiagram.php`. Você pode:

- Adicionar mais detalhes às classes
- Incluir relacionamentos específicos
- Alterar o estilo do diagrama
- Filtrar quais modelos incluir

### Adicionando Relacionamentos

Para incluir relacionamentos entre modelos, edite o método `generateRelationships()` no comando:

```php
private function generateRelationships(array $models): string
{
    $content = "\n' Relationships\n";
    
    // Exemplo: User tem muitos Pacientes
    if (in_array('User', $modelNames) && in_array('Paciente', $modelNames)) {
        $content .= "User ||--o{ Paciente : manages\n";
    }
    
    return $content;
}
```

### Tipos de Relacionamentos PlantUML

```
||--o{  : Um para muitos
||--||  : Um para um
}o--o{  : Muitos para muitos
||--o|  : Um para zero ou um
```

## 🎨 Temas e Estilos

O diagrama usa o tema `cerulean-outline` por padrão. Você pode alterar no comando:

```plantuml
!theme cerulean-outline
skinparam backgroundColor #FEFEFE
skinparam class {
  BackgroundColor #E8F4FD
  BorderColor #1E88E5
}
```

## 🔍 Visualização Online

Se você quiser visualizar rapidamente um diagrama PlantUML:

1. Copie o conteúdo do arquivo `.puml`
2. Visite [PlantUML Online](http://www.plantuml.com/plantuml/uml/)
3. Cole o conteúdo e visualize

## 🚀 Integração com Pipeline

### GitHub Actions

O workflow `documentation.yml` gera os diagramas automaticamente e:

- Faz upload dos arquivos como artifacts
- Comenta nos PRs com link para download
- Mantém os arquivos por 30 dias

### Customizando o Pipeline

Para modificar quando os diagramas são gerados, edite `.github/workflows/documentation.yml`:

```yaml
on:
  push:
    branches: [main, develop]
    paths: ['app/Models/**'] # Só executa se modelos mudarem
```

## 📊 Exemplo de Diagrama

O diagrama atual inclui as classes:

- **User**: Modelo de usuário com autenticação
- **Paciente**: Modelo de paciente com dados pessoais

### Estrutura das Classes

Cada classe mostra:
- ✅ Propriedades (campos do banco)
- ✅ Métodos públicos
- ✅ Relacionamentos
- ✅ Tipos de dados

## 🛠️ Solução de Problemas

### Erro ao gerar imagem

Se a geração da imagem falhar:

1. Verifique conexão com internet
2. Use o arquivo `.puml` manualmente no site do PlantUML
3. Instale PlantUML localmente: `java -jar plantuml.jar arquivo.puml`

### Modelos não aparecem

Certifique-se de que:
- Os modelos estão em `app/Models/`
- As classes são válidas e podem ser instanciadas
- O autoload do Composer está atualizado: `composer dump-autoload`

## 📚 Recursos Adicionais

- [Documentação PlantUML](https://plantuml.com/)
- [Guia de Diagramas de Classe](https://plantuml.com/class-diagram)
- [Laravel Model Documentation](https://laravel.com/docs/eloquent)
