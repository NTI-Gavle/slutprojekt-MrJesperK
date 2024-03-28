<?php
require '../db_shenanigans/dbconn.php';
require '../vendor/autoload.php';
use ReallySimpleJWT\Token;

$token = 'PenisBallsInMyAss';
$secret = 'qeZkIMG!u4#]isfu;i!hYw9PD]b1i^Wv_to^)bf%z.wh]tVsm';

$result = Token::validate($token, $secret);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password</title>
    <script src="../other_things/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <form method="post">
        <input type="password" name="newPass" id="newPass">
        <input type="checkbox" name="" id="showPassword" onclick="Shenanigans()">
        <button type="submit"></button>
    </form>
</body>
</html>