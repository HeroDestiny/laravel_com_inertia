# Diagramas UML - Documentação

Este projeto inclui geração automática de diagramas UML## 🔧 Correções Implementadas ✅

### ✅ Problema de Codificação RESOLVIDO

O sistema agora implementa **múltiplos métodos** de codificação com fallback automático:

- ✅ **DEFLATE Moderno**: Método principal (DEFLATE + Base64 URL-safe)
- ✅ **DEFLATE Legacy**: Algoritmo clássico do PlantUML
- ✅ **HEX**: Codificação hexadecimal simples
- ✅ **Base64**: Fallback final

### 🔄 Como Funciona o Sistema Robusto

```python
# Testa múltiplos métodos automaticamente
methods = [
    ("DEFLATE Moderno", deflate_modern, "~1"),
    ("DEFLATE Legacy", deflate_legacy, ""),
    ("HEX", hex_encode, "~h"),
    ("Base64", base64_encode, ""),
]

# Para cada método, testa a URL e valida o PNG
for method, encoder, prefix in methods:
    url = f"http://www.plantuml.com/plantuml/png/{prefix}{encoded}"
    if test_url_and_validate_png(url):
        return success
```

### ✅ Resultado Atual

- **Método ativo**: DEFLATE Moderno
- **URL**: `http://www.plantuml.com/plantuml/png/~1[ENCODED]`
- **Status**: ✅ Funcionando perfeitamente
- **Arquivo**: PNG válido (790x328px, 53.8KB)s de domínio.

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

## � Correções Implementadas

### Problema de Codificação Resolvido

O sistema agora usa a **codificação DEFLATE** correta do PlantUML em vez de Base64 simples:

- ✅ **Antes**: Base64 simples causava erro "bad URL"
- ✅ **Agora**: DEFLATE + Base64 modificado com prefixo `~1`
- ✅ **Resultado**: URLs funcionais e imagens geradas corretamente

### Como Funciona a Codificação

```python
def plantuml_encode(plantuml_text):
    # 1. Comprime usando DEFLATE
    zlibbed_str = zlib.compress(plantuml_text.encode('utf-8'), 9)[2:-4]

    # 2. Codifica em Base64
    compressed_string = base64.b64encode(zlibbed_str).decode('ascii')

    # 3. Substitui caracteres para URL
    return compressed_string.replace('+', '-').replace('/', '_')
```

### URL Gerada

```
http://www.plantuml.com/plantuml/png/~1[ENCODED_CONTENT]
```

O prefixo `~1` indica ao PlantUML que está usando codificação DEFLATE.

## �🔍 Visualização Online

Se você quiser visualizar rapidamente um diagrama PlantUML:

1. Copie o conteúdo do arquivo `.puml`
2. Visite [PlantUML Online](http://www.plantuml.com/plantuml/uml/)
3. Cole o conteúdo e visualize

**Ou** use a URL gerada automaticamente no terminal após executar `npm run docs:uml`.

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

## � Troubleshooting & Diagnóstico

### 🔍 Comando de Diagnóstico

```bash
# Executa diagnóstico completo do sistema UML
npm run docs:uml:check
```

**O que o diagnóstico verifica:**

- ✅ Existência dos arquivos necessários
- ✅ Validade da imagem PNG gerada
- ✅ Conectividade com o PlantUML online
- ✅ Dependências Python disponíveis
- ✅ Análise do conteúdo PlantUML

### ✅ Problema: "Bad URL" do PlantUML

**Status**: ✅ **RESOLVIDO**

- Sistema agora usa múltiplos métodos de codificação
- Fallback automático entre DEFLATE, HEX e Base64
- Validação automática de PNG gerado

### ✅ Problema: Imagem corrompida

**Status**: ✅ **RESOLVIDO**

- Script verifica se arquivo é PNG válido
- Testa conectividade antes de gerar
- Múltiplos métodos de encoding como backup

### 🔧 Problemas Conhecidos e Soluções

**1. Arquivo .puml não encontrado**

```bash
cd src/
php artisan generate:uml
```

**2. Modelos não aparecem**

- Verificar se estão em `app/Models/`
- Testar com `php artisan tinker`

**3. Python não encontrado**

```bash
python3 --version
# Se necessário: sudo apt install python3
```

**4. Conectividade com PlantUML**

```bash
# Testar acesso manual
curl -I http://www.plantuml.com/plantuml/
```

## �📊 Exemplo de Diagrama

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

## 🔧 Troubleshooting

### Problema: "Bad URL" do PlantUML

**Sintomas:**

- Plugin PlantUML mostra erro de URL inválida
- Mensagem sobre codificação HUFFMAN
- Necessidade de adicionar `~1` à URL

**Solução:** ✅ Já implementada!

- Scripts atualizados para usar codificação DEFLATE
- URLs agora incluem prefixo `~1` automaticamente
- Geração de imagem funciona corretamente

### Problema: Arquivo .puml não encontrado

**Solução:**

```bash
# Certifique-se de executar na pasta src/
cd src/
php artisan generate:uml
```

### Problema: Modelos não aparecem no diagrama

**Possíveis causas:**

1. Modelo não está em `app/Models/`
2. Classe não pode ser instanciada
3. Não herda de `Illuminate\Database\Eloquent\Model`

**Solução:**

- Verifique se o modelo está no namespace correto
- Teste se `php artisan tinker` consegue carregar o modelo

### Problema: Python não encontrado

**Solução:**

```bash
# Verificar se Python está instalado
python3 --version

# Se não estiver instalado no sistema
sudo apt update && sudo apt install python3
```

### Problema: Imagem PNG corrompida

**Solução:**

1. Verifique se a URL gerada está funcionando
2. Teste a URL manualmente no navegador
3. Re-execute o comando: `npm run docs:uml`

## 💡 Dicas e Melhores Práticas

### Performance

- Execute `npm run docs:uml` apenas quando necessário
- Use o workflow do GitHub Actions para automatizar
- Considere cache para projetos com muitos modelos

### Personalização

- Modifique `GenerateUmlDiagram.php` para suas necessidades
- Adicione relacionamentos específicos do seu domínio
- Customize cores e temas no código PlantUML

### Versionamento

- Commit os arquivos `.puml` e `.png` no repositório
- Use `.gitignore` para excluir se desejar gerar sempre
- Considere usar releases para snapshots de diagramas

## 📚 Recursos Adicionais

- [Documentação PlantUML](https://plantuml.com/)
- [Guia de Diagramas de Classe](https://plantuml.com/class-diagram)
- [Laravel Model Documentation](https://laravel.com/docs/eloquent)
