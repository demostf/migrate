<?php declare(strict_types=1);

namespace Demostf\Migrate;

class Store {
    private $baseDir;
    private $baseUrl;

    public function __construct($baseDir, $baseUrl) {
        $this->baseDir = $baseDir;
        $this->baseUrl = $baseUrl;
    }

    public function hash(string $name): string {
        return md5_file($this->generatePath($name));
    }

    public function generatePath(string $name): string {
        return $this->baseDir . $this->getPrefix($name) . $name;
    }

    private function getPrefix(string $name): string {
        return '/' . substr($name, 0, 2) . '/' . substr($name, 2, 2) . '/';
    }

    public function exists(string $name): bool {
        return file_exists($this->generatePath($name));
    }

    public function generateUrl(string $name) {
        return $this->baseUrl . $this->getPrefix($name) . $name;
    }
}
