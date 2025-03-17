<?php

namespace Afromessage;

use Illuminate\Support\Facades\Http;

class AfroMessage
{
    public static function http()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer '.config('afromessage.api_key'),
            'Accept' => 'application/json',
            'Content-type' => 'application/json',
        ]);
    }

    /**
     * Short code sender.
     *
     * @param  int  $codeLength  code length default: 4
     * @param  string  $type  'number', 'alphabet', 'alphanumeric
     * @param  int  $timeToExpire  expiratio time in seconds
     * @param  string  $prefix  a text before the code
     * @param  string  $postfix  a text after the code
     * @param  string  $spaceBefore  amount of white space before the code
     * @param  string  $spaceAfter  amount of white space after the code
     * @param  string  $from  The value of the system identifier id
     * @param  string  $sender  The value of Sender Name to use for this message
     * @param  string  $callback  The callback URL you want to receive SMS send progress. It should be a GET endpoint
     */
    public static function code(
        string $recipient,
        int $codeLength = 4,
        string $type = 'number',
        int $timeToExpire = 0,
        string $prefix = '',
        string $postfix = '',
        int $spaceBefore = 1,
        int $spaceAfter = 1,
        ?string $from = null,
        ?string $sender = null,
        $callback = null,

    ) {
        $messageTypes = [
            'number' => 0,
            'alphabet' => 1,
            'alphanumeric' => 2,
        ];

        $sender = $sender ? $sender : config('afromessage.sender_id');

        $response = self::http()->get('https://api.afromessage.com/api/challenge', [
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
            'callback' => $callback,
        ]);

        return new AfroResponse($response);

    }

    /**
     * Verification method to validate code to the corresponding phone number
     *
     * @param  string  $recipient  phone number you need to verify with the code
     * @param  string  $code  the code you want to verify it.
     * @param  string  $verificationCode  The verification Id you received when sending security codes manadatory if $recipient is not given
     */
    public static function verify($code, $recipient = null, $verificationCode = null)
    {
        $response = self::http()->get('https://api.afromessage.com/api/verify', [
            'to' => $recipient,
            'code' => $code,
            'vc' => $verificationCode,
        ]);

        return new AfroResponse($response);

    }

    /**
     * Send a text message for single phone number
     *
     * @param  string  $recipient  phone number of the recipient
     * @param  string  $from  The value of the system identifier id
     * @param  string  $sender  The value of Sender Name to use for this message. only for verified users
     * @param  string  $template  Indicates the message is a template id rather than the actual message
     * @param  string  $callback  callback url
     * @param  string  $method  http methods post or get
     */
    public static function send(
        string $recipient,
        string $message,
        ?string $from = null,
        ?string $sender = null,
        int $template = 0,
        $callback = null,
        string $method = 'get'
    ) {
        $method = strtolower($method);

        $sender = $sender ? $sender : config('afromessage.sender_id');

        $response = self::http()->$method('https://api.afromessage.com/api/send', [
            'to' => $recipient,
            'message' => $message,
            'template' => $template,
            'from' => $from,
            'sender' => $sender,
            'callback' => $callback,
        ]);

        return new AfroResponse($response);

    }

    public static function bulk(
        array $recipients,
        ?string $message = null,
        ?string $from = null,
        ?string $sender = null,
        ?string $campaign = null,
        ?string $createCallback = null,
        ?string $statusCallback = null,

    ): AfroResponse {
        $body = [
            'to' => $recipients,
            'message' => $message,
            'from' => $from,
            'sender' => $sender,
            'campaign' => $campaign,
            'createCallback' => $createCallback,
            'statusCallback' => $statusCallback,
        ];
        $response = self::http()->post('https://api.afromessage.com/api/bulk_send', $body);

        return new AfroResponse($response);
    }
}
