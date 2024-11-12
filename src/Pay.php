<?php

namespace Jshar\BogPayment;

use Jshar\BogPayment\Contracts\PayContract;
use Jshar\BogPayment\Traits\BuildsPayment;

class Pay implements PayContract
{
    use BuildsPayment;

    public ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->resetPayload();
    }

    public function process(): array
    {
        $response = $this->apiClient->post('/ecommerce/orders', $this->payload);

        $this->resetPayload();

        return [
            'id' => $response['id'],
            'redirect_url' => $response['_links']['redirect']['href'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }
}
