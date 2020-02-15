<?php

/*
 * SETTINGS!
 */
$databaseName = 'citrustest';
$databaseUser = 'root';
$databasePassword = '';

/*
 * DUMMY DATA
 */

$numberOfCommentsToGenerate = 5;
$numberOfProductsToGenerate = 9;

$loremIpsumDescriptions = [
    'Sed finibus lobortis dapibus. ',
    'Nunc eros enim, consectetur eget blandit eu, rutrum vel purus.',
    'Maecenas eget nisi vel dui dignissim dignissim.',
    'In dictum ligula sodales nulla pretium lobortis.',
    'Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
];

$randomUserNames = [
    'Johny Bravo',
    'Mojo Jojo',
    'Krang',
    'Lucky Luke',
    'Leeroy Jenkins'
];

$randomEmails = [
    'johnybravo@jb.com',
    'mojojojo@ppg.com',
    'krang@tmnt.com',
    'luckyluke@ll.com',
    'lj@wow.com',
];

$imagePaths = [
    'public/images/clemen.jpeg',
    'public/images/grapefruit.png',
    'public/images/grapefruit2.png',
    'public/images/orange.png',
    'public/images/variousCitruses.jpeg',
];

$randomCitrusNames = [
    'Fortunella',
    'Limetta',
    'Sweet Lemon',
    'White Grapefruit'
];

/*
 * CREATE THE DATABASE
 */
$pdoDatabase = new PDO('mysql:host=localhost', $databaseUser, $databasePassword);
$pdoDatabase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdoDatabase->exec('CREATE DATABASE IF NOT EXISTS citrustest');

/*
 * CREATE THE TABLE
 */
$pdo = new PDO('mysql:host=localhost;dbname='.$databaseName, $databaseUser, $databasePassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// initialize the tables

$pdo->exec('DROP TABLE IF EXISTS `product`;');

$pdo->exec('CREATE TABLE `product` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `image_path` varchar(255) COLLATE utf8mb4_unicode_ci,
 `created_at` datetime NOT NULL,
 `updated_at` datetime NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

$pdo->exec('DROP TABLE IF EXISTS `comment`;');

$pdo->exec('CREATE TABLE `comment` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `text` text NOT NULL,
 `status` tinyint NOT NULL DEFAULT "0" COMMENT "0 -> Unapproved, 1 -> Approved, 2 -> Declined",
 `created_at` datetime NOT NULL,
 `updated_at` datetime NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

/*
 * INSERT INITIAL DATA!
 */

// Insert products based on the number specified on the top of the file

for($i = 0; $i < $numberOfProductsToGenerate; $i++) {
    $pdo->exec( 'INSERT INTO `product`
    (`title`, `description`, `image_path`, `created_at`, `updated_at`) VALUES
    ("' . $randomCitrusNames[array_rand( $randomCitrusNames )] . '", "' . $loremIpsumDescriptions[array_rand( $loremIpsumDescriptions )] . '", "' . $imagePaths[array_rand( $imagePaths )] . '", NOW(), NOW())'
    );
}

// Insert comments based on the number specified on the top of the file
for($i = 0; $i < $numberOfCommentsToGenerate; $i++) {
    $pdo->exec('INSERT INTO `comment`
    (`name`, `email`, `text`,  `created_at`, `updated_at`) VALUES
    ("' . $randomUserNames[array_rand($randomUserNames)] . '", "' . $randomEmails[array_rand($randomEmails)] . '", "' . $loremIpsumDescriptions[array_rand($loremIpsumDescriptions)] . '", NOW(), NOW())'
    );
}

echo "Done!\n";
