<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','type','breed','name','weight','coat'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
