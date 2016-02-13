<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';



use GeoIp2\Database\Reader;

// This creates the Reader object, which should be reused across

$reader = new Reader('../GeoLite2-Country.mmdb');


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
print($ip . "\n"); // 'US'


// Replace "city" with the appropriate method for your database, e.g.,
// "country".
$record = $reader->country($ip);

print($record->country->isoCode . "\n"); // 'US'
print($record->country->name . "\n"); // 'United States'

exit;