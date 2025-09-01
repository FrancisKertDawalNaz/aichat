<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeminiService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function getReply($message)
    {
        try {
            $response = $this->client->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent',
                [
                    'query' => ['key' => $this->apiKey],
                    'json' => [
                        'contents' => [
                            ['parts' => [['text' => $message]]]
                        ]
                    ]
                ]
            );

            $data = json_decode($response->getBody(), true);

            return $data['candidates'][0]['content']['parts'][0]['text']
                ?? 'Sorry, I could not generate a reply.';
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
