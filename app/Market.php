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
     * Last Open Buy Order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function highestOpenBuyOrder()
    {
        return $this->hasOne(Order::class)->buys()->open()->orderBy('exchange_rate', 'desc');
    }

    /**
     * Last Open Sell Order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lowestOpenSellOrder()
    {
        return $this->hasOne(Order::class)->sells()->open()->orderBy('exchange_rate', 'asc');
    }

    /**
     * Open Orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function openOrders()
    {
        return $this->hasMany(Order::class)->open();
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
     * Last Order Match
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastMatch()
    {
        return $this->hasOne(OrderMatch::class)->orderBy('tx_index', 'desc');
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
