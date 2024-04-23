<?php
session_start();
require '../db_shenanigans/dbconn.php';
require '../db_shenanigans/thing.php';
$category = "";
if (isset($_GET['c'])){
$category = $_GET['c'];
} else {
  
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
  $search = '%' . $_POST['search'] . '%';

  $searchStmt = $dbconn->prepare("SELECT ID, title, image, description, created_by FROM posts WHERE title LIKE ? ORDER BY ID DESC");

  $searchStmt->bindParam(1, $search, PDO::PARAM_STR);

  $searchStmt->execute();

  $posts = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $dbconn->prepare("SELECT ID, title, image, description, created_by FROM posts ORDER BY ID DESC");
  $stmt->execute();
  $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
if ($category == "" || $category == null){
  $stmt = $dbconn->prepare("SELECT ID, title, image, description, created_by FROM posts ORDER BY ID DESC");
  $stmt->execute();
  $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  $stmt = $dbconn->prepare("SELECT ID, title, image, description, created_by FROM posts WHERE category = :category ORDER BY ID DESC");
  $stmt->bindParam(':category', $category, PDO::PARAM_STR);
  $stmt->execute();
  $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
  
}


$post_id = $dbconn->prepare("SELECT ID FROM posts");
$post_id->execute();
$post_idRes = $post_id->fetch(PDO::FETCH_ASSOC);
$postId = $post_idRes['ID'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AMOGUS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="../other_things/script.js" defer></script>
  <link rel="stylesheet" href="../other_things/style.css">
</head>

<body class="m-0 p-0 d-flex flex-column" style="width:100%; height:100vh;">

  <Header class="container-fluid text-center mt-2" style="width:fit-content;">
    <a href="#" class="text-decoration-none ">
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
            <?php
            if (isset($_SESSION['username'])) {
              echo "<button type='button' class='btn' id='modalInput1' data-bs-toggle='modal' data-bs-target='#PostModal' onclick='modal1()'>
            New Post
          </button>";
            }

            ?>
          </li>
          <li class="nav-item">
            <?php
            if (isset($_SESSION['username'])) {
              if ($_SESSION['admin'] === 'Y') {
                echo "<a href='admin.php' class='btn btn-warning'>Admin</a>";
                echo "<li class='nav-item'><a class='btn' href='account.php?user=" . $_SESSION['user_id'] . "&p=saved'>" . $_SESSION['username'] . "</a></li>";
              } else {
                echo "<a href='account.php?user=" . $_SESSION['username'] . "&p=saved' class='btn'>" . $_SESSION['username'] . "</a>";
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

  <!-- Modal -->
  <div class="modal fade" id="PostModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="ModalLabel">Create Post</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../db_shenanigans/upload.php" id="postForm" method="post" enctype="multipart/form-data" onsubmit="return post(event)">
          <div class="modal-body d-flex flex-column mb-3 gap-3">
            <input type="file" name="image" id="image" required>
            <input type="text" name="title" id="title" placeholder="--Title--" maxlength="20" required>
            <textarea name="description" id="description" cols="30" rows="4" placeholder="--description--"
              maxlength="150" required style="resize:none;"></textarea>
            <select name="category" id="category" required>
              <option value="None">--Choose Category--</option>
              <option value="Tower">Tower fan</option>
              <option value="Table">Table fan</option>
              <option value="Ceiling">Ceiling fan</option>
              <option value="Handheld">Handheld fan</option>
            </select>
          </div>
          <div class="modal-footer">
            <img src="../image/sus.png" alt="sus" style="width:3rem; height:3rem;">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="Post" id="Post">Post</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="ModalLabel">Login</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" id="login" onsubmit="return login(event)">
        <p id="error" class="fw-bold text-danger text-center m-0 mt-3"></p>
          <div class="modal-body d-flex flex-column mb-3 gap-3">
            <input type="text" name="username" id="username" placeholder="--Username--">
            <input type="password" name="password" id="password" placeholder="--Password--">
            <div class="container d-flex flex-row">
              <label for="showPass" id="passLabel" style="margin-bottom:1.17rem;">Show password: </label>             
              <input id="showPass" class="mb-3 ms-2" type="checkbox" onclick="Shenanigans()">
            </div>
            <a href="register.php">No account?</a>
            <a href="passreset.php">Forgot password?</a>

          </div>
          <div class="modal-footer">
            <img src="../image/sus.png" alt="sus" style="width:3rem; height:3rem;">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="Login" id="thing">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <h2 class="text-center m-auto mt-4 text-decoration-underline"><?php if (isset($_GET['c'])){echo $category." fans";}else{echo "All fans";} ?></h2>

  <h3 class="mt-4 text-center">
    <?php if (empty($posts)): ?>
        Nothing to see here
    <?php endif; ?>
  </h3>

  <div class="row row-cols-6 column-gap-5 row-gap-2 m-auto justify-content-center position-relative" style="top:3rem;" id="postList">

    <?php foreach ($posts as $post): ?>

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
        <!-- <div class="card-body">
    <p class="card-title text-center"><?php echo $post['description']; ?></p>
  </div> -->
      </a>
    <?php endforeach; ?>
  </div>

  <footer class="container-fluid bg-body-tertiary border-top border-black mt-5 position-relative bottom-0">
    <h1 class="text-center align-middle fw-bold text-decoration-underline">&copy;BALLS</h1>
  </footer>

</body>

</html>