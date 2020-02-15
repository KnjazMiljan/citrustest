<?php

use Service\Container;

require __DIR__.'/bootstrap.php';

$container = new Container($configuration);
$pdo = $container->getPDO();
$comment = $container->getComment();

$email = $_POST['email'] ? $_POST['email'] : '';
$name = $_POST['name'] ? $_POST['name'] : '';
$text = $_POST['text'] ? $_POST['text'] : '';


if ($email && $name && $text) {
    $data = [
        'name' => $name,
        'email' => $email,
        'text' => $text
    ];

    $except = [
        'status'
    ];

    try {
        $comment->create($data, $except);
    } catch (Exception $e) {
    }

    header('Location: /index.php?success=true');
    die;
} else {
    header('Location: /index.php?error=missing_data');
    die;
}
