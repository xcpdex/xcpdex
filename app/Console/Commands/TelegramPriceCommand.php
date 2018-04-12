<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;

class TelegramPriceCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'price';

    /**
     * @var string Command Description
     */
    protected $description = 'Market Price (XCP, BTC)';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $update = $this->getUpdate();
        $asset = explode(' ', $update->getMessage()->getText());

        if(isset($asset[1]))
        {
            $asset = $asset[1];
        }
        else
        {
            $this->replyWithMessage(['text' => 'Error: No Asset Provided']);
            return true;
        }

        try
        {
            $asset = \App\Asset::where('name', '=', $asset)
                ->orWhere('long_name', '=', $asset)
                ->firstOrFail();

            $history = $asset->histories()
                ->whereType('price')
                ->orderBy('reported_at', 'desc')
                ->first();

            $price = fromSatoshi($history->value);

            $this->replyWithMessage(['text' => '$' . $price]);
        }
        catch(\Exception $e)
        {
            $this->replyWithMessage(['text' => 'Error: Not Found']);
        }
    }
}