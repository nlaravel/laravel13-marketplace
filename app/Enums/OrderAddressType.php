<?php

namespace App\Enums;

enum OrderAddressType: string
{
    case SHIPPING = 'shipping';
    case BILLING = 'billing';
}
