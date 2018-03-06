<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'market_id', 'block_index', 'expire_index', 'tx_index', 'tx_hash', 'source', 'status', 'type', 'base_quantity', 'base_remaining', 'quote_quantity', 'quote_remaining', 'exchange_rate', 'exchange_rate_usd', 'fee_paid', 'duration',
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'base_remaining_normalized', 'quote_remaining_normalized',
        'base_quantity_normalized', 'quote_quantity_normalized',
    ];

    /**
     * Normalized Base
     *
     * @return string
     */
    public function getBaseRemainingNormalizedAttribute()
    {
        return $this->market->baseAsset->divisible ? fromSatoshi($this->base_remaining) : sprintf("%.8f", (float)$this->base_remaining);
    }

    /**
     * Normalized Quote
     *
     * @return string
     */
    public function getQuoteRemainingNormalizedAttribute()
    {
        return $this->market->quoteAsset->divisible ? fromSatoshi($this->quote_remaining) : sprintf("%.8f", (float)$this->quote_remaining);
    }

    /**
     * Normalized Base
     *
     * @return string
     */
    public function getBaseQuantityNormalizedAttribute()
    {
        return $this->market->baseAsset->divisible ? fromSatoshi($this->base_quantity) : sprintf("%.8f", (float)$this->base_quantity);
    }

    /**
     * Normalized Quote
     *
     * @return string
     */
    public function getQuoteQuantityNormalizedAttribute()
    {
        return $this->market->quoteAsset->divisible ? fromSatoshi($this->quote_quantity) : sprintf("%.8f", (float)$this->quote_quantity);
    }

    /**
     * Block
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function block()
    {
        return $this->belongsTo(Block::class, 'block_index', 'block_index');
    }

    /**
     * Market
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function market()
    {
        return $this->belongsTo(Market::class);
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
     * Order Matches Reverse
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderMatchesReverse()
    {
        return $this->hasMany(OrderMatch::class, 'order_match_id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        $block_height = \Cache::get('block_height', 0);

        return $query->whereStatus('open')->where('expire_index', '>', $block_height);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBuys($query)
    {
        return $query->whereType('buy');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSells($query)
    {
        return $query->whereType('sell');
    }
}
