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
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'issuance_normalized',
    ];

    /**
     * Issuance Normalized
     *
     * @return string
     */
    public function getIssuanceNormalizedAttribute()
    {
        return $this->divisible ? fromSatoshi($this->issuance) : sprintf("%.8f", (float)$this->issuance);
    }

    /**
     * Base Markets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function baseMarkets()
    {
        return $this->hasMany(Market::class, 'base_asset_id');
    }

    /**
     * Quote Markets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quoteMarkets()
    {
        return $this->hasMany(Market::class, 'quote_asset_id');
    }

    /**
     * Base Markets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function baseMarketsOrderMatches()
    {
        return $this->hasManyThrough(OrderMatch::class, Market::class, 'base_asset_id', 'market_id');
    }

    /**
     * Quote Markets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quoteMarketsOrderMatches()
    {
        return $this->hasManyThrough(OrderMatch::class, Market::class, 'quote_asset_id', 'market_id');
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

    /**
     * Scope a query to only include users of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubassets($query, $name)
    {
        return $query->where('long_name', 'like', "{$name}.%");
    }
}
