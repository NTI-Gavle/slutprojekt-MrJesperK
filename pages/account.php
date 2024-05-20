<?php
require '../db_shenanigans/dbconn.php';
require '../db_shenanigans/thing.php';

if (!isset($_GET['p'])){
  header("Location: account.php?p=saved");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['logout'])){
        session_destroy();
        header('Location: index.php');
        exit();
     }

     if (isset($_POST['Delete'])){
        echo "<script>alert('hah you thought')</script>";
        header('Location: index.php');
     }
}
$whatPosts = $_GET['p'];

$user_id = $_SESSION['user_id'];
if ($whatPosts == 'saved' || $whatPosts != 'liked'){
$getSavesStmt = $dbconn->prepare("SELECT posts.* FROM saves INNER JOIN posts ON saves.post_id = posts.ID WHERE saves.user_id = :user_id ORDER BY ID DESC");
$getSavesStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$getSavesStmt->execute();
$whatPosts = $getSavesStmt->fetchAll(PDO::FETCH_ASSOC);
} elseif($whatPosts == 'liked'){
$getLikesStmt = $dbconn->prepare("SELECT posts.* FROM likes INNER JOIN posts ON likes.post_id = posts.ID WHERE likes.user_id = :user_id ORDER BY ID DESC");
$getLikesStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$getLikesStmt->execute();
$whatPosts = $getLikesStmt->fetchAll(PDO::FETCH_ASSOC);
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="../other_things/style.css">
<script src="../other_things/script.js"></script>
</head>
<body>
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
              <li><a class="dropdown-item" href="index.php"><b>all Fans</b></a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="index.php?c=tower">Tower Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="index.php?c=table">Table Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="index.php?c=ceiling">Ceiling Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="index.php?c=handheld">Handheld Fans</a></li>
            </ul>
        </li>

        <li class="nav-item">
          <?php if (!isset($_SESSION['username'])){
            echo "<button class='nav-link btn' data-bs-toggle='modal' data-bs-target='#LoginModal' onclick='modal2()'>Login</button>";
          } 
          else {
            echo "";
          }
          ?>
        </li>
        <li class="nav-item">
          <?php
        if (isset($_SESSION['username'])){
          if ($_SESSION['admin'] === 'Y')
              echo "<a href='admin.php' class='btn btn-warning'>Admin</a>";
            }
            
            ?>
        </li>
        <li class="nav-item">
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<form method='post' name='logoutForm'><input type='submit' class='btn btn-warning ms-3' name='logout' value='logout'></input></form>
<button class="btn btn-danger" data-bs-target="#DeleteUserModal" data-bs-toggle="modal" onclick="modal5()" >Delete Account</button>

<div class="modal fade" id="DeleteUserModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel">Delete</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>    
      <form  method="POST" action="">
      <div class="modal-body d-flex flex-column mb-3 gap-3">
        <h2>Are you sure you want to delete you account?</h2>
        <p class="text-body-secondary">Everything gone, no recover &#128078;</p>

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

<div class="container text-center p-0" id="saved">
<div class="dropdown-center">
  <button class="btn dropdown-toggle btn-lg border border-secondary shadow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <?php echo $_GET['p']?>
  </button>
  <ul class="dropdown-menu text-center">   
    <li><a class="dropdown-item" href="account.php?p=<?php echo "saved"?>"">Saved</a></li>
    <li class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="account.php?p=<?php echo "liked"?>">Liked</a></li>
  </ul>
</div>
<h2 class="mt-4">
    <?php if (empty($whatPosts)): ?>
        Nothing to see here
    <?php endif; ?>
</h2>
<div class="row row-cols-6 column-gap-5 row-gap-2 m-auto justify-content-center position-relative" style="top:3rem;">

    <?php foreach($whatPosts as $post): ?>
    <?php
    $likeCountStmt = $dbconn->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE post_id = :postId");
    $likeCountStmt->bindParam(':postId', $post['ID'], PDO::PARAM_INT);
    $likeCountStmt->execute();
    $likeCount = $likeCountStmt->fetch(PDO::FETCH_ASSOC);

    $saveCountStmt = $dbconn->prepare("SELECT COUNT(*) AS save_count FROM saves WHERE post_id = :postId");
    $saveCountStmt->bindParam(':postId', $post['ID'], PDO::PARAM_INT);
    $saveCountStmt->execute();
    $saveCount = $saveCountStmt->fetch(PDO::FETCH_ASSOC);
    ?>

    <a href="post.php?id=<?php echo $post['ID']; ?>"
        class="card shadow-sm col border mb-5 p-0 text-decoration-none position-relative text-black hoverEffect overflow-hidden z-0"
        style="width: 18rem;">
        <?php
        if (isset($post['image'])) {
          $image = $post['image'];
          echo "<img class='card-img-top' style='height:14rem;' src='https://pub-0130d38cef9c4c1aa3926e0a120c3413.r2.dev/$image' />";
        } else {
          echo "<img src='../image/nedladdning.png' class='card-img-top' />";
        }
        ?>

        <ul class="list-group list-group-flush">
          <h5 class="list-group-item">
            <?php echo $post['title']; ?>
          </h5>
          <li class="list-group-item">Likes:
            <?php echo $likeCount['like_count'] ?>
          </li>
          <li class="list-group-item">Saves:
            <?php echo $saveCount['save_count'] ?>
          </li>
          <li class="list-group-item text-body-secondary">Created by:
            <?php echo $post['created_by'] ?>
          </li>
        </ul>
      </a>
  
    <?php endforeach; ?>
    
</div>
</div>

</body>
</html>