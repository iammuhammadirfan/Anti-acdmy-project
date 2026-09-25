<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;

class BrevoApiTransport extends AbstractTransport
{
    public function __construct(protected string $apiKey)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();

        if (!$email instanceof Email) {
            throw new TransportException('Unsupported message type for Brevo transport.');
        }

        $fromAddress = $email->getFrom()[0] ?? null;

        $payload = [
            'sender' => [
                'email' => $fromAddress ? $fromAddress->getAddress() : 'no-reply@example.com',
                'name'  => $fromAddress ? $fromAddress->getName() : null,
            ],
            'to' => array_map(fn ($addr) => [
                'email' => $addr->getAddress(),
                'name'  => $addr->getName() ?: null,
            ], $email->getTo()),
            'subject'     => $email->getSubject(),
            'htmlContent' => $email->getHtmlBody() ?: nl2br((string) $email->getTextBody()),
        ];

        $response = Http::withHeaders([
            'api-key' => $this->apiKey,
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if (!$response->successful()) {
            throw new TransportException('Brevo API error: ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'brevo+api://api.brevo.com';
    }
}