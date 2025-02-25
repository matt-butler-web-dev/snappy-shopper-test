<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ShopType;

class Shop extends Model
{
    protected $table = 'shops';
    protected $fillable = ['name', 'postcode', 'lat', 'lang', 'opening_time',
        'closing_time', 'shop_type', 'max_delivery_km'
    ];

    public function shopType()
    {
        return $this->belongsTo(ShopType::class);
    }

    /**
     * Creates an additional select that is a calculation of the distance between the shop
     * and a provided lat/long pair
     */
    public function ScopeDistance($query,$fromLat,$fromLong)
    {
        // This will calculate the distance in km
        // if you want in miles use 3959 instead of 6371
        $raw = \DB::raw('ROUND ( ( 6371 * acos( cos( radians('.$fromLat.') ) * cos( radians( `lat` ) ) * cos( radians( `long` ) - radians('.$fromLong.') ) + sin( radians('.$fromLat.') ) * sin( radians( `lat` ) ) ) ) ) AS distance');
        return $query->select('*')->addSelect($raw)->orderBy('distance', 'ASC');
    }
}
