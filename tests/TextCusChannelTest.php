<?php

namespace AlhajiAki\Textcus\Tests;

use AlhajiAki\Textcus\Channels\TextcusChannel;
use AlhajiAki\Textcus\Messages\TextcusMessage;
use AlhajiAki\Textcus\TextcusClient;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use \Orchestra\Testbench\TestCase as Orchestra;

class TextcusChannelTest extends Orchestra
{
    use MockeryPHPUnitIntegration;

    /** @test */
    public function sms_is_sent_via_textcus()
    {
        $notification = new NotificationTextcusChannelTestNotification;
        $notifiable = new NotificationTextcusChannelTestNotifiable;

        $channel = new TextcusChannel(
            $textcus = m::mock(TextcusClient::class),
            '4444444444'
        );

        $textcus->shouldReceive('send')
            ->with('4444444444', '5555555555', 'this is my message')
            ->once();

        $channel->send($notifiable, $notification);
    }

    /** @test */
    public function sms_is_sent_via_textcus_with_custom_from()
    {
        $notification = new NotificationTextcusChannelTestCustomFromNotification;
        $notifiable = new NotificationTextcusChannelTestNotifiable;

        $channel = new TextcusChannel(
            $textcus = m::mock(TextcusClient::class),
            '4444444444'
        );

        $textcus->shouldReceive('send')
            ->with('5554443333', '5555555555', 'this is my message')
            ->once();

        $channel->send($notifiable, $notification);
    }
}

class NotificationTextcusChannelTestNotifiable
{
    use Notifiable;

    public $mobile = '5555555555';

    public function routeNotificationForTextcus($notification)
    {
        return $this->mobile;
    }
}

class NotificationTextcusChannelTestNotification extends Notification
{
    public function toTextcus($notifiable)
    {
        return new TextcusMessage('this is my message');
    }
}

class NotificationTextcusChannelTestCustomFromNotification extends Notification
{
    public function toTextcus($notifiable)
    {
        return (new TextcusMessage('this is my message'))->from('5554443333');
    }
}
