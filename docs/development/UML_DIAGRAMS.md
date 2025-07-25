# 📊 Diagramas UML - Sistema Automático

Este projeto inclui geração automática de diagramas UML a partir dos models Laravel.

## 🚀 Como Usar

### Gerar Diagramas

```bash
# Via Artisan (gera apenas .puml)
php artisan generate:uml

# Via npm (gera .puml + .png)
npm run docs:uml

# Formatos específicos
npm run docs:uml:png         # Apenas PNG
npm run docs:uml:svg         # Apenas SVG
npm run docs:uml:pdf         # Apenas PDF
npm run docs:uml:all         # Todos os formatos
```

### Visualizar Online

```bash
# Lista formatos disponíveis
npm run docs:uml:formats

# Diagnóstico do ambiente
npm run docs:uml:check
```

## 📁 Arquivos Gerados

- `storage/uml/domain-models.puml` - Código fonte PlantUML
- `storage/uml/domain-models.png` - Imagem PNG
- `storage/uml/domain-models.svg` - Imagem SVG (vetorial)
- `storage/uml/domain-models.pdf` - Documento PDF
- `storage/uml/domain-models.eps` - Imagem EPS (vetorial)
- `storage/uml/domain-models.txt` - ASCII art

## 📖 Documentação Técnica

Para informações detalhadas sobre implementação, scripts e personalização, consulte:
**[UML Scripts - Documentação Técnica](./UML_SCRIPTS.md)**

## ⚙️ Sistema de Encoding

O sistema usa múltiplos métodos de encoding com fallback automático:

1. **Deflate + Base64** (padrão PlantUML)
2. **Base64 simples** (fallback)
3. **URL encoding** (último recurso)

Isso garante máxima compatibilidade com o PlantUML Online.

## 🔧 Personalização

### Models Suportados

O sistema analisa automaticamente todos os models em `app/Models/` e gera:

- Propriedades (campos do banco)
- Métodos públicos
- Relacionamentos
- Tipos de dados

### Exemplo de Output

```plantuml
@startuml Domain Models

class User {
  +id: bigint
  +name: string
  +email: string
  +email_verified_at: timestamp
  +password: string
  +remember_token: string
  +created_at: timestamp
  +updated_at: timestamp
}

class Paciente {
  +id: bigint
  +nome: string
  +cpf: string
  +telefone: string
  +email: string
  +created_at: timestamp
  +updated_at: timestamp
}

@enduml
```

## 🔄 Integração CI/CD

O GitHub Actions gera diagramas automaticamente:

```yaml
- name: Generate UML Diagrams
  run: |
    cd src
    npm run docs:uml
    
- name: Upload UML Artifacts
  uses: actions/upload-artifact@v3
  with:
    name: uml-diagrams
    path: src/storage/uml/
```

## 🛠️ Dependências

- **PHP**: Laravel Artisan para comando básico
- **Python**: Script avançado com validação (opcional)
- **Node.js**: Integração com npm scripts

## 🔍 Troubleshooting

### Problemas Comuns

1. **Diagramas não estão sendo gerados**
   ```bash
   # Verificar se os models existem
   ls src/app/Models/
   
   # Verificar permissões
   chmod +x scripts/generate-uml.sh
   ```

2. **Problemas com encoding**
   ```bash
   # Testar Python
   python3 -c "import zlib, base64; print('OK')"
   
   # Verificar sistema UML
   python3 scripts/check_uml_system.py
   ```

3. **Problemas ao abrir PlantUML Online**
   - Verificar conectividade com internet
   - URL pode estar muito longa (usar encoding alternativo)

## 📈 Monitoramento

O sistema monitora:
- ✅ Geração bem-sucedida de arquivos
- ✅ Validação do encoding
- ✅ Conectividade com PlantUML Online
- ✅ Tamanho dos diagramas gerados

## 🎯 Próximos Passos

- [ ] Suporte a relacionamentos entre models
- [ ] Geração de diagramas de sequência
- [ ] Integração com documentação automática
- [ ] Cache de diagramas para desempenho

---

**📊 Sistema robusto com múltiplos encoders e fallback automático**
