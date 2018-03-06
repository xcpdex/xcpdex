<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'market_id', 'block_index', 'open', 'high', 'low', 'close', 'volume', 'price_usd',
    ];

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
}
