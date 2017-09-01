<?php

namespace Librarian\Charging\Domain;


use MyCLabs\Enum\Enum;

class AccountState extends Enum
{
    const ACTIVE = 1;
    const INACTIVE = 2;
}