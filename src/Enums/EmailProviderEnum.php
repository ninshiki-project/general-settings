<?php

namespace ninshikiProject\GeneralSettings\Enums;

use ninshikiProject\GeneralSettings\Traits\WithOptions;

enum EmailProviderEnum: string
{
    use WithOptions;

    case SMTP = 'SMTP';
    case MAILGUN = 'Mailgun';
    case SES = 'Amazon SES';
    case POSTMARK = 'Postmark';
}
