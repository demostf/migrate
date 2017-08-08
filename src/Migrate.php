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
        $name = basename($demo['url']);
        if (!$this->store->exists($name)) {
            throw new \Exception('demo not found: ' . $this->store->generatePath($name));
        }

        $hash = $this->store->hash($name);

        if ($hash !== $demo['hash']) {
            throw new \Exception('hash mismatch: ' . $this->store->generatePath($name));
        }

        return $this->api->changeDemo(
            $demo['id'],
            $this->backend,
            $this->store->generatePath($name),
            $this->store->generateUrl($name),
            $hash,
            $this->key
        );
    }
}
