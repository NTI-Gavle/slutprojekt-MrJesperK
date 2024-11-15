<?php 
require '../db_shenanigans/dbconn.php';
if (isset($_GET['id'])){
$post_id = $_GET['id'];

$stmt = $dbconn->prepare("SELECT * FROM posts WHERE ID = :post_id");

$stmt->bindParam(':post_id', $post_id);
$stmt->execute();


$post_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['commentText'])){

        

    }

    if (isset($_POST['Delete'])){
        $post = $_GET['id'];

        $deleteStmt = $dbconn->prepare("DELETE FROM posts WHERE ID = :post");
        $deleteStmt->bindParam(':post', $post);
        $deleteStmt->execute();

        header('Location: index.php');
    }

    if (isset($_POST['like'])){
        $user = $_SESSION['user_id'];
        $post_id = $_GET['id'];

       

        
    }
    
    if (isset($_POST['save'])){
      $user = $_SESSION['user_id'];

      if (!isset($_SESSION['username'])){
          echo "<script>alert('you must be signed in to like and/or save posts')</script>";
          header("refresh: 0");
      }

      $checkSaves = $dbconn->prepare("SELECT * FROM saves WHERE post_id = :post_id AND user_id = :user");
      $checkSaves->bindParam(':post_id', $post_id, PDO::PARAM_INT);
      $checkSaves->bindParam(':user', $user, PDO::PARAM_INT);
      $checkSaves->execute();
      $saveExists = $checkSaves->fetch(PDO::FETCH_ASSOC);

      if($saveExists){
          $delStmt = $dbconn->prepare("DELETE FROM saves WHERE post_id = :post_id AND user_id = :user");
          $delStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
          $delStmt->bindParam(':user', $user, PDO::PARAM_INT);
          $delStmt->execute();
      }
      else{
      $saveStmt = $dbconn->prepare("INSERT INTO saves (post_id, user_id) VALUES (:post_id, :user)");
      $saveStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
      $saveStmt->bindParam(':user', $user, PDO::PARAM_INT);
      $saveStmt->execute();
      }    
  }

  $like = "";
if (isset($_POST['like'])){

  if (!isset($_SESSION['username'])){
    echo "<script>alert('you must be signed in to like and/or save posts')</script>";
    header("refresh: 0");
}

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
}
$checkLikesIcon = $dbconn->prepare("SELECT post_id, user_id FROM likes WHERE post_id = :post_id AND user_id = :user_id");
 $checkLikesIcon->bindParam(':post_id', $post_id, PDO::PARAM_INT);
 $checkLikesIcon->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
 $checkLikesIcon->execute();
 $likedPost = $checkLikesIcon->fetch(PDO::FETCH_ASSOC);

 if (!$likedPost){
    $likeIcon = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-heart' viewBox='0 0 16 16'>
    <path d='m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15'/>
    </svg>";
     
 } else {

    $likeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
    </svg>';
     
 }

 $checkIfSaved = $dbconn->prepare("SELECT post_id, user_id FROM saves WHERE post_id = :post_id AND user_id = :user_id");
 $checkIfSaved->bindParam(':post_id', $post_id, PDO::PARAM_INT);
 $checkIfSaved->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
 $checkIfSaved->execute();
 $savedPost = $checkIfSaved->fetch(PDO::FETCH_ASSOC);

 if (!$savedPost){
    $save = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"/>
  </svg>';
     
 } else {

    $save = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-fill" viewBox="0 0 16 16">
    <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/>
  </svg>';
     
 }

$CommentSql = "SELECT * FROM comments WHERE postID = :post_id ORDER BY ID DESC";

$CommentStmt = $dbconn->prepare($CommentSql);
$CommentStmt->bindParam(':post_id', $post_id);
$CommentStmt->execute();

$Comments = $CommentStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $post_data['title'];?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="../other_things/script.js" defer></script>
<link rel="stylesheet" href="../other_things/style.css">
</head>
<body class="p-0 m-0">
    
