<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'base_asset_id', 'quote_asset_id', 'name', 'slug',
    ];

    /**
     * Orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Order Matches
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderMatches()
    {
        return $this->hasMany(OrderMatch::class);
    }

    /**
     * Base Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function baseAsset()
    {
        return $this->belongsTo(Asset::class, 'base_asset_id');
    }

    /**
     * Quote Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quoteAsset()
    {
        return $this->belongsTo(Asset::class, 'quote_asset_id');
    }
}
