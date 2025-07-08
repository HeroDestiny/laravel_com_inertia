### 1. A05:2021 – Configuração Incorreta de Segurança

- **Justificativa:** A configuração do disco `local` no arquivo `config/filesystems.php` inclui a diretiva `'serve' => true` e aponta para `storage_path('app/private')`. O nome do diretório "private" sugere que o seu conteúdo não deve ser acessível publicamente. Se a diretiva `'serve' => true` for utilizada em conjunto com rotas que expõem o conteúdo deste disco sem a devida autenticação e autorização, arquivos privados poderiam ser expostos. Esta não é uma configuração padrão para o disco `local` do Laravel e requer atenção.
- **Evidência:**

  ```php
  // filepath: ..\laravel_com_inertia\src\config\filesystems.php
  // ...existing code...
  'disks' => [

      'local' => [
          'driver' => 'local',
          'root' => storage_path('app/private'),
          'serve' => true, // Esta diretiva pode ser arriscada se não gerenciada corretamente
          'throw' => false,
          'report' => false,
      ],

      'public' => [
  // ...existing code...
  ```

- **Solução:**

  1.  **Reavaliar a necessidade:** Determinar se é realmente necessário servir arquivos diretamente do diretório `storage/app/private` via HTTP.
  2.  **Remover ou alterar `'serve' => true`:** Se não for estritamente necessário, remover a linha `'serve' => true,` ou defini-la como `false`.
  3.  **Controle de Acesso Rigoroso:** Se for necessário servir arquivos deste disco, certificar-se de que qualquer rota que o faça utilize middlewares de autenticação e autorização robustos para proteger o acesso. Em vez de servir diretamente, criar um endpoint de controller que verifique as permissões antes de retornar o arquivo.

  **Sugestão de Código (se o acesso direto não for necessário):**

  ```php
  // filepath: ..\laravel_com_inertia\src\config\filesystems.php
  // ...existing code...
  'disks' => [

      'local' => [
          'driver' => 'local',
          'root' => storage_path('app/private'),
          // 'serve' => true, // Removido ou alterado para false
          'throw' => false,
          'report' => false,
      ],

      'public' => [
  // ...existing code...
  ```

### 2. A01:2021 – Quebra de Controle de Acesso

- **Justificativa:**
  1.  **Ambiguidade da Funcionalidade de Cadastro:** A rota `/cadastrar` (`routes/web.php`) está protegida pelos middlewares `auth` e `verified`, significando que apenas utilizadores autenticados e verificados podem acedê-la. No entanto, a página correspondente `resources/js/pages/Cadastrar.vue` tem o título "Criar Conta" e a descrição "Preencha seus dados abaixo para se registrar", o que sugere um formulário para registo de _novos utilizadores_. Esta discrepância pode levar a um controle de acesso inadequado dependendo da intenção real.
  2.  **Validação de Dados Sensíveis (Potencial):** O formulário em Cadastrar.vue coleta dados pessoais (Nome, Sobrenome, Data de Nascimento, CPF, Cargo, Escolaridade, Nome da Mãe, Email). Atualmente, a função `onSubmit` não envia dados para o backend. Se/quando esta funcionalidade for implementada, é crucial que o backend realize validação completa e rigorosa de todos os campos (e.g., formato do CPF, unicidade, etc.) e aplique as regras de negócio corretas. Apenas a validação `required` no frontend é insuficiente.
- **Evidência:**

  - Rota `/cadastrar` protegida por `auth`:

    ```php
    // filepath: ..\laravel_com_inertia\src\routes\web.php
    // ...existing code...
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return inertia('Dashboard');
        })->name('dashboard');

        Route::get('/cadastrar', function () { // Protegida por auth
            return inertia('Cadastrar');
        })->name('cadastrar');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    // ...existing code...
    ```

  - Interface do utilizador em Cadastrar.vue sugerindo registo de novo utilizador:
    ```vue
    // filepath: ..\laravel_com_inertia\src\resources\js\pages\Cadastrar.vue //
    ...existing code...
    <CardHeader>
      <CardTitle class="text-2xl">Criar Conta</CardTitle>
      <CardDescription>Preencha seus dados abaixo para se registrar.</CardDescription>
    </CardHeader>
    // ...existing code...
    ```
  - Coleta de dados no frontend e lógica de submissão atual:

    ```vue
    // filepath: ..\laravel_com_inertia\src\resources\js\pages\Cadastrar.vue
    // ...existing code...
    const form = ref<RegisterForm>({
      name: '',
      surname: '',
      birthdate: '',
      cpf: '',
      role: '',
      education: '',
      motherName: '',
      email: '',
    })

    const onSubmit = () => {
      console.log('Dados do formulário:', form.value) // Não envia para o backend

      toast('Cadastro realizado com sucesso!', {
        description: `Bem-vindo(a), ${form.value.name}!`
      })
    // ...existing code...
    ```

