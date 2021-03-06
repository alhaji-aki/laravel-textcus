<?php

namespace AlhajiAki\Textcus;

use AlhajiAki\Textcus\Exceptions\TextcusException;
use GuzzleHttp\Client;

class TextcusClient
{
    public function send($from, $to, $content)
    {
        $client = new Client([
            'base_uri' => 'https://sms.textcus.com/',
        ]);

        $apiKey = config('services.textcus.api_key');

        $dlr = config('services.textcus.dlr');

        $type = config('services.textcus.type');

        $response = $client->get("api/send?apikey={$apiKey}&destination={$to}&source={$from}&dlr={$dlr}&type={$type}&message={$content}");

        $response = json_decode($response->getBody()->getContents());

        if ($response->status !== '0000') {
            throw new TextcusException($response->error ?? $response->warning ?? $response->message ?? 'Unable to send sms');
        }

        return $response;
    }
}
