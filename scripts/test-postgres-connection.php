<?php

/**
 * Script para testar conectividade PostgreSQL
 * Execute depois do rebuild do DevContainer
 */

echo "Testando conectividade PostgreSQL...\n\n";

// 1. Verificar extensões PHP
echo "1. Verificando extensões PHP:\n";
$extensions = ['pdo', 'pdo_pgsql', 'pgsql'];
foreach ($extensions as $ext) {
  if (extension_loaded($ext)) {
    echo "   OK $ext\n";
  } else {
    echo "   ERRO $ext (não carregada)\n";
  }
}
echo "\n";

// 2. Verificar configuração Laravel
echo "2. Verificando configuração Laravel:\n";
if (file_exists('.env')) {
  // Carregar .env de forma segura usando parse_ini_file
  $env = parse_ini_file('.env', false, INI_SCANNER_RAW);

  if ($env === false) {
    echo "   ERRO Não foi possível ler o arquivo .env\n";
  } else {
    $dbConnection = isset($env['DB_CONNECTION']) && $env['DB_CONNECTION'] === 'pgsql' ? 'OK' : 'ERRO';
    $dbHost = isset($env['DB_HOST']) && $env['DB_HOST'] === 'postgres' ? 'OK' : 'ERRO';
    $dbDatabase = isset($env['DB_DATABASE']) && $env['DB_DATABASE'] === 'laravel_inertia' ? 'OK' : 'ERRO';

    echo "   $dbConnection DB_CONNECTION=pgsql\n";
    echo "   $dbHost DB_HOST=postgres\n";
    echo "   $dbDatabase DB_DATABASE=laravel_inertia\n";
  }
} else {
  echo "   ERRO Arquivo .env não encontrado\n";
}
echo "\n";

// 3. Testar conexão direta PostgreSQL
echo "3. Testando conexão PostgreSQL:\n";
try {
  // Usar variáveis de ambiente ou valores seguros
  $host = $_ENV['DB_HOST'] ?? 'postgres';
  $port = $_ENV['DB_PORT'] ?? '5432';
  $database = $_ENV['DB_DATABASE'] ?? 'laravel_inertia';
  $username = $_ENV['DB_USERNAME'] ?? 'laravel_user';
  $password = $_ENV['DB_PASSWORD'] ?? 'laravel_password';

  $dsn = sprintf(
    'pgsql:host=%s;port=%s;dbname=%s',
    $host,
    $port,
    $database
  );

  $pdo = new PDO(
    $dsn,
    $username,
    $password,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_TIMEOUT => 10
    ]
  );

  echo "   OK Conexão PostgreSQL estabelecida\n";

  // Testar query simples com prepared statement
  $stmt = $pdo->prepare('SELECT version() as version');
  $stmt->execute();
  $result = $stmt->fetch();

  if ($result && isset($result['version'])) {
    $version = substr($result['version'], 0, 50);
    echo "   OK Versão PostgreSQL: " . htmlspecialchars($version, ENT_QUOTES, 'UTF-8') . "...\n";
  }

  $pdo = null; // Fechar conexão
} catch (PDOException $e) {
  // Não expor detalhes sensíveis da conexão
  echo "   ERRO na conexão: Falha ao conectar com PostgreSQL\n";
  error_log("PostgreSQL connection error: " . $e->getMessage());
}
echo "\n";

// 4. Testar configuração Laravel
echo "4. Testando configuração Laravel:\n";
if (file_exists('vendor/autoload.php')) {
  try {
    require_once 'vendor/autoload.php';

    // Verificar se o bootstrap existe antes de incluir
    if (!file_exists('bootstrap/app.php')) {
      throw new Exception('Arquivo bootstrap/app.php não encontrado');
    }

    $app = require_once 'bootstrap/app.php';
    echo "   OK Laravel carregado\n";

    // Testar configuração database de forma segura
    if (method_exists($app, 'make')) {
      $config = $app->make('config');
      $dbConfig = $config->get('database.connections.pgsql');

      if (is_array($dbConfig) && isset($dbConfig['host']) && $dbConfig['host'] === 'postgres') {
        echo "   OK Configuração database.php correta\n";
      } else {
        echo "   ERRO Configuração database.php incorreta\n";
      }
    } else {
      echo "   ERRO Método make não disponível na instância da aplicação\n";
    }
  } catch (Exception $e) {
    echo "   ERRO ao carregar Laravel: Falha na inicialização\n";
    error_log("Laravel loading error: " . $e->getMessage());
  }
} else {
  echo "   ERRO Dependências Laravel não instaladas (composer install)\n";
}
echo "\n";

echo "Teste concluído!\n";
echo "\nPróximos passos após o rebuild:\n";
echo "1. Execute: php artisan migrate:fresh --seed\n";
echo "2. Execute: php artisan test\n";
echo "3. Execute: php artisan serve --host=0.0.0.0\n";
