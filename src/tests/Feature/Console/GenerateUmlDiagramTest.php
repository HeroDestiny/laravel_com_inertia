<?php

namespace Tests\Feature\Console;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class GenerateUmlDiagramTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();

    // Clean up any existing UML files
    $this->cleanupUmlFiles();
  }

  protected function tearDown(): void
  {
    // Clean up after tests
    $this->cleanupUmlFiles();

    parent::tearDown();
  }

  private function cleanupUmlFiles(): void
  {
    $umlPath = storage_path('uml');
    if (File::exists($umlPath)) {
      File::deleteDirectory($umlPath);
    }
  }

  public function test_can_generate_uml_diagram()
  {
    $this->artisan('generate:uml')
      ->expectsOutput('Generating UML class diagram...')
      ->expectsOutput('UML diagram generated successfully!')
      ->assertExitCode(0);

    // Check that the UML file was created
    $umlFile = storage_path('uml/domain-models.puml');
    $this->assertTrue(File::exists($umlFile));

    // Check that the file contains expected content
    $content = File::get($umlFile);
    $this->assertStringContainsString('@startuml', $content);
    $this->assertStringContainsString('@enduml', $content);
    $this->assertStringContainsString('class Paciente', $content);
    $this->assertStringContainsString('class User', $content);
  }

  public function test_can_generate_uml_diagram_with_custom_output_path()
  {
    $customPath = 'custom/uml/';

    $this->artisan('generate:uml', ['--output' => $customPath])
      ->expectsOutput('Generating UML class diagram...')
      ->expectsOutput('UML diagram generated successfully!')
      ->assertExitCode(0);

    // Check that the UML file was created in custom path
    $umlFile = base_path($customPath . 'domain-models.puml');
    $this->assertTrue(File::exists($umlFile));

    // Clean up custom directory
    $customDir = base_path('custom');
    if (File::exists($customDir)) {
      File::deleteDirectory($customDir);
    }
  }

  public function test_uml_diagram_contains_model_properties()
  {
    $this->artisan('generate:uml')->assertExitCode(0);

    $umlFile = storage_path('uml/domain-models.puml');
    $content = File::get($umlFile);

    // Check for Paciente model properties
    $this->assertStringContainsString('+ name: string', $content);
    $this->assertStringContainsString('+ surname: string', $content);
    $this->assertStringContainsString('+ email: string', $content);
    $this->assertStringContainsString('+ cpf: string', $content);

    // Check for User model properties
    $this->assertStringContainsString('+ name: string', $content);
    $this->assertStringContainsString('+ email: string', $content);
  }

  public function test_uml_diagram_contains_model_methods()
  {
    $this->artisan('generate:uml')->assertExitCode(0);

    $umlFile = storage_path('uml/domain-models.puml');
    $content = File::get($umlFile);

    // Check for some expected methods (these might vary based on your models)
    $this->assertStringContainsString('+ rules()', $content);
    $this->assertStringContainsString('+ getFullNameAttribute()', $content);
  }

  public function test_creates_output_directory_if_not_exists()
  {
    $customPath = 'non/existent/path/';

    $this->artisan('generate:uml', ['--output' => $customPath])
      ->assertExitCode(0);

    // Check that the directory was created
    $fullPath = base_path($customPath);
    $this->assertTrue(File::exists($fullPath));

    // Clean up
    $baseDir = base_path('non');
    if (File::exists($baseDir)) {
      File::deleteDirectory($baseDir);
    }
  }

  public function test_handles_no_models_gracefully()
  {
    // Temporarily rename the models directory to simulate no models
    $modelsPath = app_path('Models');
    $tempPath = app_path('Models_temp');

    if (File::exists($modelsPath)) {
      File::move($modelsPath, $tempPath);
    }

    try {
      $this->artisan('generate:uml')
        ->expectsOutput('No model classes found in app/Models directory.')
        ->assertExitCode(1);
    } finally {
      // Restore the models directory
      if (File::exists($tempPath)) {
        File::move($tempPath, $modelsPath);
      }
    }
  }
}
