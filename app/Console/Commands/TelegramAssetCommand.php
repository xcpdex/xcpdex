<?php

namespace App\Commands;

use Telegram\Bot\Commands\Command;

/**
 * Class TelegramAssetCommand
 */
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
        $asset = $update->getMessage()->getText();
        $this->replyWithMessage(['text' => $asset]);
    }
}