<?php

namespace Afromessage;

use Illuminate\Http\Client\Response;

class AfroResponse
{
    public $status;

    public $acknowledge;

    public $response;

    public function __construct(Response $response)
    {
        $responsBody = json_decode($response->body(), associative: true);

        if ($response->status() == 401) {
            $this->status = $response->status();
            $this->acknowledge = 'error';
            $this->response = [
                'message' => 'Account balance is too low. Please refill or contact support.',
            ];

        } elseif ($responsBody['acknowledge'] == 'error') {
            $this->status = '422';
            $this->acknowledge = 'error';
            $this->response = $responsBody['response'];
        } else {

            $this->status = '200';
            $this->acknowledge = 'success';
            $this->response = $responsBody['response'];
        }

    }
}
