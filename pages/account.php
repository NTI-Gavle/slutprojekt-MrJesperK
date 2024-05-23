<?php
require '../db_shenanigans/dbconn.php';
require '../db_shenanigans/thing.php';

if (!isset($_GET['p'])){
  header("Location: account.php?p=saved");
}
if (!isset($_SESSION['username'])){
  header("Location: index.php");
}
if (isset($_GET['c'])){
  $category = $_GET['c'];
  } else {
    header("Location: account.php?c=all&page=1&p=liked");
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

if (isset($_GET['page'])){
  if ($_GET['page'] <= 0 || !is_numeric($_GET['page'])){
    header("Location: account.php?page=1");
  }
  $page = htmlspecialchars($_GET['page']);
} else {
  header('Location: account.php?page=1');
}

$items_per_page = 20; 
$offset = ($page - 1) * $items_per_page;

if ($category == "all") {
    $getTotalPosts = $dbconn->prepare("SELECT COUNT(*) AS postAmount FROM posts WHERE created_by = :user");
    $getTotalPosts->bindParam(':user', $_SESSION['username']);
} else {
    $getTotalPosts = $dbconn->prepare("SELECT COUNT(*) AS postAmount FROM posts WHERE category = :category AND created_by = :user");
    $getTotalPosts->bindParam(':user', $_SESSION['username']);
    $getTotalPosts->bindParam(':category', $category, PDO::PARAM_STR);
}
$getTotalPosts->execute();
$totalPosts = $getTotalPosts->fetch(PDO::FETCH_ASSOC)['postAmount'];

$total_pages = ceil($totalPosts / $items_per_page);

$whatPosts = $_GET['p'];

$user_id = $_SESSION['user_id'];
if ($whatPosts == 'saved' || $whatPosts != 'liked'){
$getSavesStmt = $dbconn->prepare("SELECT posts.* FROM saves INNER JOIN posts ON saves.post_id = posts.ID WHERE saves.user_id = :user_id ORDER BY ID DESC LIMIT 20 OFFSET :offset");
$getSavesStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$getSavesStmt->bindParam('offset', $offset, PDO::PARAM_INT);
$getSavesStmt->execute();
$whatPosts = $getSavesStmt->fetchAll(PDO::FETCH_ASSOC);
} elseif($whatPosts == 'liked'){
$getLikesStmt = $dbconn->prepare("SELECT posts.* FROM likes INNER JOIN posts ON likes.post_id = posts.ID WHERE likes.user_id = :user_id ORDER BY ID DESC LIMIT 20 OFFSET :offset");
$getLikesStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$getLikesStmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$getLikesStmt->execute();
$whatPosts = $getLikesStmt->fetchAll(PDO::FETCH_ASSOC);
}

function generatePaginationLink($page_number, $text,  $p, $is_active = false) {
  global $category;
  $active_class = $is_active ? " active" : "";
  return "<li class='page-item$active_class'><a class='page-link' href='account.php?&p=$p&c=" . urlencode($category) . "&page=$page_number'>$text</a></li>";
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
<body class="m-0 p-0 d-flex flex-column" style="width:100%; height:100vh;">
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
              <li><a class="dropdown-item" href="account.php?c=all&page=1&p=<?php echo $_GET['p']?>"><b>all Fans</b></a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="account.php?c=tower&page=1&p=<?php echo $_GET['p']?>">Tower Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="account.php?c=table&page=1&p=<?php echo $_GET['p']?>">Table Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="account.php?c=ceiling&page=1&p=<?php echo $_GET['p']?>">Ceiling Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="account.php?c=handheld&page=1&p=<?php echo $_GET['p']?>">Handheld Fans</a></li>
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
          <li class="nav-item">
            <form method="post">
              <button class="btn text-black" type="submit" name="logout" id="logout">Logout</button>
              </form>
          </li>
          <li class="nav-item">
          <button class="btn text-danger" data-bs-target="#DeleteUserModal" data-bs-toggle="modal" onclick="modal5()" >Delete Account</button>          </li>
        </ul>
        <form class="d-flex phoneSearch" role="search" id="searchForm" onsubmit="return searching(event)" method="post">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

      </div>
    </div>
  </nav>



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
    <li><a class="dropdown-item" href="account.php?p=<?php echo "saved&page=".$_GET['page']."&c=".$category?>">Saved</a></li>
    <li class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="account.php?p=<?php echo "liked&page=".$_GET['page']."&c=".$category?>">Liked</a></li>
  </ul>
</div>
</div>
<p class="mt-4 mx-auto position-relative">
    <?php if (empty($whatPosts)): ?>
      <div
        class="m-auto card shadow-sm col border mb-5 p-0 text-decoration-none position-relative text-black hoverEffect overflow-hidden z-0"
        style="width: 18rem;">
        <?php

          echo "<img src='../image/nedladdning.png' class='card-img-top' />";
        
        ?>

        <ul class="list-group list-group-flush">
          <h5 class="list-group-item">
            <?php echo "Nothing here!" ?>
          </h5>
          <li class="list-group-item">Likes:
            <?php echo "0" ?>
          </li>
          <li class="list-group-item">Saves:
            <?php echo "0" ?>
          </li>
          <li class="list-group-item text-body-secondary">Created by:
            <?php echo "Admins" ?>
          </li>
        </ul>
      </div>
    <?php endif; ?>
</p>
<div class="row row-cols-6 column-gap-5 row-gap-2 m-auto justify-content-center position-relative" style="top:3rem;">

<?php foreach ($whatPosts as $post): ?>

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
        <span class="text-secondary float-end me-2 fs-6 fw-medium"><?php echo $post['category']?> fan</span>
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

<div class="container mt-4">
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">

        <?php if ($page > 1): ?>
          <?= generatePaginationLink($page - 1, "Previous", $_GET['p']) ?>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?= generatePaginationLink($i, $i, $_GET['p'], $i == $page) ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <?= generatePaginationLink($page + 1, "Next", $_GET['p']) ?>
        <?php endif; ?>

      </ul>
    </nav>
  </div>


<footer class="container-fluid bg-body-tertiary border-top border-black mt-5 position-relative bottom-0">
    <p class="ps-2 pt-1">&copy; 2024-<?php echo date("Y");?></p>
    <p class="ps-2">some random guy</p>
    <a class="text-black m-0 ps-2 pb-1" href="tos.php">Terms of service</a>
  </footer>

</body>
</html>