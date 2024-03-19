<?php
require '../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$s3Client = new S3Client([
    'version' => 'latest',
    'region'  => 'EEUR',
    'endpoint' => 'https://r2.storage.cloudflare.com',
    'credentials' => [
        'key'    => 'a61e3b28bee3c0b48657393f4ae5796b',
        'secret' => 'e17dc37bce5892f6086d2556ff67ace89bd97701a5cbb3233b18737270e6f6f0',
    ],
]);

$bucket = 'images';
$key = 'sus.png'; // Replace with the name you want to give the file in Cloudflare R2
$filePath = '../image/sus.png'; // Replace with the local file path

// Upload a file to Cloudflare R2
try {
    $result = $s3Client->putObject([
        'Bucket' => $bucket,
        'Key'    => $key,
        'Body'   => fopen($filePath, 'r'),
    ]);

    echo "File uploaded successfully.\n";
} catch (AwsException $e) {
    // Display error message
    echo $e->getMessage();
    echo "\n";
}

