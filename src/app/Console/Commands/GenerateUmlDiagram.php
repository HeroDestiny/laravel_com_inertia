<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionMethod;

final class GenerateUmlDiagram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:uml {--output=storage/uml/}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate UML class diagram for domain models';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating UML class diagram...');

        $outputPath = $this->option('output');
        $fullOutputPath = base_path($outputPath);

        // Ensure output directory exists
        if (! File::exists($fullOutputPath)) {
            File::makeDirectory($fullOutputPath, 0755, true);
        }

        // Get all model classes
        $models = $this->getModelClasses();

        if (empty($models)) {
            $this->warn('No model classes found in app/Models directory.');

            return Command::FAILURE;
        }

        // Generate PlantUML content
        $umlContent = $this->generatePlantUmlContent($models);

        // Save PlantUML file
        $pumlFile = $fullOutputPath.'domain-models.puml';
        File::put($pumlFile, $umlContent);

        $this->info('UML diagram generated successfully!');
        $this->info("PlantUML file: {$pumlFile}");

        $this->line('');
        $this->info('📋 How to view the diagram:');
        $this->info('  • Online PlantUML: https://www.plantuml.com/plantuml/uml/');
        $this->info('  • VS Code PlantUML extension: Ctrl+Alt+P');
        $this->info('  • Copy content and paste in online editor');
        $this->info('  • Edit source: '.$pumlFile);

        $this->line('');
        $this->info('🎯 Found '.count($models).' model(s): '.implode(', ', array_map(function ($class) {
            return (new ReflectionClass($class))->getShortName();
        }, $models)));

        return Command::SUCCESS;
    }

    /**
     * Get all model classes from the app/Models directory
     *
     * @return array<class-string>
     */
    private function getModelClasses(): array
    {
        $modelPath = app_path('Models');
        $models = [];

        if (! File::exists($modelPath)) {
            return $models;
        }

        $files = File::files($modelPath);

        foreach ($files as $file) {
            $className = 'App\\Models\\'.$file->getFilenameWithoutExtension();

            if (class_exists($className)) {
                $models[] = $className;
            }
        }

        return $models;
    }

    /**
     * Generate PlantUML content for the given models
     *
     * @param  array<class-string>  $models
     */
    private function generatePlantUmlContent(array $models): string
    {
        $content = "@startuml Domain Models\n";
        $content .= "!theme cerulean-outline\n";
        $content .= "skinparam classAttributeIconSize 0\n";
        $content .= "skinparam classFontStyle bold\n";
        $content .= "skinparam backgroundColor #FEFEFE\n";
        $content .= "skinparam class {\n";
        $content .= "  BackgroundColor #E8F4FD\n";
        $content .= "  BorderColor #1E88E5\n";
        $content .= "  HeaderBackgroundColor #1E88E5\n";
        $content .= "  HeaderFontColor #FFFFFF\n";
        $content .= "}\n\n";

        foreach ($models as $modelClass) {
            $content .= $this->generateClassUml($modelClass);
        }

        // Add relationships between models
        $content .= $this->generateRelationships($models);

        $content .= "\n@enduml";

        return $content;
    }

    /**
     * Generate UML for a single class
     */
    private function generateClassUml(string $className): string
    {
        $reflection = new ReflectionClass($className);
        $shortName = $reflection->getShortName();

        $content = "class {$shortName} {\n";

        // Add properties
        $properties = $this->getClassProperties($reflection);
        foreach ($properties as $property) {
            $content .= "  {$property}\n";
        }

        if (! empty($properties)) {
            $content .= "  --\n";
        }

        // Add methods
        $methods = $this->getClassMethods($reflection);
        foreach ($methods as $method) {
            $content .= "  {$method}\n";
        }

        $content .= "}\n\n";

        return $content;
    }

    /**
     * Get class properties for UML
     *
     * @param  ReflectionClass<object>  $reflection
     * @return array<string>
     */
    private function getClassProperties(ReflectionClass $reflection): array
    {
        $properties = [];

        // Get fillable properties from Laravel model
        if ($reflection->hasProperty('fillable')) {
            try {
                $instance = $reflection->newInstance();
                if (method_exists($instance, 'getFillable')) {
                    $fillable = $instance->getFillable();
                    foreach ($fillable as $field) {
                        $properties[] = "+ {$field}: string";
                    }
                }
            } catch (\Exception $e) {
                // Skip if can't instantiate
            }
        }

        // Add some common Laravel model properties
        $properties[] = '+ id: int';
        $properties[] = '+ created_at: timestamp';
        $properties[] = '+ updated_at: timestamp';

        return $properties;
    }

    /**
     * Get class methods for UML
     *
     * @param  ReflectionClass<object>  $reflection
     * @return array<string>
     */
    private function getClassMethods(ReflectionClass $reflection): array
    {
        $methods = [];
        $publicMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($publicMethods as $method) {
            // Skip magic methods and Laravel framework methods
            if (
                $method->isConstructor() ||
                strpos($method->getName(), '__') === 0 ||
                $method->getDeclaringClass()->getName() !== $reflection->getName()
            ) {
                continue;
            }

            $methodName = $method->getName();
            $methods[] = "+ {$methodName}()";
        }

        return $methods;
    }

    /**
     * Generate relationships between models
     *
     * @param  array<class-string>  $models
     */
    private function generateRelationships(array $models): string
    {
        $content = "\n' Relationships\n";

        // Simple example - you can enhance this based on your actual relationships
        $modelNames = array_map(function ($class) {
            return (new ReflectionClass($class))->getShortName();
        }, $models);

        // Example relationship
        if (in_array('User', $modelNames) && in_array('Paciente', $modelNames)) {
            $content .= "User ||--o{ Paciente : manages\n";
        }

        return $content;
    }
}
