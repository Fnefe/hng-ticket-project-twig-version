<?php
//Get port from Railway environment variable
$port = getenv('PORT') ?: 8080;

$command ="php -S 0.0.0.0:{$port} -t src/";
echo "Starting server on port {$port}...\n"
passthru($command);