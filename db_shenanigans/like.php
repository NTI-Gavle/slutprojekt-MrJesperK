<?php
require 'dbconn.php';

$data = file_get_contents('php://input');
$request = json_decode($data, true);
$user = $_SESSION['user_id'];

$post_id = $request['id'];
$checkLikesIcon = $dbconn->prepare("SELECT post_id, user_id FROM likes WHERE post_id = :post_id AND user_id = :user_id");
 $checkLikesIcon->bindParam(':post_id', $post_id, PDO::PARAM_INT);
 $checkLikesIcon->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
 $checkLikesIcon->execute();
 $likedPost = $checkLikesIcon->fetch(PDO::FETCH_ASSOC);

 if ($likedPost){
    // im a fucking retard and this checks if the post is NOT liked
    $likeIcon = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-heart' viewBox='0 0 16 16'>
    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15'/>
    </svg>";
   

    echo $likeIcon;
     
 } else {

    $likeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
    </svg>';


     echo $likeIcon;
     
 }


if (!isset($_SESSION['username'])){
    echo "<script>alert('you must be signed in to like and/or save posts')</script>";
    die();
} else {
$checkLikes = $dbconn->prepare("SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user");
$checkLikes->bindParam(':post_id', $post_id, PDO::PARAM_INT);
$checkLikes->bindParam(':user', $user, PDO::PARAM_INT);
$checkLikes->execute();
$likeExists = $checkLikes->fetch(PDO::FETCH_ASSOC);

if($likeExists){
    $delStmt = $dbconn->prepare("DELETE FROM likes WHERE post_id = :post_id AND user_id = :user");
    $delStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $delStmt->bindParam(':user', $user, PDO::PARAM_INT);
    $delStmt->execute();
}
else{
$likeStmt = $dbconn->prepare("INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user)");
$likeStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
$likeStmt->bindParam(':user', $user, PDO::PARAM_INT);
$likeStmt->execute();
}
}