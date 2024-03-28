<?php 
require '../db_shenanigans/dbconn.php';
require '../vendor/autoload.php';
use ReallySimpleJWT\Token;



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])){
    try{
    $email = $_POST['email'];

    $getUIDStmt = $dbconn->prepare("SELECT ID FROM users WHERE email = :email");
    $getUIDStmt->bindParam(':email', $email);
    $getUIDStmt->execute();

    $user_id = $getUIDStmt->fetch(PDO::FETCH_ASSOC);
    if ($user_id){
        $mail_content = '<!DOCTYPE html><body>';
        $mail_content .= '<p>balls:</p> <a href="http://localhost:8080/projekt/slutprojekt-MrJesperK/pages/newpassword.php">Kill yourslef</a>';
        $mail_content .='</body></html>';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($email, "Password reset", $mail_content, $headers);

    $payload = [
        'iat' => time(),
        'uid' => $user_id,
        'exp' => time() + 10,
        'iss' => 'localhost'
    ];
    
    $secret = 'qeZkIMG!u4#]isfu;i!hYw9PD]b1i^Wv_to^)bf%z.wh]tVsm';

    $token = Token::customPayload($payload, $secret);
    
} else {
    echo 'This email does not exist';
}    

    } catch(PDOException $e) {
        echo $e;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="../other_things/script.js" defer></script>
</head>
<body>
    <div class="container d-flex flex-column justify-content-center m-auto p-0" style="width:fit-content;">
        <h3>Enter your email</h3>
    <form method="post" onsubmit="return thing(event)">
        <input type="email" name="email" id="email" placeholder="example@gmail.com">
        <button type="submit" class="btn btn-primary" name="emailForm" id="emailForm">submit</button>
    </form>
    <strong>You will die</strong>
    </div>
</body>
</html>



