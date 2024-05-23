<?php
require 'dbconn.php';

$json_data = file_get_contents('php://input');
$request = json_decode($json_data, true);

if ($request === null) {
    http_response_code(400);
    exit("Invalid JSON data");
}
$username = htmlspecialchars($request['username']);
$password = htmlspecialchars($request['password']);
try {
    $stmt = $dbconn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['isVerified'] == 'Y') {
        if (password_verify($password, $user['pass'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['admin'] = $user['admin'];


        } else {
            echo "Invalid username or password";
            die();
        }
    } elseif (!$user || $user['isVerified'] == 'N') {
        echo "Invalid username or password";
        die();
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "<br />";
    die();
}

if ($user){
echo "<ul class='navbar-nav me-auto mb-2 mb-lg-0' id='listToUpdate'>
<li class='nav-item dropdown'>
  <a class='nav-link dropdown-toggle' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
    Categories
  </a>
  <ul class='dropdown-menu'>
    <li><a class='dropdown-item' href='index.php'><b>all Fans</b></a></li>
    <li>
      <hr class='dropdown-divider'>
    </li>
    <li><a class='dropdown-item' href='index.php?c=tower'>Tower Fans</a></li>
    <li>
      <hr class='dropdown-divider'>
    </li>
    <li><a class='dropdown-item' href='index.php?c=table'>Table Fans</a></li>
    <li>
      <hr class='dropdown-divider'>
    </li>
    <li><a class='dropdown-item' href='index.php?c=ceiling'>Ceiling Fans</a></li>
    <li>
      <hr class='dropdown-divider'>
    </li>
    <li><a class='dropdown-item' href='index.php?c=handheld'>Handheld Fans</a></li>
  </ul>
</li>
<li class='nav-item'>

<button type='button' class='btn' id='modalInput1' data-bs-toggle='modal' data-bs-target='#PostModal' onclick='modal1()'>
New Post
</button>
  
</li>
<li class='nav-item'>
 
    <a href='account.php?user=" . $request['username'] . "&p=saved' class='btn'>" . $request['username'] . "</a>
      
  
</li>
</ul>";
} else {
    echo "Invalid username or password";
}