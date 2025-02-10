<?php

namespace ninshikiProject\GeneralSettings\Enums;

use ninshikiProject\GeneralSettings\Traits\WithOptions;

enum TypeFieldEnum: string
{
    use WithOptions;

    case Text = 'text';
    case Boolean = 'boolean';
    case Select = 'select';
    case Textarea = 'textarea';
    case Datetime = 'datetime';
    case Toggle = 'toggle';
}
