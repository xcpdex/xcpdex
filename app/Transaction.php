<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'offset', 'tx_hash', 'tx_index', 'block_index', 'total', 'fee', 'cents', 'size', 'source', 'destination', 'data', 'tx_hex', 'processed', 'malformed', 'timestamp',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'timestamp',
    ];
}
