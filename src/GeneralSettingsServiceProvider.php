<?php

namespace ninshikiProject\GeneralSettings;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use ninshikiProject\GeneralSettings\Commands\FilamentGeneralSettingsCommand;
use ninshikiProject\GeneralSettings\Testing\TestsFilamentGeneralSettings;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GeneralSettingsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'general-settings';

    public static string $viewNamespace = 'general-settings';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations();
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }

        if (file_exists($package->basePath('/../resources/dist'))) {
            $package->hasAssets();
        }
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentGeneralSettingsCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_general-settings_table',
            'add_logo_favicon_columns_to_general_settings_table',
        ];
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        //        FilamentAsset::register(
        //            $this->getAssets(),
        //            $this->getAssetPackageName()
        //        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/{$this->packageName()}/{$file->getFilename()}"),
                ], 'general-settings-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentGeneralSettings);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    protected function getAssetPackageName(): ?string
    {
        return 'ninshikiProject/general-settings';
    }

    protected function packageName(): string
    {
        return static::$name;
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-general-settings', __DIR__ . '/../resources/dist/components/filament-general-settings.js'),
            Css::make('general-settings-styles', __DIR__ . '/../resources/dist/general-settings.css'),
            Js::make('general-settings-scripts', __DIR__ . '/../resources/dist/general-settings.js'),
        ];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }
}
