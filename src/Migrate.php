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
            $this->reDownloadDemo($demo);
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

    private function reDownloadDemo(array $demo) {
        $name = basename($demo['url']);

        $tmpFile = tempnam(sys_get_temp_dir(), 'dem_');
        copy($demo['url'], $tmpFile);

        $newHash = md5_file($tmpFile);
        if ($newHash !== $demo['hash']) {
            throw new \Exception('hash mismatch even after re-download: ' . $this->store->generatePath($name));
        }

        unlink($this->store->generatePath($name));
        rename($tmpFile, $this->store->generatePath($name));
    }
}
