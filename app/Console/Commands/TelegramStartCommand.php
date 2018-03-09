<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;

class TelegramStartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['signup'];

    /**
     * @var string Command Description
     */
    protected $description = 'Start Command to get you started';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $update = $this->getUpdate();
        $text = "Hello, Welcome to the xcp dex bot!\nType /help to get a list of available commands.";
        $this->replyWithMessage(['text' => $text]);
    }
}