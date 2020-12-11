<?php

namespace AlhajiAki\Textcus\Channels;

use AlhajiAki\Textcus\TextcusClient;
use Illuminate\Notifications\Notification;

class TextcusChannel
{
    /**
     * The Textcus client instance.
     *
     * @var \AlhajiAki\Textcus\TextcusClient
     */
    protected $textCus;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Create a new Textcus channel instance.
     *
     * @param  \AlhajiAki\Textcus\TextcusClient $textCus
     * @param  string  $from
     * @return void
     */
    public function __construct(TextcusClient $textCus, $from)
    {
        $this->textCus = $textCus;
        $this->from = $from;
    }

    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('textcus', $notification)) {
            return;
        }

        $message = $notification->toTextcus($notifiable);


        return $this->textCus->send(
            $message->from ?: $this->from,
            $to,
            trim($message->content)
        );
    }
}
