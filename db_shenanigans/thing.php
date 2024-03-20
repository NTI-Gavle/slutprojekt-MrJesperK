<?php
require '../vendor/autoload.php';

$bucket_name        = "images";
$account_id         = "e3e80fa27f48463489ff183d8cd229a5";
$access_key_id      = "a61e3b28bee3c0b48657393f4ae5796b";
$access_key_secret  = "e17dc37bce5892f6086d2556ff67ace89bd97701a5cbb3233b18737270e6f6f0";

$credentials = new Aws\Credentials\Credentials($access_key_id, $access_key_secret);

$options = [
    'region' => 'auto',
    'endpoint' => "https://$account_id.r2.cloudflarestorage.com",
    'version' => 'latest',
    'credentials' => $credentials
];

$s3_client = new Aws\S3\S3Client($options);

$contents = $s3_client->listObjectsV2([
    'Bucket' => $bucket_name
]);
