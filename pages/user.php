<?php
 require '../db_shenanigans/dbconn.php';

 $user = htmlspecialchars($_GET['u']);
 if (!isset($_GET['posts'])){
  header('Location: user.php?u='.$user.'&posts=liked&c=all&page=1');
}

$fetchUserID = $dbconn->prepare("SELECT ID FROM users WHERE username = :username");
$fetchUserID->bindParam(':username', $user, PDO::PARAM_STR);
$fetchUserID->execute();
$user_id = $fetchUserID->fetch();

$page = $_GET['page'];
$category = $_GET['c'];

$items_per_page = 20; 
$offset = ($page - 1) * $items_per_page;

if ($_GET['posts'] == "liked") {
if ($category == "all") {
    $getTotalPosts = $dbconn->prepare("SELECT COUNT(*) AS postAmount FROM likes INNER JOIN posts ON posts.ID = post_id WHERE likes.user_id = :user");
    $getTotalPosts->bindParam(':user', $user_id['ID'], PDO::PARAM_INT);
} else {
    $getTotalPosts = $dbconn->prepare("SELECT COUNT(*) AS postAmount FROM likes INNER JOIN posts ON posts.ID = post_id WHERE likes.user_id = :user AND category = :category");
    $getTotalPosts->bindParam(':user', $user_id['ID'], PDO::PARAM_INT);
    $getTotalPosts->bindParam(':category', $category, PDO::PARAM_STR);
}
} elseif ($_GET['posts'] == "posted") {
  if ($category == "all"){
  $getTotalPosts = $dbconn->prepare("SELECT COUNT(*) as postAmount FROM posts WHERE created_by = :user");
  $getTotalPosts->bindParam(':user', $user, PDO::PARAM_STR);
  } else {
    $getTotalPosts = $dbconn->prepare("SELECT COUNT(*) as postAmount FROM posts WHERE created_by = :user AND category = :category");
    $getTotalPosts->bindParam(':user', $user, PDO::PARAM_STR);
    $getTotalPosts->bindParam(':category', $category, PDO::PARAM_STR);
  }
} else {
  header('Location: user.php?u='.$user.'&posts=liked&c=all&page=1');
}
$getTotalPosts->execute();
$totalPosts = $getTotalPosts->fetch(PDO::FETCH_ASSOC)['postAmount'];

