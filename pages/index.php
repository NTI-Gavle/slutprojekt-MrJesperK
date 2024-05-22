<?php
session_start();
require '../db_shenanigans/dbconn.php';
require '../db_shenanigans/thing.php';

$category = "";
$url = htmlspecialchars("index.php?page=");
if (isset($_GET['c'])){
  if ($_GET['c'] == ""){
    header('Location: index.php?c=all&page=1');
  }
    $category = $_GET['c'];
} else {
    header("Location: index.php?c=all&page=1");
}

$page = 1;
if (isset($_GET['page'])){
    if ($_GET['page'] <= 0 || !is_numeric($_GET['page'])){
        header("Location: index.php?c=$category&page=1");
    }
    $page = htmlspecialchars($_GET['page']);
} else {
    header("Location: index.php?c=$category&page=1");
}

$items_per_page = 20; 
$offset = ($page - 1) * $items_per_page;

if ($category == "all") {
    $getTotalPosts = $dbconn->prepare("SELECT COUNT(*) AS postAmount FROM posts");
} else {
    $getTotalPosts = $dbconn->prepare("SELECT COUNT(*) AS postAmount FROM posts WHERE category = :category");
    $getTotalPosts->bindParam(':category', $category, PDO::PARAM_STR);
}
$getTotalPosts->execute();
$totalPosts = $getTotalPosts->fetch(PDO::FETCH_ASSOC)['postAmount'];

$total_pages = ceil($totalPosts / $items_per_page);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search = '%' . $_POST['search'] . '%';
    $searchStmt = $dbconn->prepare("SELECT ID, title, image, description, created_by FROM posts WHERE title LIKE :search ORDER BY ID DESC LIMIT :limit OFFSET :offset");
    $searchStmt->bindParam(':search', $search, PDO::PARAM_STR);
    $searchStmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $searchStmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $searchStmt->execute();
    $posts = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    if ($category == "" || $category == "all") {
        $stmt = $dbconn->prepare("SELECT ID, title, image, description, created_by FROM posts ORDER BY ID DESC LIMIT :limit OFFSET :offset");
    } else {
        $stmt = $dbconn->prepare("SELECT ID, title, image, description, created_by FROM posts WHERE category = :category ORDER BY ID DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generatePaginationLink($page_number, $text, $is_active = false) {
    global $category;
    $active_class = $is_active ? " active" : "";
    return "<li class='page-item$active_class'><a class='page-link' href='index.php?c=" . urlencode($category) . "&page=$page_number'>$text</a></li>";
}

echo $totalPosts;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AMOGUS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="../other_things/script.js" defer></script>
  <link rel="stylesheet" href="../other_things/style.css">
</head>

<body class="m-0 p-0 d-flex flex-column" style="width:100%; height:100vh;">
  <Header class="container-fluid text-center mt-2" style="width:fit-content;">
    <a href="index.php?c=all&page=1" class="text-decoration-none">
      <h2 class="text-black fw-bold">Only&#128405;Fans</h2>
    </a>
  </Header>
  <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom border-top mb-3">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="index.php?c=tower&page=1">Tower Fans</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="index.php?c=table&page=1">Table Fans</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="index.php?c=ceiling&page=1">Ceiling Fans</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="index.php?c=handheld&page=1">Handheld Fans</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <?php if (isset($_SESSION['username'])): ?>
              <button type='button' class='btn' id='modalInput1' data-bs-toggle='modal' data-bs-target='#PostModal' onclick='modal1()'>New Post</button>
            <?php endif; ?>
          </li>
          <li class="nav-item">
            <?php if (isset($_SESSION['username'])): ?>
              <?php if ($_SESSION['admin'] === 'Y'): ?>
                <a href='admin.php' class='btn btn-warning'>Admin</a>
                <li class='nav-item'><a class='btn' href='account.php?&p=saved&page=1'><?= $_SESSION['username'] ?></a></li>
              <?php else: ?>
                <a href='account.php?p=saved&page=1' class='btn'><?= $_SESSION['username'] ?></a>
              <?php endif; ?>
            <?php else: ?>
              <button class='nav-link btn float-start' id='modalInput2' data-bs-toggle='modal' data-bs-target='#LoginModal' onclick='modal2()'>Login</button>
            <?php endif; ?>
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
        <form id="postForm" method="post" enctype="multipart/form-data" onsubmit="return post(event)">
          <div class="modal-body d-flex flex-column mb-3 gap-3">
            <a href="tos.php" class="text-danger text-decoration-underline">READ BEFORE YOU POST</a>
            <input type="file" name="image" id="image" required>
            <input type="text" name="title" id="title" placeholder="title" required>
            <textarea name="description" id="description" cols="40" rows="3" placeholder="description" required></textarea>
            <select name="category" id="category">
              <option value="Table">Table Fan</option>
              <option value="Ceiling">Ceiling Fan</option>
              <option value="Tower">Tower Fan</option>
              <option value="Handheld">Handheld Fan</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Post</button>
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

  <div class="row row-cols-6 column-gap-5 row-gap-2 m-auto justify-content-center position-relative" style="top:3rem;" id="postList">

  <?php if (empty($posts)): ?>
    <h3 class="mt-4 text-center m-0 w-100">
        Nothing to see here
  </h3>
      <?php else: ?>
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

  </a>
<?php endforeach; ?>
<?php endif;?>



</div>

  <div class="container mt-4">
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">

        <?php if ($page > 1): ?>
          <?= generatePaginationLink($page - 1, "Previous") ?>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?= generatePaginationLink($i, $i, $i == $page) ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <?= generatePaginationLink($page + 1, "Next") ?>
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
