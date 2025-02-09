<?php

namespace ninshikiProject\GeneralSettings\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ApplicationFieldsForm
{
    public static function get(): array
    {
        return [
            TextInput::make('site_name')
                ->label(__('general-settings::default.site_name'))
                ->autofocus(),
            Textarea::make('site_description')
                ->label(__('general-settings::default.site_description'))
                ->columnSpanFull(),
            Grid::make()->schema([
                FileUpload::make('site_logo')
                    ->label(fn () => __('general-settings::default.site_logo'))
                    ->image()
                    ->directory('assets')
                    ->visibility('public')
                    ->moveFiles()
                    ->imageEditor()
                    ->getUploadedFileNameForStorageUsing(fn () => 'site_logo.png')
                    ->columnSpan(2),
                FileUpload::make('site_favicon')
                    ->label(fn () => __('general-settings::default.site_favicon'))
                    ->image()
                    ->directory('assets')
                    ->visibility('public')
                    ->moveFiles()
                    ->getUploadedFileNameForStorageUsing(fn () => 'site_favicon.ico')
                    ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon'])
                    ->columnSpan(2),
            ])
                ->columns(4)
                ->visible(fn () => config('general-settings.show_logo_and_favicon')),
        ];
    }
}
