<?php

namespace ninshikiProject\GeneralSettings\Forms;

use Filament\Forms\Components\TextInput;
use ninshikiProject\GeneralSettings\Enums\SocialNetworkEnum;

class SocialNetworkFieldsForm
{
    public static function get(): array
    {
        $fields = [];
        foreach (SocialNetworkEnum::options() as $key => $value) {
            $fields[] = TextInput::make($key)
                ->label(ucfirst(strtolower($value)));
        }

        return $fields;
    }
}
