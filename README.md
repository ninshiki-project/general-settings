# Filament General Settings

Create really fast and easily general settings for your Laravel Filament project.

This is heavily customized for [ninshiki-project/Ninshiki-backend-community](https://github.com/ninshiki-project/Ninshiki-backend-community)

<div class="filament-hidden">

![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-general-settings/main/art/joaopaulolndev-filament-general-settings.jpg)

</div>


## Installation

You can install the package via composer:

```bash
composer require ninshiki-project/general-settings
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="general-settings-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="general-settings-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="general-settings-views"
```

Optionally, you can publish the translations using

```bash
php artisan vendor:publish --tag="general-settings-translations"
```

Optionally, you can publish the assets using.
Ex: to show images in default email providers.

```bash
php artisan vendor:publish --tag="general-settings-assets"
```

![Screenshot of Default Email Providers](https://raw.githubusercontent.com/joaopaulolndev/filament-general-settings/main/art/default_email_provider_images.png)

This is the contents of the published config file:

```php
return [
    'show_application_tab' => true,
    'show_analytics_tab' => true,
    'show_seo_tab' => true,
    'show_email_tab' => true,
    'show_social_networks_tab' => true,
    'expiration_cache_config_time' => 60,
];
```

Optionally, if you would like to add custom tabs and custom fields follow the example on configuration using the keys
`show_custom_tabs` and `custom_tabs`.

```php
use ninshikiProject\GeneralSettings\Enums\TypeFieldEnum;

return [
    'show_application_tab' => true,
    'show_analytics_tab' => true,
    'show_seo_tab' => true,
    'show_email_tab' => true,
    'show_social_networks_tab' => true,
    'expiration_cache_config_time' => 60,
    'show_custom_tabs'=> true,
    'custom_tabs' => [
        'more_configs' => [
            'label' => 'More Configs',
            'icon' => 'heroicon-o-plus-circle',
            'columns' => 1,
            'fields' => [
                'custom_field_1' => [
                    'type' => TypeFieldEnum::Text->value,
                    'label' => 'Custom Textfield 1',
                    'placeholder' => 'Custom Field 1',
                    'required' => true,
                    'rules' => 'required|string|max:255',
                ],
                'custom_field_2' => [
                    'type' => TypeFieldEnum::Select->value,
                    'label' => 'Custom Select 2',
                    'placeholder' => 'Select',
                    'required' => true,
                    'options' => [
                        'option_1' => 'Option 1',
                        'option_2' => 'Option 2',
                        'option_3' => 'Option 3',
                    ],
                ],
                'custom_field_3' => [
                    'type' => TypeFieldEnum::Textarea->value,
                    'label' => 'Custom Textarea 3',
                    'placeholder' => 'Textarea',
                    'rows' => '3',
                    'required' => true,
                ],
                'custom_field_4' => [
                    'type' => TypeFieldEnum::Datetime->value,
                    'label' => 'Custom Datetime 4',
                    'placeholder' => 'Datetime',
                    'seconds' => false,
                ],
                'custom_field_5' => [
                    'type' => TypeFieldEnum::Boolean->value,
                    'label' => 'Custom Boolean 5',
                    'placeholder' => 'Boolean'
                ],
            ]
        ],
    ]
];
```

### Enabling Logo and Favicon Feature

To enable the feature for choosing a logo and favicon within the application tab, you need the following steps:

1. Publish the migration file to add the `site_logo` and `site_favicon` fields to the general settings table (only if
   you have installed the package before this feature):

```bash
php artisan vendor:publish --tag="general-settings-migrations"
php artisan migrate
```

2. Publish the configuration file:

```bash
php artisan vendor:publish --tag="general-settings-config"
```

3. Open the published configuration file config/general-settings.php and set the following key to true:

```bash
return [
    // Other configuration settings...
    'show_logo_and_favicon' => true,
];
```

## Usage

Add in AdminPanelProvider.php

```php

use ninshikiProject\GeneralSettings\GeneralSettingsPlugin;

...

->plugins([
    GeneralSettingsPlugin::make()
])
```

if you want to show for specific parameters to sort, icon, title, navigation group, navigation label and can access, you
can use the following example:

```php
->plugins([
    GeneralSettingsPlugin::make()
        ->canAccess(fn() => auth()->user()->id === 1)
        ->setSort(3)
        ->setIcon('heroicon-o-cog')
        ->setNavigationGroup('Settings')
        ->setTitle('General Settings')
        ->setNavigationParentItem('Settings')
        ->setNavigationLabel('General Settings'),
    ])
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- Original Developer:  [João Paulo Leite Nascimento](https://github.com/joaopaulolndev)
- Original Package Repo: https://github.com/joaopaulolndev/filament-general-settings


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
