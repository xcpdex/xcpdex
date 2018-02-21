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
         'block_index', 'block_hash', 'block_time',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
         'block_time',
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
     * Markets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function markets()
    {
        return $this->hasManyThrough(Market::class, Order::class, 'block_index', 'market_id', 'block_index');
    }
}
