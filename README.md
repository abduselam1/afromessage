# Afro Message a Laravel(PHP) SDK

This SDK provides a convinient access to the Afro message API.

## Installation

```bash
composer require abduselam/afromessage
```
* Put your key in your .env 

```env
AFRO_API_KEY=*********
```

## Gettig started

Simple usage looks like this

```php
use Afromessage\AfroMessage;

$response = AfroMessage::send("+251987654321","Hello there");

```

## Methods

This methods provides exactly the same functionality as the actual API of afromessage if you need detail on how it works [browse Afromessage API](https://afromessage.com/developers) 

### send
To send a text message to a phone number use this send method
*2* Required argument.
>1. Recipient phone number.
>2. Message

* Optional parameters *

| Paramater | Type | Description
| ------ | ------ | ------ |
| from | string | The value of the system identifier id if you have subscribed to multiple short codes.|
| sender | string | The value of Sender Name to use for this message. only for verified users |.
| template | int | Indicates the message is a template id rather than the actual message |
| callback | string | The callback URL(GET) you want to receive SMS send progress.
| method | string | http method POST or GET (defuld is POST) |

* Example 
```php

$response = AfroMessage::send("+251987654321","Hello there",method: "GET");

```


### code
This method helps you to send a short code Whether you want to send a one time password (OTP) or put in place a two-factor-authentication in your systems.

*1* Required argument.
>1. Recipient phone number.

* Optional parameters *

| Paramater | Type | Description
| ------ | ------ | ------ |
| codeLength | int | The character length of the security code. |
| type | string | The type of code you want to send (numeric, alphanumeric, alphabet). Defult is numeric |
| timeToExpire | int | The number of seconds for this code to be expired (defualt 0 which is not expired.)|
| prefix | string | A message prefix that you can prepend to the code |
| postfix | string | A message postfix that you can append right after the code |
| spaceBefore | int | The number of empty spaces you want to add between generated code and message prefix.(default is 1) |
| spaceAfter | int | The number of empty spaces you want to add between generated code and message postfix.(default is 1) |
| from | string | The value of the system identifier id if you have subscribed to multiple short codes.|
| sender | string | The value of Sender Name to use for this message. only for verified users |.
| callback | string | The callback URL(GET) you want to receive SMS send progress.

* Example
```php

$response = AfroMessage::code("+251987654321",codeLength: 5, type: 'alphanumeric', postfix: "Is your otp");


```

### verify
Verification method to validate code to the corresponding phone number
*1* Required argument.
>1. code.


* Optional parameters *

| Paramater | Type | Description
| ------ | ------ | ------ |
| Recipient | string | phone number of the recipient. Either this or verificationCod|
| verificationCode | string | The verification Id you received when sending security codes |.

* Example 
```php

$response = AfroMessage::verify("23124",recipient: "+251987654321");

```

