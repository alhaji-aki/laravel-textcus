<?php

namespace AlhajiAki\Textcus\Channels;

use AlhajiAki\Textcus\TextCusClient;
use Illuminate\Notifications\Notification;

class TextCusChannel
{
    /**
     * The TextCus client instance.
     *
     * @var \AlhajiAki\TextCus\TextCusClient
     */
    protected $textCus;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Create a new TextCus channel instance.
     *
     * @param  \AlhajiAki\TextCus\TextCusClient $textCus
     * @param  string  $from
     * @return void
     */
    public function __construct(TextCusClient $textCus, $from)
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
