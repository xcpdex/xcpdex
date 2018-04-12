<?php

namespace App\Console\Commands;

use Telegram\Bot\Commands\Command;

class TelegramImageCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'image';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['i', 'p', 'photo', 'art'];

    /**
     * @var string Command Description
     */
    protected $description = 'Show Image';

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
            $asset = \App\Asset::whereNotNull('image_url')->inRandomOrder()->first()->name;
        }

        try
        {
            $asset = \App\Asset::where('name', '=', $asset)
                ->orWhere('long_name', '=', $asset)
                ->firstOrFail();

            if($asset->image_url)
            {
                if(substr($asset->image_url, -3) === 'gif')
                {
                    $this->replyWithDocument(['document' => $asset->image_url]);
                }
                else
                {
                    $this->replyWithPhoto(['photo' => $asset->image_url]);
                }
            }
            elseif($asset->icon_url)
            {
                if(substr($asset->icon_url, -3) === 'gif')
                {
                    $this->replyWithDocument(['document' => $asset->icon_url]);
                }
                else
                {
                    $this->replyWithPhoto(['photo' => $asset->icon_url]);
                }
            }
            else
            {
                $this->replyWithMessage(['text' => "Not Available"]);
            }
        }
        catch(\Exception $e)
        {
            $this->replyWithMessage(['text' => 'Error: Not Found']);
        }
    }
}