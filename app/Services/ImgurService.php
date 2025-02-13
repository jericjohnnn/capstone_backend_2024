<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ImgurService
{
    protected $client;
    protected $clientId;

    public function __construct()
    {
        $this->client = new Client();
        $this->clientId = env('IMGUR_CLIENT_ID');
    }

    public function uploadImage($imagePath)
    {
        try {
            $response = $this->client->request('POST', 'https://api.imgur.com/3/image', [
                'headers' => [
                    'Authorization' => 'Client-ID ' . $this->clientId,
                ],
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen($imagePath, 'r'),
                    ],
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            return $body['data']['link'] ?? null; // Return the uploaded image URL
        } catch (\Exception $e) {
            Log::error('Imgur Upload Error: ' . $e->getMessage());
            return null;
        }
    }
}
