<?php

namespace App\Commands;

use Telegram\Bot\Commands\Command;

/**
 * Class TelegramMarketCommand
 */
class TelegramMarketCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'market';

    /**
     * @var string Command Description
     */
    protected $description = 'Market Data';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $update = $this->getUpdate();
        $market = trim($update->getMessage()->getText());

        try
        {
            $market = \App\Market::where('name', '=', $market)
                ->orWhere('slug', '=', $market)
                ->firstOrFail();

            $bid = $market->orders()->buys()->open()
                ->orderBy('exchange_rate', 'desc')
                ->orderBy('tx_index', 'desc')
                ->first();

            $ask = $market->orders()->sells()->open()
                ->orderBy('exchange_rate', 'asc')
                ->orderBy('tx_index', 'desc')
                ->first();

            $last = $market->lastMatch;

            $bid_price = $bid ? $bid->exchange_rate : '--------';
            $bid_price_usd = $bid ? $bid->exchange_rate_usd : '--------';
            $ask_price = $ask ? $ask->exchange_rate : '--------';
            $ask_price_usd = $ask ? $ask->exchange_rate_usd : '--------';
            $last_price = $last ? $last->order->exchange_rate : '--------';
            $last_price_usd = $last ? $last->order->exchange_rate_usd : '--------';

            $this->replyWithMessage(['text' => "Bid: {$bid_price}\nAsk: {$ask_price}\nLast: {$last_price}\n"]);

        }
        catch(\Exception $e)
        {
            $this->replyWithMessage(['text' => 'Error: Not Found']);
        }
    }
}