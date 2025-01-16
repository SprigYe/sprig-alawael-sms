<?php

namespace SprigYe\AlawaelSms;

use GuzzleHttp\Client;
use Exception;

class AlawaelSmsClient
{
    protected array $config;
    protected Client $client;

    public function __construct(array $config)
    {
        $this->config = $config;

        $this->config['send_url'] = $this->config['send_url'] ?? 'https://sms.alawaeltec.com/MainServlet';
        $this->config['coding'] = $this->config['coding'] ?? 2;

        if (empty($this->config['org_name']) || empty($this->config['username']) || empty($this->config['password'])) {
            throw new Exception('Missing required configuration keys: ' . json_encode([
                'org_name' => $this->config['org_name'] ?? null,
                'username' => $this->config['username'] ?? null,
                'password' => $this->config['password'] ?? null,
            ]));
        }

        $this->client = new Client(['verify' => false]);
    }
    public function sendSms(array $recipients, string $message): array
    {
        $responses = [];
        $messageParts = self::breakMessageIntoParts($message);

        foreach ($recipients as $recipient) {
            foreach ($messageParts as $part) {
                $response = $this->client->get($this->config['send_url'], [
                    'query' => [
                        'orgName' => $this->config['org_name'],
                        'userName' => $this->config['username'],
                        'password' => $this->config['password'],
                        'mobileNo' => $recipient,
                        'text' => $part,
                        'coding' => $this->config['coding'],
                    ],
                ]);

                $responses[$recipient][] = $this->parseResponse((string) $response->getBody());
            }
        }

        return $responses;
    }

    public static function breakMessageIntoParts(string $message): array
    {
        $limit = 326;
        $length = mb_strlen($message);

        if ($length <= $limit) {
            return [$message];
        }

        $parts = [];
        $index = 0;

        while ($index < $length) {
            if ($index + $limit < $length) {
                $boundaryStr = mb_substr($message, $index, $limit);

                $endPosDot = mb_strrpos($boundaryStr, '.');
                $endPosColon = mb_strrpos($boundaryStr, ':');
                $endPosBreak = mb_strrpos($boundaryStr, PHP_EOL);

                $endPos = max($endPosDot, $endPosColon, $endPosBreak);

                if ($endPos === false) {
                    $endPos = mb_strrpos($boundaryStr, ' ');
                }

                if ($endPos === false) {
                    $endPos = $limit - 1;
                }

                $part = mb_substr($message, $index, $endPos + 1);
                $index += $endPos + 1;
            } else {
                $part = mb_substr($message, $index);
                $index = $length;
            }

            $parts[] = $part;
        }

        return $parts;
    }

    protected function parseResponse(string $response): array
    {
        $parts = explode(':', $response);

        if (count($parts) < 2) {
            throw new Exception('Invalid response: ' . $response);
        }

        return [
            'code' => (int) $parts[0],
            'message' => $parts[1],
            'id' => $parts[2] ?? null,
        ];
    }
}
