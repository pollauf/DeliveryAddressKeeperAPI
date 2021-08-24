<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryCustomer extends Model
{
    protected $table = 'clientes_delivery';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
