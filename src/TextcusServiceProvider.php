<?php

namespace AlhajiAki\Textcus;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class TextcusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('textcus', function ($app) {
                return new Channels\TextCusChannel(
                    $this->app->make(TextCusClient::class),
                    $this->app['config']['services.textcus.sender_id']
                );
            });
        });
    }
}
