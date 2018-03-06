<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'block_index', 'block_hash', 'previous_block_hash', 'difficulty', 'block_time', 'mined_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
         'mined_at',
    ];

    /**
     * Orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'block_index', 'block_index');
    }


    /**
     * Order Matches
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderMatches()
    {
        return $this->hasMany(OrderMatch::class, 'block_index', 'block_index');
    }

    /**
     * Markets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function markets()
    {
        return $this->hasManyThrough(Market::class, Order::class, 'block_index', 'market_id', 'block_index');
    }
}
