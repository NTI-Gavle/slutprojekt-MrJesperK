<?php 
require 'dbconn.php';
require '../vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Configuration for Cloudflare R2
$bucket_name        = "images"; 
$account_id         = "e3e80fa27f48463489ff183d8cd229a5"; 
$access_key_id      = "a61e3b28bee3c0b48657393f4ae5796b"; 
$access_key_secret  = "e17dc37bce5892f6086d2556ff67ace89bd97701a5cbb3233b18737270e6f6f0"; 

// Initialize AWS SDK client for Cloudflare R2
$s3_client = new S3Client([
    'region'      => 'auto',
    'endpoint'    => "https://$account_id.r2.cloudflarestorage.com",
    'version'     => 'latest',
    'credentials' => [
        'key'    => $access_key_id,
        'secret' => $access_key_secret,
    ]
]);

$json_data = file_get_contents('php://input');
$request = json_decode($json_data, true);

$Categories = ['Tower', 'Handheld', 'Ceiling', 'Table'];

$file_url = null;

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    $filename = $_FILES['image']['name'];
    $tmpFilePath = $_FILES['image']['tmp_name'];

    try {
        // Upload the file to the Cloudflare R2 bucket
        $result = $s3_client->putObject([
            'Bucket'     => $bucket_name,
            'Key'        => $filename,
            'SourceFile' => $tmpFilePath,
            'ACL'        => 'public-read',
        ]);

        // Retrieve the URL of the uploaded file
        $file_url = $result['ObjectURL'];

    } catch (Exception $e) {
        // Output error message
        echo "File upload error: " . $e->getMessage();
    }
} else {
    // Output error message for file upload
    echo "File upload error: " . $_FILES["image"]["error"];
}

$checkLastInsert = $dbconn->prepare("SELECT created_at, LAST_INSERT_ID() FROM posts WHERE username = :username");
$checkLastInsert->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
$checkLastInsert->execute();


if (isset($_SESSION['last_submit']) && ((time() - $_SESSION['last_submit']) < 60 * 5) && $_SESSION['username'] != 'admin') {
    die();
} else {
// Insert data into the database
if ($file_url !== null && in_array($_POST['category'], $Categories)) {
    $title = htmlspecialchars($_POST['title']);
    $descr = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    $user = $_SESSION['username'];
    $image = basename($file_url);

    // Prepare and execute the SQL statement
    $stmt = $dbconn->prepare("INSERT INTO posts (title, description, created_by, created_at, category, image) VALUES (:title, :descr, :user, now(), :category, :image)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':descr', $descr);
    $stmt->bindParam(':user', $user);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':category', $category);
    
    $stmt->execute();
    $_SESSION['last_submit'] = time();
    $success = true;
} else {
    $success = false;
}
}

$thisID = $dbconn->lastInsertId();

if ($success == true) {
       echo "<a href='post.php?id=$thisID' 
       class='card shadow-sm col border mb-5 p-0 text-decoration-none position-relative text-black hoverEffect overflow-hidden z-0'
       style='width: 18rem;'>
       <img class='card-img-top' style='height: 14rem;' src='https://pub-0130d38cef9c4c1aa3926e0a120c3413.r2.dev/$image' />
       <ul class='list-group list-group-flush'>
       <h5 class='list-group-item'>
       $title
       </h5>
       <li class='list-group-item'>
       Likes: 0
       </li>
       <li class='list-group-item'>
       Saves: 0
       </li>
       <li class='list-group-item'>
       Created by: $_SESSION[username]
       </li>
       </ul>
       </a>";
       $success = false;
       die();
} else {
    $success = false;
    die();
}