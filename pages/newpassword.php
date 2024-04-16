<?php
require '../db_shenanigans/dbconn.php';
require '../vendor/autoload.php';
use ReallySimpleJWT\Token;

$tokenStr = $_GET['t'];
$secret = 'qeZkIMG!u4#]isfu;i!hYw9PD]b1i^Wv_to^)bf%z.wh]tVsm';

$result = Token::validate($tokenStr, $secret);
$exp = Token::validateExpiration($tokenStr);
$thing = Token::getPayload($tokenStr);
var_dump($result);
var_dump($exp);
$uid = $thing['uid'];

if ($result && $exp){
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newPass'])){
    try {
        
            $pass = htmlspecialchars($_POST['newPass']);
           
            $encPass = password_hash($pass, PASSWORD_DEFAULT);
            $updatePassStmt = $dbconn->prepare("UPDATE users SET pass = :pass WHERE ID = :id");
            $updatePassStmt->bindParam(':pass', $encPass, PDO::PARAM_STR);
            $updatePassStmt->bindParam(':id', $uid, PDO::PARAM_INT);
            $updatePassStmt->execute();

    } catch(PDOException $e) {
        echo $e;
    }

}
} else {
    echo "expired or something idk";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password</title>
    <script src="../other_things/script.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <form method="post">
        <input type="password" name="newPass" id="newPass">
        <input type="submit" class="btn btn-primary" value="Update"></input>
    </form>
</body>
</html>