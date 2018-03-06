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
         'base_asset_id', 'quote_asset_id', 'name', 'slug', 'base_volume', 'last_price_usd', 'quote_volume', 'quote_volume_usd', 'quote_market_cap', 'quote_market_cap_usd', 'open_orders_total', 'orders_total', 'order_matches_total', 'last_traded_at'
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'base_volume_normalized', 'quote_volume_normalized',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
         'last_traded_at',
    ];

    /**
     * Normalized Base Volume
     *
     * @return string
     */
    public function getBaseVolumeNormalizedAttribute()
    {
        return $this->baseAsset->divisible ? fromSatoshi($this->base_volume) : sprintf("%.8f", (float)$this->base_volume);
    }

    /**
     * Normalized Quote Volume
     *
     * @return string
     */
    public function getQuoteVolumeNormalizedAttribute()
    {
        return $this->quoteAsset->divisible ? fromSatoshi($this->quote_volume) : sprintf("%.8f", (float)$this->quote_volume);
    }

    /**
     * Charts
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function charts()
    {
        return $this->hasMany(Chart::class);
    }

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
        return $this->hasMany(OrderMatch::class)->whereStatus('completed');
    }

    /**
     * Last Order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastOrder()
    {
        return $this->hasOne(Order::class)->orderBy('tx_index', 'desc');
    }

    /**
     * Last Order Match
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastMatch()
    {
        return $this->hasOne(OrderMatch::class)->whereStatus('completed')->orderBy('tx_index', 'desc');
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
