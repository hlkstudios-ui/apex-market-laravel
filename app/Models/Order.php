<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['number', 'status', 'name', 'email', 'phone', 'address', 'city', 'postal_code', 'country', 'subtotal', 'shipping', 'total'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
