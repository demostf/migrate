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
            $this->reDownloadDemo($demo);
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
        $encodedUrl = rawurlencode($demo['url']);
        $encodedUrl = str_replace('%2F', '/', $encodedUrl);
        $encodedUrl = str_replace('%3A//', '://', $encodedUrl);
        copy($encodedUrl, $tmpFile);

        $newHash = md5_file($tmpFile);
        if ($newHash !== $demo['hash']) {
            throw new \Exception('hash mismatch even after re-download: ' . $this->store->generatePath($name));
        }

        $path = $this->store->generatePath($name);
        unlink($path);
        rename($tmpFile, $path);
        chmod($path, 0644);
    }
}
