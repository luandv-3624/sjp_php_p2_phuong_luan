<?php

namespace App\Enums;

enum UsersSortBy: string
{
    case NAME = 'name';
    case EMAIL = 'email';
    case PHONE_NUMBER = 'phone_number';
    case STATUS = 'status';
    case ROLE_ID = 'role_id';
}
