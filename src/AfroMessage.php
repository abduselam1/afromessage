<?php

namespace Afromessage;

use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class AfroMessage
{
    function http()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AFRO_API_KEY'),
            'Accept'        => 'application/json',
            'Content-type'  => 'application/json'
        ]);
    }

    /**
     * Short code sender.
     *
     * @param string $recipient
     * @param int $codeLength code length default: 4
     * @param string $type 'number', 'alphabet', 'alphanumeric
     * @param int $timeToExpire expiratio time in seconds
     * @param string $prefix a text before the code
     * @param string $postfix a text after the code
     * @param string $spaceBefore amount of white space before the code
     * @param string $spaceAfter amount of white space after the code
     * @param string $from The value of the system identifier id
     * @param string $sender The value of Sender Name to use for this message
     * @param string $callback The callback URL you want to receive SMS send progress. It should be a GET endpoint 
     * @return string
     */
    public function code(
        string $recipient, 
        int $codeLength = 4,
        string $type = 'number',
        int $timeToExpire = 0,
        string $prefix = '', 
        string $postfix = '', 
        int $spaceBefore = 1,
        int $spaceAfter = 1,
        string $from = null,
        string $sender = null,
        $callback = null,

        )
    {
        $messageTypes = [
            'number' => 0,
            'alphabet' => 1,
            'alphanumeric'=> 2
        ];
        $response = $this->http()->get('https://api.afromessage.com/api/challenge', [
            'to' => $recipient,
            'pr' => '',
            'ps' => $postfix,
            'pr' => $prefix,
            'ttl' => $timeToExpire,
            't' => $messageTypes[$type],
            'len' => $codeLength,
            'sb' => $spaceBefore,
            'sa' => $spaceAfter,
            'from' => $from,
            'sender' => $sender,
            'callback' => $callback
        ]);
        return new AfroResponse($response);

    }

    /**
     * Verification method to validate code to the corresponding phone number
     * @param string $recipient phone number you need to verify with the code
     * @param string $code the code you want to verify it.
     * @param string $verificationCode The verification Id you received when sending security codes manadatory if $recipient is not given
     */

    public function verify($code, $recipient=null, $verificationCode = null)
    {
        $response = $this->http()->get('https://api.afromessage.com/api/verify', [
            'to' => $recipient,
            'code' => $code,
            'vc' => $verificationCode
        ]);
        
        return new AfroResponse($response);

    }


    public function send(
        string $recipient,
        string $message,
        string $from = null,
        string $sender = null,
        int $template = 0,
        $callback = null,
        string $method = 'get'
    )
    {
        $response = $this->http()->$method('https://api.afromessage.com/api/send',[
            'to' => $recipient,
            'message' => $message,
            'template' => $template,
            'from' => $from,
            'sender' => $sender,
            'callback' => $callback,
        ]);

        return new AfroResponse($response);

    }


    public function checkStatus(ClientResponse $response): Response
    { 
        $responsBody = json_decode($response->body(), associative: true);

        if($response->status() == 401){
            return new Response([
                'message' => "Account balance is too low. Please refill or contact support."
            ]);
        }
        if ($responsBody['acknowledge'] == 'error') {
            return new Response($responsBody,422);
        }
        return Response($responsBody,200);
    }

}