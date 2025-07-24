<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Disable middleware that might cause issues in tests
        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);

        // Ensure Vite manifest exists for tests
        $this->ensureViteManifestExists();
    }

    /**
     * Ensure that the Vite manifest.json file exists for testing
     */
    protected function ensureViteManifestExists(): void
    {
        $buildPath = public_path('build');
        $manifestPath = public_path('build/manifest.json');

        // Create build directory if it doesn't exist
        if (! File::exists($buildPath)) {
            File::makeDirectory($buildPath, 0755, true);
        }

        // Create a minimal manifest file if it doesn't exist
        if (! File::exists($manifestPath)) {
            $manifest = [
                'resources/js/app.ts' => [
                    'file' => 'assets/app-test.js',
                    'isEntry' => true,
                    'src' => 'resources/js/app.ts',
                ],
            ];

            File::put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));
        }
    }
}
