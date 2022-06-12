<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'company', 'country',
        'street_address', 'town', 'region', 'post_code',
        'phone', 'email', 'remark',
    ];
}
