<?php

namespace App\Exceptions;

use App\Models\Ingredient;
use App\Models\Product;

class InsufficientStockException extends \RuntimeException
{
    public function __construct(public Product|Ingredient $item)
    {
        parent::__construct("Insufficient stocks for {$item->name}. ");
    }
}
