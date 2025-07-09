<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'status',
        'total_amount',
    ];

    public function productOrders()
    {
        return $this->hasMany(ProductOrder::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_orders')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}