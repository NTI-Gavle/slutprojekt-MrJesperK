<?php
require 'dbconn.php';

$data = file_get_contents('php://input');
$request = json_decode($data, true);

if ($request && isset($request['id']) && isset($request['text'])) {
    $postID = $request['id'];
    $user = $_SESSION['username'];
    $text = htmlspecialchars($request['text']);

    try {
        $stmt = $dbconn->prepare("INSERT INTO comments (postID, created_by, CommentText, created_at) VALUES (:postID, :user, :text, now())");
        $stmt->bindParam(':postID', $postID);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':text', $text);
        $stmt->execute();

        $thisID = $dbconn->lastInsertId();
        $time = date("Y-m-d h:i:s");
        echo /*html*/"<div class='card m-3 p-0 shadow-sm' id='$thisID'>
        <div class='card-header'>
            <p class='m-0'><span class='fw-bold'>$_SESSION[username]</span> <span class='text-secondary'>(this is you)</span><span class='float-end text-secondary'>$time</span></p>
             </div>
        <div class='card-body m-0 ps-0 pe-0'>
          <p class='card-text ps-3'>$request[text]</p>
          <ul class='list-group list-group-flush'>
          <li class='list-group-item'>
            <a href='#' class='btn btn-primary'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-heart' viewBox='0 0 16 16'>
        <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15'/>
      </svg></a>
      </li>
      <li class='list-group-item'>
      Refresh the page to view replies or like or whatever (for now (this might be way too annoying to fix later so i just might not lmao))
      </li>
      </ul>
        </div>
      </div>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request data";
}

