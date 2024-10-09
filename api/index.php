<?php

declare(strict_types=1);

session_start();

require_once 'VendingMachine.php';

$machine = new VendingMachine(
   currency: [
        'sign' => 'лв.',
        'space' => '',
        'position' => VendingMachine::CURRENCY_POSITION_AFTER,
    ],
    drinks: [
        'Milk' => 0.50,
        'Espresso' => 0.40,
        'Long Espresso' => 0.60,
    ]
);

$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', $uriPath);
$action = end($uriSegments);

if (method_exists($machine, $action)) {
    $result = $machine->$action();
    echo json_encode($result);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