<Header class="container-fluid text-center mt-2" style="width:fit-content;">
    <a href="index.php?c=all&page=1" class="text-decoration-none ">
      <h2 class="text-black fw-bold">Only&#128405;Fans</h2>
    </a>
  </Header>
  <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom border-top mb-3">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="listToUpdate">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Categories
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="index.php?c=all&page=1"><b>all Fans</b></a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="index.php?c=tower&page=1">Tower Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="index.php?c=table&page=1">Table Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="index.php?c=ceiling&page=1">Ceiling Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="index.php?c=handheld&page=1">Handheld Fans</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <?php
            if (isset($_SESSION['username'])) {
              if ($_SESSION['admin'] === 'Y') {
                echo "<a href='admin.php' class='btn btn-warning'>Admin</a>";
                echo "<li class='nav-item'><a class='btn' href='account.php?user=" . $_SESSION['user_id'] . "&p=saved'>" . $_SESSION['username'] . "</a></li>";
              } else {
                echo "<a href='account.php?p=saved' class='btn'>" . $_SESSION['username'] . "</a>";
              }
            } else {
              echo "<button class='nav-link btn float-start' id='modalInput2' data-bs-toggle='modal' data-bs-target='#LoginModal' onclick='modal2()'>Login</button>";
            }
            ?>
          </li>
        </ul>
        <form class="d-flex phoneSearch" role="search" id="searchForm" onsubmit="return searching(event)" method="post">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

      </div>
    </div>
  </nav>

<div class="container d-flex justify-content-center text-center p-0 z-0">
<?php $image = $post_data['image']; ?>
<div class="card m-3" style="width: 50rem;">
  <img src="https://pub-0130d38cef9c4c1aa3926e0a120c3413.r2.dev/<?php echo $image; ?>" class="card-img-top" alt="picture">
  <div class="card-body">
    <p class="card-text"><?php echo $post_data['description']; ?></p>
  </div>
  <ul class="list-group list-group-flush text-start">
    <li class="list-group-item"><strong>Created by: <?php if ( !isset($_SESSION['username']) || $_SESSION['username'] != $post_data['created_by']){echo "<a class='text-decoration-none' href='user.php?u=$post_data[created_by]'>$post_data[created_by]</a>";}else{ echo $post_data['created_by'];} ?></strong></li>
    <li class="list-group-item"><strong class="text-body-secondary">Posted at: <?php echo $post_data['created_at']?></strong></li>

    <li class="list-group-item">
      <form method="post" id="likeForm">
      
        <button class="btn btn-success position-relative text-center" type="submit" name="like" id="likeButton<?php echo $post_id?>" data-post-id="<?php echo $post_id?>">

        <p class="m-0">
        <?php echo $likeIcon; ?>
        </p>

        </button>

      </form>
    </li>

    <li class="list-group-item">
      <form method="post">
        <button class="btn btn-primary position-relative text-center" type="submit" name="save" id="save">
          <p class="m-0">
            <?php echo $save; ?>
          </p>
        </button>
      </form>
    </li>
<?php if (isset($_SESSION['username'])){
    echo "<li class='list-group-item'><button class='btn btn-primary' id='modalInput3' data-bs-target='#CommentModal' data-bs-toggle='modal' onlick='modal3()'>Comment</button></li>";
 } ?>

 <?php
 if (!isset($_SESSION['username'])) {
  echo "";
}
    elseif (isset($_SESSION['username']) && $_SESSION['admin'] === 'Y' || $_SESSION['username'] == $post_data['created_by'] ){
      echo "<li class='list-group-item'><button class='btn btn-danger' id='modalInput4' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='deleteModal()' style='top:1rem;'>Delete</button></li>";
  } 
 ?>
  </ul>
</div>


    </div>





<div class="modal fade" id="CommentModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel"><?php echo $post_data['title']; ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post" id="commentForm" onsubmit="return comment(event, <?php echo $_GET['id']?>)">
      <div class="modal-body d-flex flex-column mb-3 gap-3">
        <textarea name="commentText" id="commentText" cols="20" rows="10" placeholder="Comment" maxlength="200" style="resize:none";></textarea>
      </div>
      <div class="modal-footer">
        <img src="../image/sus.png" alt="sus" style="width:3rem; height:3rem;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Comment" name="Comment" id="Comment" />
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel">Delete</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>    
      <form method="POST">
      <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
      <div class="modal-body d-flex flex-column mb-3 gap-3">
        <h3>Are you sure you want to delete this post?</h3>
      </div>
      <div class="modal-footer">
        <img src="../image/sus.png" alt="sus" style="width:3rem; height:3rem;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" name="Delete" value="Delete" id="Delete"></input>
      </div>
      </form>
    </div>
  </div>
</div>
<div id="commentList">
    <h2 class="text-center m-3">Comments</h2>
