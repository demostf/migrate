<?php declare(strict_types=1);

namespace Demostf\Migrate;

use GuzzleHttp\Client;

class Api {
    private $endpoint;
    private $client;

    public function __construct($endpoint) {
        $this->endpoint = $endpoint;
        $this->client = new Client();
    }

    public function listDemos(int $page = 0, string $order = 'DESC', $backend = ''): array {
        $url = $this->endpoint . '/demos?page=' . $page . '&order=' . $order . '&backend=' . $backend;
        $content = file_get_contents($url);
        $demos = json_decode($content, true);
        foreach ($demos as &$data) {
            $date = new \DateTime();
            $date->setTimestamp($data['time']);
            $data['time'] = $date;
        }
        return $demos;
    }

    public function changeDemo(int $id, string $backend, string $path, string $url, string $hash, string $key): bool {
        $request = $this->client->post($this->endpoint . '/demos/' . $id . '/url', [
            'form_params' => [
                'hash' => $hash,
                'backend' => $backend,
                'url' => $url,
                'path' => $path,
                'key' => $key
            ]
        ]);
        return $request->getStatusCode() === 200;
    }
}
