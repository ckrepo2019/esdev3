<?php

namespace App\Http\Controllers\DiscordWebhookController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DiscordWebhookController extends Controller
{
    public function sendDiscordNotification()
    {
        // Define your Discord Webhook URL
        $webhookUrl = "https://discord.com/api/webhooks/1296305826318782535/p_WBX37fvtONrgzhnoeeNnY0rcRfhcWTDXy3d_knv-URYpmCEZLDZ6E_ccLZcJ9ia3Ye";

        // Example dynamic data
        $schoolName = "Springfield High School"; // Replace with dynamic data source
        $concerns = [
            'Concern 1: Attendance Issues',
            'Concern 2: Grade Discrepancies',
            'Concern 3: Behavioral Problems',
        ];
        // Create a new Guzzle client
        $client = new Client();

        // Prepare the message payload
        $payload = [
            'json' => [
                'content' => 'New Concerns Reported',
                'embeds' => [
                    [
                        'title' => 'Concerns for ' . $schoolName,
                        'description' => 'The following concerns have been reported:',
                        'color' => hexdec('00ff00'), // Green color for the embed
                        'fields' => array_map(function ($concern) {
                            return [
                                'name' => 'Concern',
                                'value' => $concern,
                                'inline' => true,
                            ];
                        }, $concerns),
                    ],
                ],
            ],
        ];

        // Send the POST request to the Discord webhook
        $response = $client->post($webhookUrl, $payload);

        // Check if the request was successful
        if ($response->getStatusCode() == 204) {
            return 'Message sent to Discord successfully!';
        } else {
            return 'Failed to send message to Discord.';
        }
    }
}