- **Solução:**

  1.  **Clarificar Propósito da Rota `/cadastrar`:**
      - **Se for para registo de novos utilizadores:** A rota `/cadastrar` deve ser movida para fora do grupo de middleware `auth`. Um novo controller, similar ao `app/Http/Controllers/Auth/RegisteredUserController.php`, deve ser criado para lidar com a criação de novos utilizadores.
      - **Se for para utilizadores autenticados adicionarem/editarem informações:** Renomear a rota e a página para refletir a sua verdadeira função (ex: "Detalhes Adicionais", "Completar Perfil"). O controller backend deve garantir que o utilizador autenticado só possa modificar os seus próprios dados ou dados para os quais tenha permissão.
  2.  **Implementar Validação Backend Robusta:** Quando a submissão do formulário Cadastrar.vue for implementada, criar um `FormRequest` ou usar o método `validate` no controller para validar todos os campos no servidor. Incluir regras específicas como validação de formato de CPF, datas, e-mails, etc.
      **Exemplo (se for para utilizador logado adicionar detalhes):**

      - Atualizar Cadastrar.vue para usar `useForm` do Inertia e submeter para uma rota POST.
      - Criar uma rota POST e um controller:

        ```php
        // filepath: ..\laravel_com_inertia\src\routes\web.php
        // ...existing code...
        use App\Http\Controllers\UserDetailController; // Adicionar este import

        Route::middleware(['auth', 'verified'])->group(function () {
            // ...
            Route::get('/cadastrar-detalhes', function () { // Rota GET renomeada para clareza
                return inertia('Cadastrar'); // A view pode precisar de ajuste no título/descrição
            })->name('user-details.create');

            Route::post('/cadastrar-detalhes', [UserDetailController::class, 'store'])->name('user-details.store'); // Rota POST
            // ...
        });
        ```

        ```php
        // filepath: app/Http/Controllers/UserDetailController.php
        // Novo arquivo
        namespace App\Http\Controllers;

        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Auth;
        // Supondo um modelo UserDetail ou que você adicionará campos ao modelo User
        // use App\Models\UserDetail;

        class UserDetailController extends Controller
        {
            public function store(Request $request)
            {
                $validatedData = $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'surname' => ['required', 'string', 'max:255'],
                    'birthdate' => ['required', 'date'],
                    'cpf' => ['required', 'string', /* Adicionar regra de validação de CPF, ex: 'cpf' */],
                    'role' => ['required', 'string', 'max:255'],
                    'education' => ['required', 'string', 'max:255'],
                    'motherName' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255'], // Validar se este email deve ser único ou se é o do utilizador
                ]);

                // Lógica para salvar os dados associados ao utilizador autenticado
                // Ex: Auth::user()->details()->create($validatedData);
                // ou Auth::user()->update($validatedData);

                // Exemplo de atualização no próprio utilizador (se os campos existirem no modelo User)
                // Auth::user()->update([
                //     'user_name' => $validatedData['name'], // Ajustar nomes dos campos conforme seu modelo
                //     'surname' => $validatedData['surname'],
                //     'birthdate' => $validatedData['birthdate'],
                //     'cpf' => $validatedData['cpf'],
                //     'role' => $validatedData['role'],
                //     'education' => $validatedData['education'],
                //     'mother_name' => $validatedData['motherName'],
                //     // O campo 'email' já é gerenciado pelo ProfileController, talvez não precise ser atualizado aqui
                // ]);

                return redirect()->route('user-details.create')->with('success', 'Detalhes salvos com sucesso!');
            }
        }
        ```

### 3. A07:2021 – Falhas de Identificação e Autenticação

