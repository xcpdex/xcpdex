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
         'name', 'long_name', 'slug', 'description', 'icon_url', 'image_url', 'issuance', 'divisible', 'locked', 'volume_total_usd', 'orders_total', 'order_matches_total', 'enhanced', 'processed', 'meta->description', 'meta->template', 'meta->asset_url', 'meta->image_url', 'meta->icon_url', 'meta->name', 'meta->team', 'meta->series', 'meta->number', 'meta->burned',
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'display_name', 'display_description', 'display_icon_url', 'issuance_normalized',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Display Name
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->long_name ? $this->long_name : $this->name;
    }

    /**
     * Display Description
     *
     * @return string
     */
    public function getDisplayDescriptionAttribute()
    {
        return isset($this->meta['description']) ? $this->meta['description'] : $this->description;
    }

    /**
     * Display Icon URL
     *
     * @return string
     */
    public function getDisplayIconUrlAttribute()
    {
        return isset($this->icon_url) ? $this->icon_url : asset('/img/token.png');
    }

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
     * History
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories()
    {
        return $this->hasMany(History::class);
    }

    /**
     * Projects
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * USD Price
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usdPrice()
    {
        return $this->hasOne(History::class)->whereType('price')->latest('timestamp');
    }

    /**
     * USD Market Cap
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usdMarketCap()
    {
        return $this->hasOne(History::class)->whereType('market_cap')->latest('timestamp');
    }

    /**
     * USD Volume
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usdVolume()
    {
        return $this->hasOne(History::class)->whereType('volume')->latest('timestamp');
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
     * Base Markets Orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function baseMarketsOrders()
    {
        return $this->hasManyThrough(Order::class, Market::class, 'base_asset_id', 'market_id');
    }

    /**
     * Quote Markets Orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quoteMarketsOrders()
    {
        return $this->hasManyThrough(Order::class, Market::class, 'quote_asset_id', 'market_id');
    }

    /**
     * Base Markets Order Matches
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function baseMarketsOrderMatches()
    {
        return $this->hasManyThrough(OrderMatch::class, Market::class, 'base_asset_id', 'market_id')->whereStatus('completed');
    }

    /**
     * Quote Markets Order Matches
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quoteMarketsOrderMatches()
    {
        return $this->hasManyThrough(OrderMatch::class, Market::class, 'quote_asset_id', 'market_id')->whereStatus('completed');
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
