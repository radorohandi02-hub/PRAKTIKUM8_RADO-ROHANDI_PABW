<?php
$f = 'composer.json';
$c = json_decode(file_get_contents($f), true);

unset($c['autoload']['psr-4']['Modules\\']);
$c['autoload']['psr-4']['Modules\\Buku\\'] = 'Modules/Buku/app/';
$c['autoload']['psr-4']['Modules\\Peminjaman\\'] = 'Modules/Peminjaman/app/';
$c['autoload']['psr-4']['Modules\\UserManagement\\'] = 'Modules/UserManagement/app/';

$c['extra']['merge-plugin']['include'] = ['Modules/*/composer.json'];
$c['config']['allow-plugins']['wikimedia/composer-merge-plugin'] = true;

file_put_contents($f, json_encode($c, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
echo "composer.json berhasil dipatch\n";
