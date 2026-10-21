<?php

echo '<pre>';

echo "PHP: " . PHP_VERSION . PHP_EOL;
echo "php.ini: " . php_ini_loaded_file() . PHP_EOL;
echo "extension_dir: " . ini_get('extension_dir') . PHP_EOL;

echo PHP_EOL . "PDO:" . PHP_EOL;
var_dump(extension_loaded('pdo'));

echo PHP_EOL . "PDO MySQL:" . PHP_EOL;
var_dump(extension_loaded('pdo_mysql'));

echo PHP_EOL . "PDO drivers:" . PHP_EOL;
print_r(PDO::getAvailableDrivers());

echo '</pre>';