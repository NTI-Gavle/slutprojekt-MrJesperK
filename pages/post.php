<?php 
require '../db_shenanigans/dbconn.php';

if (isset($_GET['id'])){
$post_id = $_GET['id'];

$sql = "SELECT * FROM posts WHERE ID = :post_id";

$stmt = $dbconn->prepare($sql);

$stmt->bindParam(':post_id', $post_id);
$stmt->execute();

$post_data = $stmt->fetch(PDO::FETCH_ASSOC);


}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['commentText'])){

        $postID = $_GET['id'];
        $user = $_SESSION['username'];
        $text = $_POST['commentText'];

        $stmt = $dbconn->prepare("INSERT INTO comments (postID, created_by, CommentText, created_at) VALUES (:postID, :user, :text, now())");
        $stmt->bindParam(':postID', $postID);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':text', $text);
        $stmt->execute();

        header('Refresh: 0');
    }

    if (isset($_POST['Delete'])){
        $post = $_GET['id'];
        $reason = $_POST['Reason'];

        $Dstmt = $dbconn->prepare("INSERT INTO deleted (reason) VALUES (:reason)");
        $Dstmt->bindParam(':reason', $reason);
        $Dstmt->execute();

        $deleteStmt = $dbconn->prepare("DELETE FROM posts WHERE ID = :post");
        $deleteStmt->bindParam(':post', $post);
        $deleteStmt->execute();

        header('Location: index.php');
    }

    if (isset($_POST['like'])){
        $user = $_SESSION['user_id'];
        $post_id = $_GET['id'];

        if (!isset($_SESSION['username'])){
            header('Refresh: 0');
            echo "<script>alert('you must be signed in to like and/or save posts')</script>";
            die();
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


        header('Refresh: 0');
        
    }

    if (isset($_POST['save'])){
      $user = $_SESSION['user_id'];
      $post_id = $_GET['id'];

      if (!isset($_SESSION['username'])){
          header('Refresh: 0');
          echo "<script>alert('you must be signed in to like and/or save posts')</script>";
          die();
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


      header('Refresh: 0');
      
  }
    
}

$CommentSql = "SELECT * FROM comments WHERE postID = :post_id";

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
<script src="../other_things/script.js"></script>
</head>
<body class="p-0 m-0">
    
<Header class="container-fluid border-bottom text-center">
        <a href="index.php" class="text-decoration-none "><h2 class="text-black fw-bold">Only&#128405;Fans</h2></a>
    </Header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom mb-3">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categories
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php?category=All"><b>all Fans</b></a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="index.php?category=Tower">Tower Fans</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Table Fans</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Ceiling Fans</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Handheld Fans</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <?php if (!isset($_SESSION['username'])){
            echo "<a class='nav-link btn' href='index.php'>Login</a>";
          } 

          ?>
        </li>
        <li class="nav-item">
        <?php
        if (isset($_SESSION['username'])){
              echo "<a href='account.php?user=".$_SESSION['username']."'class='btn'>Account</a>";
        }
            ?>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div>
<div class="container d-flex justify-content-center text-center p-0 z-0" style="width:40rem; height:40rem;">
    <div class="row m-auto justify-content-center z-0">
<?php
$image = $post_data['image'];
echo "<h1 class='text-start'>".$post_data['title']."</h1>";
echo "<p class='text-start text-body-secondary fs-5 mb-0'>Posted by: ". $post_data['created_by']."</p>";
echo "<p class='text-start text-body-secondary fs-6'>". $post_data['created_at']."</p>";
echo "<img style='height:40rem; width:40rem;' class='border shadow object-fit-scale' src='https://pub-0130d38cef9c4c1aa3926e0a120c3413.r2.dev/$image' />";
if (isset($_SESSION['username'])){
    if ($_SESSION['admin'] === 'Y'){
        echo "<button class='btn btn-danger position-relative' id='modalInput4' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='deleteModal()' style='top:1rem;'>Delete</button>";
    }
echo "<button class='btn btn-primary mt-4 z-3' id='modalInput3' data-bs-target='#CommentModal' data-bs-toggle='modal' onlick='modal3()'>Comment</button>";
}
?>

    </div>
</div>


<div class="container d-flex float-end text-center p-0 z-0 position-absolute" style="right: 6rem; top: 20rem; width:fit-content;">
    <div class=" m-auto justify-content-center z-0">
        <?php
        echo "<h2 class='mt-4' style='height:5rem; width:30rem;'>Description</h2>";
        echo "<hr class='position-relative' style='bottom:1.3rem;'>";
        echo "<h4 class='text-center p-2 position-relative mt-3' style='width:30rem; top:-2rem;'>". $post_data['description'] ."</h4>";
        ?>
    </div>
    <div class="container position-absolute d-flex justify-content-center text-center gap-2 p-0 m-0" style="height:fit-content; width:fit-content; top:-8rem; left:-14rem;">
<form method="post">
    <button class="btn btn-success position-relative text-center" type="submit" name="like" id="like">Like</button>
</form>
<form method="post">
    <button class="btn btn-success position-relative text-center" type="submit" name="save" id="save">Save</button>
</form>
</div>
</div>
</div>



<div class="modal fade" id="CommentModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel"><?php echo $post_data['title']; ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
      <div class="modal-body d-flex flex-column mb-3 gap-3">
        <textarea name="commentText" id="commentText" cols="20" rows="10" placeholder="Comment" maxlength="200"></textarea>
      </div>
      <div class="modal-footer">
        <img src="../image/sus.png" alt="sus" style="width:3rem; height:3rem;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Comment" name="Comment" id="Comment"></input>
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
      <form  method="POST" action="">
      <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
      <div class="modal-body d-flex flex-column mb-3 gap-3">
        <input type="text" name="Reason" id="Reason" placeholder="--Reason--">

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

<hr class="m-0 position-relative" style="top:14rem;">
<div class="d-flex flex-column justify-content-center align-self-center position-relative m-auto border-start border-end" style="width:70rem; height:fit-content; top:14rem;">
    <h2 class="text-center mt-3">Comments</h2>
    <div>
        <?php foreach ($Comments as $Comment): ?>
            <hr>
            <p class="text-start fs-6 text-body-secondary ms-3 mb-0 mt-3"><?php echo $Comment['created_by']; ?></p>
        <p class="fs-5 mb-5 ms-2 text-break"><?php echo $Comment['CommentText']; ?></p>
        <form method="post"><button class="btn btn-primary ms-4 mb-3" type="submit" name="likeComment" id="likeComment">Like</button></form>
        <?php endforeach; ?>

    </div>
</div>

</body>
</html>