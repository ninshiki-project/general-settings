<?php

namespace ninshikiProject\GeneralSettings\Forms;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\Colors\Color;
use ninshikiProject\GeneralSettings\Enums\TypeFieldEnum;

class CustomForms
{
    public static function get(array $customFields): array
    {
        $fields = [];
        foreach ($customFields as $fieldKey => $field) {

            if ($field['type'] === TypeFieldEnum::Text) {

                $fields[] = TextInput::make($fieldKey)
                    ->label(__($field['label']))
                    ->placeholder(__($field['placeholder']))
                    ->required(array_key_exists('required', $field))
                    ->rules(array_key_exists('rules', $field) ? $field['rules'] : []);

            } elseif ($field['type'] === TypeFieldEnum::Boolean) {

                $fields[] = Checkbox::make($fieldKey)
                    ->label(__($field['label']));

            } elseif ($field['type'] === TypeFieldEnum::Toggle) {

                $fields[] = Toggle::make($fieldKey)
                    ->onColor(Color::Green)
                    ->offColor(Color::Red)
                    ->inline((array_key_exists('required', $field)) ? $field['inline'] : false)
                    ->rule(array_key_exists('rules', $field) ? $field['rules'] : [])
                    ->label(__($field['label']));

            } elseif ($field['type'] === TypeFieldEnum::Select) {

                $fields[] = Select::make($fieldKey)
                    ->label(__($field['label']))
                    ->placeholder(__($field['placeholder']))
                    ->options($field['options'])
                    ->required(array_key_exists('required', $field));

            } elseif ($field['type'] === TypeFieldEnum::Textarea) {

                $fields[] = Textarea::make($fieldKey)
                    ->label(__($field['label']))
                    ->placeholder(__($field['placeholder']))
                    ->rows($field['rows'])
                    ->required(array_key_exists('required', $field));
            } elseif ($field['type'] === TypeFieldEnum::Datetime) {

                $fields[] = DateTimePicker::make($fieldKey)
                    ->label(__($field['label']))
                    ->placeholder(__($field['placeholder']))
                    ->seconds(array_key_exists('required', $field));
            }
        }

        return $fields;
    }
}
