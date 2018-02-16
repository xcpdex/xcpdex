<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class OrderResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'block_index' => $this->block_index,
            'expire_index' => $this->expire_index,
            'tx_index' => $this->tx_index,
            'tx_hash' => $this->tx_hash,
            'source' => $this->source,
            'status' => $this->status,
            'type' => $this->type,
            'exchange_rate' => $this->exchange_rate,
            'base_quantity' => $this->base_quantity,
            'base_remaining' => $this->base_remaining,
            'base_remaining_normalized' => $this->base_remaining_normalized,
            'quote_quantity' => $this->quote_quantity,
            'quote_remaining' => $this->quote_remaining,
            'quote_remaining_normalized' => $this->quote_remaining_normalized,
            'fee_paid' => $this->fee_paid,
            'duration' => $this->duration,
        ];
    }
}
