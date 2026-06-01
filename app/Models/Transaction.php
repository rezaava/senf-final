<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_id',
        'user_id',
        'organ_id',
        'operator_id',
        'price',
        'remain',
        'description',
        'status',
        'payment_id',
        'invoice_details',
        'transaction_id',
        'transaction_result',
    ];
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function organ()
    {
        return $this->belongsTo(Organ::class);
    }
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
    public function setInvoiceDetailsAttribute($value)
    {
        $this->attributes['invoice_details'] = serialize($value);
    }
    public function getInvoiceDetailsAttribute($value)
    {
        return unserialize($this->attributes['invoice_details']);
    }
    public function setTransactionResultAttribute($value)
    {
        $this->attributes['transaction_result'] = serialize($value);
    }
    public function getTransactionResultAttribute($value)
    {
        return unserialize($this->attributes['transaction_result']);
    }
}