$total_pages = ceil($totalPosts / $items_per_page);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])){
  $search = '%' . $_POST['search'] . '%';
  $searchStmt = $dbconn->prepare("SELECT * FROM posts WHERE title LIKE :search AND created_by = :user ORDER BY ID DESC LIMIT :limit OFFSET :offset");
  $searchStmt->bindParam(':search', $search, PDO::PARAM_STR);
  $searchStmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
  $searchStmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  $searchStmt->bindParam(':user', $user, PDO::PARAM_STR);
  $searchStmt->execute();
  $userPosts = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  if ($category == "" || $category == "all" && $_GET['posts'] == "liked"){
    $stmt = $dbconn->prepare("SELECT posts.* FROM likes INNER JOIN posts ON posts.ID = post_id WHERE likes.user_id = :user ORDER BY ID DESC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':user', $user_id['ID'], PDO::PARAM_INT);
    $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  } elseif ($category != "" || $category != "all" && $_GET['posts'] == "liked") {
    $stmt = $dbconn->prepare("SELECT posts.* FROM likes INNER JOIN posts ON posts.ID = post_id WHERE likes.user_id = :user AND category = :category ORDER BY ID DESC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':user', $user_id['ID'], PDO::PARAM_INT);
    $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
  } 
  $stmt->execute();
  $userLikedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if ($category == "" || $category == "all" && $_GET['posts'] == "posted"){
    $stmt2 = $dbconn->prepare("SELECT * FROM posts WHERE created_by = :user ORDER BY ID DESC LIMIT :limit OFFSET :offset");
    $stmt2->bindParam(':user', $user, PDO::PARAM_INT);
    $stmt2->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt2->bindParam(':offset', $offset, PDO::PARAM_INT);
  } elseif ($category != "" || $category != "all" && $_GET['posts'] == "posted") {
    $stmt2 = $dbconn->prepare("SELECT * FROM posts WHERE created_by = :user AND category = :category ORDER BY ID DESC LIMIT :limit OFFSET :offset");
    $stmt2->bindParam(':user', $user, PDO::PARAM_INT);
    $stmt2->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt2->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt2->bindParam(':category', $category, PDO::PARAM_STR);
  } 
  $stmt2->execute();
  $userPostedPosts = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}

 $fetchUserPostsStmt = $dbconn->prepare("SELECT * FROM posts WHERE created_by = :user ORDER BY id DESC LIMIT :limit OFFSET :offset");
 $fetchUserPostsStmt->bindParam(':user', $user, PDO::PARAM_STR);
 $fetchUserPostsStmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
 $fetchUserPostsStmt->bindParam(':offset', $offset, PDO::PARAM_INT);
 $fetchUserPostsStmt->execute();
 $userPostedPosts = $fetchUserPostsStmt->fetchAll(PDO::FETCH_ASSOC);

 function generatePaginationLink($page_number, $text, $user, $posts, $is_active = false) {
  global $category;
  $active_class = $is_active ? " active" : "";
  return "<li class='page-item$active_class'><a class='page-link' href='user.php?posts=" . $posts ."&u=" . $user . "&c=" . urlencode($category) . "&page=$page_number'>$text</a></li>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../other_things/style.css">
    <script src="../other_things/script.js"></script>
    <title><?php echo $user ?></title>
</head>
<body>

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
              <li><a class="dropdown-item" href="user.php?c=all&page=1&posts=<?php echo $_GET['posts']?>&u=<?php echo $user?>"><b>all Fans</b></a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="user.php?c=tower&page=1&posts=<?php echo $_GET['posts']?>&u=<?php echo $user?>">Tower Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="user.php?c=table&page=1&posts=<?php echo $_GET['posts']?>&u=<?php echo $user?>">Table Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="user.php?c=ceiling&page=1&posts=<?php echo $_GET['posts']?>&u=<?php echo $user?>">Ceiling Fans</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="user.php?c=handheld&page=1&posts=<?php echo $_GET['posts']?>&u=<?php echo $user?>">Handheld Fans</a></li>
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

  <div class="dropdown-center text-center position-relative">
    <h3><?php echo $user."'s"?></h3>
  <button class="btn dropdown-toggle btn-lg border border-secondary shadow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
  <?php echo $_GET['posts'] ?>
  </button>
  <h3>posts</h3>
  <ul class="dropdown-menu text-center">   
    <li><a class="dropdown-item" href="user.php?u=<?php echo $user?>&posts=liked&page=1&c=<?php echo $category?>">Liked</a></li>
    <li class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="user.php?u=<?php echo $user?>&posts=posted&page=1&c=<?php echo $category?>">Posted</a></li>
  </ul>
</div>

  <div class="row row-cols-6 column-gap-5 row-gap-2 m-auto justify-content-center position-relative" style="top:3rem;">
<?php if ($_GET['posts'] == 'liked'):?>
  <?php foreach($userLikedPosts as $post):?>
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
        <!-- <div class="card-body">
    <p class="card-title text-center"><?php echo $post['description']; ?></p>
  </div> -->
      </a>
  <?php endforeach ?>
  <?php endif; ?>
  </div>
  <div class="row row-cols-6 column-gap-5 row-gap-2 m-auto justify-content-center position-relative" style="top:3rem;">
<?php if ($_GET['posts'] == 'posted'):?>
  <?php foreach($userPostedPosts as $post):?>
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
  <?php endforeach ?>
  <?php endif; ?>
  </div>



  <div class="container mt-4">
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">

        <?php if ($page > 1): ?>
          <?= generatePaginationLink($page - 1, "Previous", $user, $_GET['posts']) ?>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?= generatePaginationLink($i, $i, $user, $_GET['posts'], $i == $page) ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <?= generatePaginationLink($page + 1, "Next", $user, $_GET['posts']) ?>
        <?php endif; ?>

      </ul>
    </nav>
  </div>




  <div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="<?php if (isset($_SESSION['username'])){ echo "true"; } else {echo "true";}?>">
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
<br>
  <footer class="container-fluid bg-light bg-gradient border-top border-black mt-5 position-relative bottom-0">
    <p class="ps-2 pt-1">&copy; 2024-<?php echo date("Y");?></p>
    <p class="ps-2">some random guy</p>
    <a class="text-black m-0 ps-2 pb-1" href="tos.php">Terms of service</a>
  </footer>

</body>
</html>