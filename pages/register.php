<?php
require '../db_shenanigans/dbconn.php';
require '../vendor/autoload.php';
use ReallySimpleJWT\Token;
$success = "";
if ($_SERVER['REQUEST_METHOD'] == "POST"){
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && !str_contains($_POST['username'], "&")){

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $email = htmlspecialchars($_POST['email']);

    try {
        $stmt = $dbconn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $error = "Username or email is already in use";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertStmt = $dbconn->prepare("INSERT INTO users (username, email, pass) VALUES (:username, :email, :password)");
            $insertStmt->bindParam(':username', $username);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password', $hashedPassword);
            $insertStmt->execute();

            $payload = [
                'iat' => time(),
                'mail' => $email,
                'exp' => time() + 60*60,
                'iss' => 'localhost'
            ];

            $VSecret = 'Ty75qKCw3vuJUhHv*rxSw';

            $VToken = Token::customPayload($payload, $VSecret);

            $mail_content = '<!DOCTYPE html><body>';
            $mail_content .= "<p>balls:</p> <a href='http://localhost:8080/projekt/slutprojekt-MrJesperK/pages/verify.php?t=$VToken'>alive yourslef</a>";
            $mail_content .= "<p>balls2:</p> <a href='http://10.154.34.37:8080/projekt/slutprojekt-MrJesperK/pages/verify.php?t=$VToken'>alive yourslef</a>";
            $mail_content .='</body></html>';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($email, "Verify account creation or something", $mail_content, $headers);
    
            $_POST = array();
            $success = "A verification mail has been sent to the provided email address, you have an hour to verify your account";

            header('Location: index.php');
        }
    } catch(PDOException $e) {
        echo 'Connection failed: '.$e->getMessage()."<br />";
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<Header class="container-fluid border-bottom text-center">
        <a href="index.php" class="text-decoration-none "><h2 class="text-black fw-bold">Only&#128405;Fans</h2></a>
    </Header>
    <div class="container m-auto mt-5 border border-black rounded p-3" style="height=:fit-content;">
        
        <form action="" method="POST">
        <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <?php echo $success?>
        <div class="d-flex text-center flex-column gap-4">
            <input type="text" name="username" id="username" placeholder="Username">
            <input type="email" name="email" id="email" placeholder="example@gmail.com">
            <input type="password" name="password" id="password" placeholder="Password">
            <hr>
            <button type="submit" class="btn btn-primary" name="register">Register</button>
            </div>
        </form>
        </div>
   
</body>
</html>