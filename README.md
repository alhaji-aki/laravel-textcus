# Textcus notifications channel for Laravel

This package makes it easy to send notifications using [textcus](https://www.textcus.com/) with Laravel 6.x, 7.x & 8.x.

## Contents

-   [Installation](#installation)
-   [Setting up the Textcus service](#setting-up-the-textcus-service)
-   [Usage](#usage)
-   [Testing](#testing)
-   [Contributing](#contributing)
-   [License](#license)

## Installation

You can install the package via composer:

```bash
composer require alhaji-aki/laravel-textcus
```

## Setting up the textcus service

Add your Textcus sender id and api key to your `config/services.php`:

```php
// config/services.php
...
'textcus' => [
    'api_key' => env('TEXTCUS_API_KEY'),
    'sender_id' => env('TEXTCUS_SENDER_ID'),
],
...
```

## Usage

To route Textcus notifications to the proper phone number, define a `routeNotificationForTextcus` method on your notifiable entity:

```php
class User extends Authenticatable
{
    use Notifiable;

    /**
     * Route notifications for the Textcus channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForTextcus()
    {
        // NOTE: this is because you have to remove the + sign infront of the number in international formt
        return substr($this->mobile, 1);
    }
}
```

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use AlhajiAki\Textcus\Messages\TextcusMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ['textcus'];
    }

    public function toTextcus($notifiable)
    {
        return (new TextcusMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

## Testing

```bash
vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
