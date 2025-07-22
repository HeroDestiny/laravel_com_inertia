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
  $env = file_get_contents('.env');
  $dbConnection = preg_match('/DB_CONNECTION=pgsql/', $env) ? 'OK' : 'ERRO';
  $dbHost = preg_match('/DB_HOST=postgres/', $env) ? 'OK' : 'ERRO';
  $dbDatabase = preg_match('/DB_DATABASE=laravel_inertia/', $env) ? 'OK' : 'ERRO';

  echo "   $dbConnection DB_CONNECTION=pgsql\n";
  echo "   $dbHost DB_HOST=postgres\n";
  echo "   $dbDatabase DB_DATABASE=laravel_inertia\n";
} else {
  echo "   ERRO Arquivo .env não encontrado\n";
}
echo "\n";

// 3. Testar conexão direta PostgreSQL
echo "3. Testando conexão PostgreSQL:\n";
try {
  $pdo = new PDO(
    'pgsql:host=postgres;port=5432;dbname=laravel_inertia',
    'laravel_user',
    'laravel_password',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );

  echo "   OK Conexão PostgreSQL estabelecida\n";

  // Testar query simples
  $stmt = $pdo->query('SELECT version()');
  $version = $stmt->fetchColumn();
  echo "   OK Versão PostgreSQL: " . substr($version, 0, 50) . "...\n";
} catch (PDOException $e) {
  echo "   ERRO na conexão: " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Testar configuração Laravel
echo "4. Testando configuração Laravel:\n";
if (file_exists('vendor/autoload.php')) {
  require_once 'vendor/autoload.php';

  try {
    // Simular carregamento do Laravel
    $app = require_once 'bootstrap/app.php';

    echo "   OK Laravel carregado\n";

    // Testar configuração database
    $config = $app->make('config');
    $dbConfig = $config->get('database.connections.pgsql');

    if ($dbConfig && $dbConfig['host'] === 'postgres') {
      echo "   OK Configuração database.php correta\n";
    } else {
      echo "   ERRO Configuração database.php incorreta\n";
    }
  } catch (Exception $e) {
    echo "   ERRO ao carregar Laravel: " . $e->getMessage() . "\n";
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
