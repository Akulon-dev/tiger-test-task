<?php

namespace App\Http\Clients;

use Exception;
use Illuminate\Support\Facades\Http;

class PostbackSmsClient
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->token = config('services.postback_sms.token');
        $this->baseUrl = config('services.postback_sms.base_url');
    }

    private function request(string $action, array $params = [], string $token = ''): array
    {
        $params['action'] = $action;
        $params['token'] = empty($token) ? $this->token : $token;
        try {
            $response = Http::get($this->baseUrl, $params);
            return $response->json();
        } catch (Exception $e) {
            return ['code' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getNumber(string $country, string $service, ?int $rentTime = null, string $token = ''): array
    {
        $params = ['country' => $country, 'service' => $service];
        if ($rentTime) {
            $params['rent_time'] = $rentTime;
        }
        return $this->request('getNumber', $params, $token);
    }

    public function getSms(string $activationId, string $token = ''): array
    {
        return $this->request('getSms', ['activation' => $activationId], $token);
    }

    public function cancelNumber(string $activationId, string $token = ''): array
    {
        return $this->request('cancelNumber', ['activation' => $activationId], $token);
    }

    public function getStatus(string $activationId, string $token = ''): array
    {
        return $this->request('getStatus', ['activation' => $activationId], $token);
    }
}
