<?php

namespace App\Services;


use App\Http\Clients\PostbackSmsClient;

class SmsService
{
    private PostbackSmsClient $client;

    public function __construct(PostbackSmsClient $client)
    {
        $this->client = $client;
    }

    public function requestNumber(string $country, string $service, ?int $rentTime = null, ?string $token = ''): array
    {
        return $this->client->getNumber($country, $service, $rentTime, $token);
    }

    public function fetchSms(string $activationId, ?string $token = ''): array
    {
        return $this->client->getSms($activationId, $token);
    }

    public function revokeNumber(string $activationId, ?string $token = ''): array
    {
        return $this->client->cancelNumber($activationId, $token);
    }

    public function checkStatus(string $activationId, ?string $token = ''): array
    {
        return $this->client->getStatus($activationId, $token);
    }
}
