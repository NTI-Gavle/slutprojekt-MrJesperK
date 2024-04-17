<?php
require '../db_shenanigans/dbconn.php';
require '../vendor/autoload.php';
use ReallySimpleJWT\Token;

$VTokenStr = $_GET['t'];
$VSecret = 'Ty75qKCw3vuJUhHv*rxSw';

$VResult = Token::validate($VTokenStr, $VSecret);
$VExp = Token::validateExpiration($VTokenStr);
$VThing = Token::getPayload($VTokenStr);
$mail = $VThing['mail'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify'])){
   $verifyStmt = $dbconn->prepare("UPDATE users SET isVerified = 'Y' WHERE email = :email");
   $verifyStmt->bindParam(':email', $mail);
   $verifyStmt->execute();


   header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Verify</title>
</head>
<body class="p-0 m-0">
  <Header class="container-fluid text-center mt-2 border-bottom border-black">
    <a href="index.php" class="text-decoration-none ">
      <h2 class="text-black fw-bold">Only&#128405;Fans</h2>
    </a>
  </Header>

    <div class="m-auto mt-4 text-center">
        <form method="POST">
            <button class="btn btn-primary" type="submit" name="verify">CLICK TO VERIFY!</button>
        </form>
    </div>
</body>
</html>