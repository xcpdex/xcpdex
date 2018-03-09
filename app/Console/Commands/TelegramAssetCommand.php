<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;

class TelegramAssetCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'asset';

    /**
     * @var string Command Description
     */
    protected $description = 'Asset Data';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $update = $this->getUpdate();
        $asset = trim(str_replace('/asset', '', $update->getMessage()->getText()));

        try
        {
            $asset = \App\Asset::where('name', '=', $asset)
                ->orWhere('long_name', '=', $asset)
                ->firstOrFail();

            $markets  = \App\Market::where('base_asset_id', '=', $asset->id)
                ->where('open_orders_total', '>', 0)
                ->where('order_matches_total', '>', 0)
                ->orWhere('quote_asset_id', '=', $asset->id)
                ->where('open_orders_total', '>', 0)
                ->where('order_matches_total', '>', 0)
                ->orderBy('quote_volume_usd_month', 'desc')
                ->orderBy('last_traded_at', 'desc')
                ->take(5)
                ->get();

            $link_to = url(route('assets.show', ['asset' => $asset->name]));

            if(count($markets))
            {
                $top_markets = '';

                foreach($markets as $market)
                {
                    $top_markets .= $market->name . "\n";
                }

                $this->replyWithMessage(['text' => "{$link_to}\n\n{$asset->display_name}\nIssuance:{$asset->issuance_normalized}\nDivisible:{$asset->divisible}\nLocked:{$asset->locked}\n\nTop Markets:\n{$top_markets}"]);
            }
            else
            {
                $this->replyWithMessage(['text' => "{$link_to}\n\n{$asset->display_name}\nIssuance:{$asset->issuance_normalized}\nDivisible:{$asset->divisible}\nLocked:{$asset->locked}\n\nTop Markets:\nNone Found"]);
            }
        }
        catch(\Exception $e)
        {
            $this->replyWithMessage(['text' => 'Error: Not Found']);
        }
    }
}