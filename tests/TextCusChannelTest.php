<?php

namespace AlhajiAki\Textcus\Tests;

use AlhajiAki\Textcus\Channels\TextCusChannel;
use AlhajiAki\Textcus\Messages\TextCusMessage;
use AlhajiAki\Textcus\TextCusClient;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use \Orchestra\Testbench\TestCase as Orchestra;

class TextCusChannelTest extends Orchestra
{
    use MockeryPHPUnitIntegration;

    /** @test */
    public function sms_is_sent_via_textcus()
    {
        $notification = new NotificationTextCusChannelTestNotification;
        $notifiable = new NotificationTextCusChannelTestNotifiable;

        $channel = new TextCusChannel(
            $textcus = m::mock(TextCusClient::class),
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
        $notification = new NotificationTextCusChannelTestCustomFromNotification;
        $notifiable = new NotificationTextCusChannelTestNotifiable;

        $channel = new TextCusChannel(
            $textcus = m::mock(TextCusClient::class),
            '4444444444'
        );

        $textcus->shouldReceive('send')
            ->with('5554443333', '5555555555', 'this is my message')
            ->once();

        $channel->send($notifiable, $notification);
    }
}

class NotificationTextCusChannelTestNotifiable
{
    use Notifiable;

    public $mobile = '5555555555';

    public function routeNotificationForTextCus($notification)
    {
        return $this->mobile;
    }
}

class NotificationTextCusChannelTestNotification extends Notification
{
    public function toTextCus($notifiable)
    {
        return new TextCusMessage('this is my message');
    }
}

class NotificationTextCusChannelTestCustomFromNotification extends Notification
{
    public function toTextCus($notifiable)
    {
        return (new TextCusMessage('this is my message'))->from('5554443333');
    }
}