<?php foreach ($Comments as $Comment): ?>
  <?php
  $createdBy = $Comment['created_by'];

    $fetchReplyStmt = $dbconn->prepare("SELECT * FROM replies WHERE comment_id = :comment_id ORDER BY id DESC");
    $fetchReplyStmt->bindParam(':comment_id', $Comment['ID'], PDO::PARAM_INT);
    $fetchReplyStmt->execute();
    $replies = $fetchReplyStmt->fetchAll(PDO::FETCH_ASSOC);  
    
    $commentIdForThing = strval($Comment['ID']);
        
    
  
  ?>
  
  <div class="card m-3 p-0 shadow-sm" id="<?php echo $Comment['ID'] ?>">
  <div class="card-header">
    <?php 
    $createdAt = $Comment['created_at'];
    if (!isset($_SESSION['username']) || $createdBy != $_SESSION['username']){
    echo "<p class='m-0'>
    <a href='user.php?u=$createdBy' class='fw-bold'>$createdBy</a>
    <span class='float-end'>$createdAt</span>
    </p>";
    } else {
      echo "<p class='m-0'><span class='fw-bold'>$createdBy</span> <span class='text-secondary'>(this is you)</span><span class='float-end text-secondary'>$createdAt</span></p>";
    }
    ?>
  </div>
  <div class="card-body m-0 ps-0 pe-0">
    <p class="card-text ps-3"><?php echo $Comment['CommentText'] ?></p>
    <ul class="list-group list-group-flush">
<li class="list-group-item">
  <button type="button" class="btn btn-primary" id="replyModalInput" data-bs-toggle="modal" data-bs-target="#replyModal<?php echo $Comment['ID']?>">View replies</button>
</li>
</ul>
  </div>
</div>


<!-- Modal with replies -->
<div class="modal fade" id="replyModal<?php echo $Comment['ID']?>" tabindex="-1" aria-labelledby="replyModal" aria-hidden="true">
<div class="modal-dialog modal-fullscreen">
  <div class="modal-content">
    <div class="modal-header">
      <h1 class="modal-title text-decoration-underline m-auto">Replies to <?php echo $Comment['created_by']?></h1>
      <button type="button" class="btn-close float-end m-0" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body text-center" id="replyBody<?php echo $Comment['ID']?>">
        <?php if(empty($replies)): ?>
          <h3 id="replyThing">
            No replies yet...
          </h3>
          <br>
          <div>
          </div>
          <?php endif; ?>
          <?php foreach($replies as $reply): ?>
       
             <div class="card mb-5 m-auto shadow w-25 phoneReplyBox">
              <div class="card-body p-0 m-0">
                <?php if (!isset($_SESSION['username']) || $reply['created_by'] != $_SESSION['username']): ?>
                <p class="card-title p-3"><a href="user.php?u=<?php echo $reply['created_by']?>" class="float-start fw-bold replyPhoneName"><?php echo $reply['created_by']?></a><span class="float-end text-secondary"><?php echo $reply['created_at']?></span></p>
                <?php else: ?>
                  <p class="card-title p-3"><span class="float-start fw-bold replyPhoneName"><?php echo $reply['created_by']?></span><span class="float-end text-secondary"><?php echo $reply['created_at']?></span></p>
                  <?php endif?>
                <hr>
                <p class="card-text mb-4 text-center ps-2 pe-2 phoneReplyText"><?php echo $reply['reply_text']?></p>
             </div>
            </div>
        
          <?php endforeach ?>
      </div>
      <div class="modal-footer">
        <?php if(isset($_SESSION['username'])): ?>
        <form method="POST" class="w-100 text-center" id="replyForm_<?php echo $Comment['ID']; ?>" onsubmit="return reply(event, <?php echo $Comment['ID']?>)">
          <input class="rounded-pill border border-secondary p-2 w-75" type="text" id="replyText_<?php echo $Comment['ID']?>" name="reply_<?php echo $Comment['ID']?>" placeholder="reply">
          <input type="hidden" name="comment_id" value="<?php echo $Comment['ID']?>">
          <button class="btn btn-primary" type="submit">Reply</button>
        </form>
        <?php else: ?>
          <h4 class="m-auto">You must be logged in to reply</h4>
        <?php endif ?>
      </div>
  </div>
</div>
</div>
<?php endforeach; ?>
</div>



<div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="ModalLabel">Login</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <p class="text-center mt-2" id="error"></p>
        <form id="loginForm" method="post" onsubmit="return login(event)">
          <div class="modal-body d-flex flex-column mb-3 gap-3">
            <input type="text" name="username" id="username" placeholder="username" required>
            <input type="password" name="password" id="password" placeholder="password" required>
            <a href="passreset.php">Forgot password?</a>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="register.php" class="btn btn-primary">Register</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>