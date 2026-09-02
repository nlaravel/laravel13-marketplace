<?php

namespace App\Enums;

enum InventoryTransactionType: string
{
    case STOCK_IN = 'stock_in';
    case SALE = 'sale';
    case RESERVATION = 'reservation';
    case RELEASE = 'release';
    case RETURN = 'return';
    case ADJUSTMENT = 'adjustment';
}
