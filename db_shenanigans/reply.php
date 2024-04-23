<?php 
require 'dbconn.php';

$json_data = file_get_contents('php://input');
$request_data = json_decode($json_data, true);

if ($request_data === null) {
  http_response_code(400);
  exit("Invalid JSON data");
}

$reply_text = htmlspecialchars($request_data['reply']);
$comment_id = $request_data['id'];

try{
$replyStmt = $dbconn->prepare("INSERT INTO replies (comment_id, reply_text, created_by, created_at) VALUES (:comment_id, :reply_text, :created_by, now())");
$replyStmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
$replyStmt->bindParam(':reply_text', $reply_text, PDO::PARAM_STR);
$replyStmt->bindParam(':created_by', $_SESSION['username'], PDO::PARAM_STR);
$replyStmt->execute();

} catch(PDOException $e) {
  http_response_code(500);
  echo $e->getMessage();
}
$time = date("Y-m-d h:i:s");
echo "<div class='card mb-5 m-auto shadow w-25 phoneReplyBox'>
              <div class='card-body p-0 m-0'>
                <p class='card-title p-3'><span class='float-start fw-bold'>$_SESSION[username]</span><span class='float-end text-secondary'>$time</span></p>
                <hr>
                <p class='card-text mb-4 text-center ps-2 pe-2 phoneReplyText'>$reply_text</p>
             </div>
            </div>";