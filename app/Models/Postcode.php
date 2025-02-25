<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{
    protected $table = 'postcodes';
    protected $fillable = ['postcode', 'lat', 'lang'];
}
