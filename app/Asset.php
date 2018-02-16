<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'name', 'long_name', 'description', 'issuance', 'divisible', 'locked', 'processed',
    ];

    /**
     * Markets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function markets()
    {
        return $this->hasMany(Market::class, 'base_asset_id');
    }

    /**
     * Orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function orders()
    {
        return $this->hasManyThrough(Order::class, Market::class);
    }
}
