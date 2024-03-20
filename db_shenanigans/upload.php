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

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"])) {
    // Check if a file was uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        // Get the uploaded file name
        $filename = $_FILES["image"]["name"];
        // Get the temporary file path
        $tmpFilePath = $_FILES["image"]["tmp_name"];
        
        // Upload the file to Cloudflare R2 bucket
        try {
            $result = $s3_client->putObject([
                'Bucket'     => $bucket_name,
                'Key'        => $filename,
                'SourceFile' => $tmpFilePath,
                'ACL'        => 'public-read',
            ]);
            // Display success message
            echo "Image uploaded successfully!";
        } catch (AwsException $e) {
            // Handle AWS SDK exception
            echo "Error uploading image: " . $e->getMessage();
        }
    } else {
        // Handle file upload error
        echo "Error uploading image!";
    }
    if (isset($_POST['title']) && isset($_POST['description'])){

        $title = $_POST['title'];
        $descr = $_POST['description'];
        $category = $_POST['category'];
        $user = $_SESSION['username'];
        $image = $filename;
  
            $stmt2 = $dbconn->prepare("INSERT INTO posts (title, description, created_by, created_at, category, image) VALUES (:title, :descr, :user, now(), :category, :image)");
            $stmt2->bindParam(':title', $title);
            $stmt2->bindParam(':descr', $descr);
            $stmt2->bindParam(':user', $user);
            $stmt2->bindParam(':image', $image);
            $stmt2->bindParam(':category', $category);
  
            $stmt2->execute();
  
            header('Location: ../pages/index.php');
  
    }
}
