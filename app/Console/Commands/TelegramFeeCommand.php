<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;

class TelegramFeeCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'fee';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['fees', 'f'];

    /**
     * @var string Command Description
     */
    protected $description = 'Avg. Fee (24h)';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $update = $this->getUpdate();

        try
        {
            $avg_fee = \App\Order::where('block_index', '>', \Cache::get('block_height') - 144)->avg('fee_paid');
            $avg_fee = fromSatoshi($avg_fee);

            $this->replyWithMessage(['text' => "{$avg_fee} BTC"]);
        }
        catch(\Exception $e)
        {
            $this->replyWithMessage(['text' => 'Error: Not Found']);
        }
    }
}