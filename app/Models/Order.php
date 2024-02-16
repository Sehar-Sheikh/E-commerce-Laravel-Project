<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['subtotal','grand_total','shipping','user_id','first_name', 'last_name','email','country_id','mobile','address','apartment','city','state','zip'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class);
    }
}
