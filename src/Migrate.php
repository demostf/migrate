<?php declare(strict_types=1);

namespace Demostf\Migrate;

class Migrate {
    private $api;
    private $store;
    private $backend;
    private $key;

    public function __construct(Api $api, Store $store, string $backend, string $key) {
        $this->api = $api;
        $this->store = $store;
        $this->backend = $backend;
        $this->key = $key;
    }

    public function migrateDemo(array $demo): bool {
        if (!$this->store->exists($demo['name'])) {
            return false;
        }

        $hash = $this->store->hash($demo['name']);

        if ($hash !== $demo['hash']) {
            throw new \Exception('hash mismatch');
        }

        return $this->api->changeDemo(
            $demo['id'],
            $this->backend,
            $this->store->generatePath($demo['name']),
            $this->store->generateUrl($demo['name']),
            $hash,
            $this->key
        );
    }
}
