<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
    ];

    public function productOrders()
    {
        return $this->hasMany(ProductOrder::class);
    } 

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'products_orders')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}