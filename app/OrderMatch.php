<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMatch extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'market_id', 'order_id', 'order_match_id', 'block_index', 'tx_index', 'status', 'base_quantity', 'quote_quantity', 'quote_quantity_usd', 'exchange_rate_usd',
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'base_quantity_normalized', 'quote_quantity_normalized',
    ];

    /**
     * Normalized Base
     *
     * @return string
     */
    public function getBaseQuantityNormalizedAttribute()
    {
        return $this->order->market->baseAsset->divisible ? fromSatoshi($this->base_quantity) : sprintf("%.8f", (float)$this->base_quantity);
    }

    /**
     * Normalized Quote
     *
     * @return string
     */
    public function getQuoteQuantityNormalizedAttribute()
    {
        return $this->order->market->quoteAsset->divisible ? fromSatoshi($this->quote_quantity) : sprintf("%.8f", (float)$this->quote_quantity);
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
     * Order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Order Match
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderMatch()
    {
        return $this->belongsTo(Order::class, 'order_match_id');
    }
}
