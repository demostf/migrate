<?php declare(strict_types=1);

require 'vendor/autoload.php';

if (!getenv('STORAGE_ROOT')) {
    $env = new \Dotenv\Dotenv(__DIR__);
    $env->load();
}

$store = new \Demostf\Migrate\Store(getenv('STORAGE_ROOT'), getenv('BASE_URL'));
$api = new \Demostf\Migrate\Api(getenv('SOURCE'));
$migrate = new \Demostf\Migrate\Migrate($api, $store, getenv('BACKEND'), getenv('KEY'));
$statePath = getenv('STATE_FILE');

$fromDate = new DateTime('-3 days');

$list = $api->listDemos(1, 'ASC', 'static');
foreach ($list as $demo) {
    if ($demo['time'] < $fromDate) {
        echo "${demo['id']}\n";
        $migrate->migrateDemo($demo);
    }
}
