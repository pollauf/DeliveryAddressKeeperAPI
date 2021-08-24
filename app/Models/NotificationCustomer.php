<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationCustomer extends Model
{
    protected $table = 'notif_cad_cli_delivery';

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
