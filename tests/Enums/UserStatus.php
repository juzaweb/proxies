<?php

namespace Juzaweb\Modules\Admin\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case BANNED = 'banned';
    case VERIFICATION = 'verification';
}
