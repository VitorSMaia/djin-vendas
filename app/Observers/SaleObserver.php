<?php

namespace App\Observers;

use App\Models\Sale;

class SaleObserver
{
    // Stock validation and decrement are handled by SaleItemObserver.
    // This observer is kept for future cross-cutting concerns on Sale.
}