- **Justificativa:** O uso de senhas fixas e fracas como `'password'` na factory de utilizadores (`database/factories/UserFactory.php`) e em diversos arquivos de teste (e.g., `tests/Feature/Auth/PasswordConfirmationTest.php`, `tests/Feature/Settings/PasswordUpdateTest.php`) é uma prática comum para facilitar o desenvolvimento e os testes. No entanto, existe o risco de que essas credenciais fracas sejam acidentalmente migradas ou usadas em ambientes de staging ou produção, ou que contas de teste com essas senhas sejam expostas. Embora o Laravel utilize hashing seguro para senhas, a senha original fraca ainda é um ponto de entrada se a conta for comprometida de outra forma ou se o banco de dados de desenvolvimento/teste vazar.
- **Evidência:**

  - Senha padrão na `UserFactory`:
    ```php
    // filepath: ..\laravel_com_inertia\src\database\factories\UserFactory.php
    // ...existing code...
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'), // Senha padrão
            'remember_token' => Str::random(10),
        ];
    }
    // ...existing code...
    ```
  - Uso de `'password'` em testes:

    ```php
    // filepath: ..\laravel_com_inertia\src\tests\Feature\Auth\PasswordConfirmationTest.php
    // ...existing code...
    public function test_password_can_be_confirmed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password', // Senha codificada no teste
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }
    // ...existing code...
    ```

- **Solução:**

  1.  **Isolamento de Ambientes:** A principal mitigação é garantir um isolamento rigoroso entre ambientes de desenvolvimento/teste e produção. Nunca usar o banco de dados de desenvolvimento/teste em produção.
  2.  **Políticas de Senha para Testes:** Embora `'password'` seja comum, para projetos de alta segurança, considerar usar senhas geradas aleatoriamente por `UserFactory` e recuperá-las nos testes quando necessário, ou usar uma variável de ambiente para a senha de teste padrão.
  3.  **Limpeza de Dados de Teste:** Certificar-se de que os seeders de produção não utilizem dados da `UserFactory` com senhas padrão.
  4.  **Conscientização:** Educar a equipa sobre os riscos de senhas fracas, mesmo em ambientes de teste, e a importância de não reutilizar essas práticas em produção.

  Para os testes e factories, manter `'password'` é geralmente aceitável pela conveniência, desde que as medidas de isolamento de ambiente sejam estritamente seguidas. Não há uma "correção" de código direta para isso que não complique os testes, mas é um risco de processo e configuração a ser gerenciado.

## 🐳 Análise: Docker vs DevContainer

### 📊 **Situação Atual**

Este projeto utiliza **DevContainer** otimizado para desenvolvimento, oferecendo:

- ✅ Ambiente padronizado e reproduzível
- ✅ VS Code integrado com extensões
- ✅ Debug PHP (Xdebug) configurado
- ✅ Performance nativa no desenvolvimento

### 🚀 **Quando Adicionar Docker**

#### **✅ RECOMENDADO para:**

1. **Deploy em Produção** - Garantir ambiente idêntico
2. **CI/CD Pipeline** - Automação de testes e deploy
3. **Equipes Grandes** - Padronização entre desenvolvedores
4. **Microservices** - Separação de responsabilidades

#### **❌ NÃO NECESSÁRIO para:**

1. **Desenvolvimento Local** - DevContainer já resolve
2. **Projetos Pequenos** - Complexidade desnecessária
3. **Deploy Tradicional** - Se usar hosting compartilhado

### 🎯 **Estratégia Recomendada**

**Fase 1 - Atual (DevContainer)**

```bash
# Desenvolvimento otimizado
npm run dev & php artisan serve
```

**Fase 2 - Produção (Docker)**

```yaml
# docker-compose.prod.yml
version: '3.8'
services:
  app:
    build: .
    ports:
      - '80:80'
    environment:
      - APP_ENV=production
```

**Fase 3 - CI/CD (GitHub Actions + Docker)**

```yaml
# .github/workflows/deploy.yml
- name: Build Docker Image
  run: docker build -t myapp .
- name: Deploy to Production
  run: docker push myapp
```

### 💡 **Conclusão**

- **Mantenha** o DevContainer para desenvolvimento
- **Adicione** Docker quando precisar de deploy profissional
- **Use** Laravel Sail se quiser experimentar Docker localmente
