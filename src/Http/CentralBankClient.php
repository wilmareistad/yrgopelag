<?php

declare(strict_types=1);

namespace App\Http;

use App\Config\Config;
use App\Exceptions\PaymentException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class CentralBankClient
{
    private readonly Client $client;

    public function __construct(private readonly Config $config)
    {
        $this->client = new Client(['base_uri' => $config->centralBankBaseUri()]);
    }

    public function validateTransferCode(string $transferCode, int $totalCost): void
    {
        try {
            $response = $this->post('transferCode', [
                'transferCode' => $transferCode,
                'totalCost' => $totalCost,
            ]);
        } catch (GuzzleException $e) {
            throw new PaymentException('Payment validation failed: ' . $e->getMessage(), 0, $e);
        }

        if (isset($response['error'])) {
            throw new PaymentException('Payment validation failed: ' . $response['error']);
        }
    }

    public function deposit(string $transferCode): void
    {
        try {
            $response = $this->post('deposit', [
                'user' => $this->config->hotelUser(),
                'transferCode' => $transferCode,
            ]);
        } catch (GuzzleException $e) {
            throw new PaymentException('Payment failed: ' . $e->getMessage(), 0, $e);
        }

        if (($response['status'] ?? null) !== 'success') {
            throw new PaymentException('Payment failed: ' . ($response['error'] ?? 'Deposit failed'));
        }
    }

    /** @param array<int, array{activity: ?string, tier: ?string}> $featuresUsed */
    public function sendReceipt(string $guestName, string $checkIn, string $checkOut, array $featuresUsed): void
    {
        try {
            $this->post('receipt', [
                'user' => $this->config->hotelUser(),
                'api_key' => $this->config->apiKey(),
                'guest_name' => $guestName,
                'arrival_date' => $checkIn,
                'departure_date' => $checkOut,
                'features_used' => $featuresUsed,
                'star_rating' => 2,
            ]);
        } catch (GuzzleException $e) {
            throw new PaymentException('Receipt could not be sent: ' . $e->getMessage(), 0, $e);
        }
    }

    /** @return array<string, mixed> */
    private function post(string $endpoint, array $json): array
    {
        $response = $this->client->post($endpoint, ['json' => $json]);

        return json_decode((string) $response->getBody(), true) ?? [];
    }
}
