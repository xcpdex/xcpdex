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
         'order_id', 'order_match_id', 'base_quantity', 'quote_quantity',
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
