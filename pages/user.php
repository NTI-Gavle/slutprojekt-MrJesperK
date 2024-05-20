<?php
 require '../db_shenanigans/dbconn.php';

 $user = $_GET['u'];
 if (!isset($_GET['posts'])){
  header('Location: user.php?u='.$user.'&posts=liked');
}
 $fetchUserID = $dbconn->prepare("SELECT ID FROM users WHERE username = :username");
 $fetchUserID->bindParam(':username', $user, PDO::PARAM_STR);
 $fetchUserID->execute();
 $user_id = $fetchUserID->fetch();

 $fetchUserLikesStmt = $dbconn->prepare("SELECT posts.* FROM likes INNER JOIN posts ON posts.ID = post_id WHERE likes.user_id = :user ORDER BY ID DESC");
 $fetchUserLikesStmt->bindParam(':user', $user_id['ID'], PDO::PARAM_INT);
 $fetchUserLikesStmt->execute();
 $userLikedPosts = $fetchUserLikesStmt->fetchAll(PDO::FETCH_ASSOC);

 $fetchUserPostsStmt = $dbconn->prepare("SELECT * FROM posts WHERE created_by = :user");
 $fetchUserPostsStmt->bindParam(':user', $user, PDO::PARAM_STR);
 $fetchUserPostsStmt->execute();
 $userPostedPosts = $fetchUserPostsStmt->fetchAll(PDO::FETCH_ASSOC);
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

  <div class="dropdown-center text-center position-relative">
    <h3><?php echo $user."'s"?></h3>
  <button class="btn dropdown-toggle btn-lg border border-secondary shadow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
  <?php echo $_GET['posts'] ?>
  </button>
  <h3>posts</h3>
  <ul class="dropdown-menu text-center">   
    <li><a class="dropdown-item" href="user.php?u=<?php echo $user?>&posts=liked">Liked</a></li>
    <li class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="user.php?u=<?php echo $user?>&posts=posted">Posted</a></li>
  </ul>
</div>

  <h3 class="mt-4 text-center">
    <?php if (empty($userLikedPosts) || empty($userPostedPosts)): ?>
        Nothing to see here
    <?php endif; ?>
  </h3>

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
</body>
</html>