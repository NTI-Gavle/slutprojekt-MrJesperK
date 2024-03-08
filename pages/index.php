<?php
require '../db_shenanigans/dbconn.php';



$sql = "SELECT ID, title FROM posts ORDER BY ID DESC";

$stmt = $dbconn->prepare($sql);

$stmt->execute();

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['title']) && isset($_POST['description'])){

        $title = $_POST['title'];
        $descr = $_POST['description'];

            $stmt2 = $dbconn->prepare("INSERT INTO posts (title, decription) VALUES (:title, :descr)");
            $stmt2->bindParam(":title", $title);
            $stmt2->bindParam(":descr", $descr);
            $stmt2->execute();

    }
}

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
<body class="m-0 p-0" style="width:100%; height:100%;">

    <Header class="container-fluid border-bottom text-center">
        <a href="#" class="text-decoration-none "><h2 class="text-black fw-bold">Only&#128405;Fans</h2></a>
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
            <button class="nav-link btn" data-bs-toggle='modal' data-bs-target='#LoginModal' onclick='modal2()'>Login</button>
        </li>
        <li class="nav-item">
        <?php 
  
            echo "<button type='button' class='btn' data-bs-toggle='modal' data-bs-target='#PostModal' onclick='modal1()'>
            New Post
          </button>";
        
        ?>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
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
      <form action="#" method="post">
      <div class="modal-body d-flex flex-column mb-3 gap-3">
        <input type="file" name="image" id="image">
        <input type="text" name="title" id="title" placeholder="--Title--">
        <textarea name="descriptiom" id="description" cols="30" rows="4" placeholder="description"></textarea>
        <select name="category" id="category">
            <option value="1">--Choose Category--</option>
            <option value="2">Tower fan</option>
            <option value="3">Table fan</option>
            <option value="4">Ceiling fan</option>
            <option value="5">Handheld fan</option>
        </select>
      </div>
      <div class="modal-footer">
        <img src="../image/sus.png" alt="sus" style="width:3rem; height:3rem;">
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
      <form action="#" method="post" class="">
      <div class="modal-body d-flex flex-column mb-3 gap-3">
        <input type="text" name="username" id="username" placeholder="--Username--">
        <input type="text" name="password" id="password" placeholder="--Password--">
        <a href="register.php">No account?</a>
        <a href="#">Forgot password?</a>

      </div>
      <div class="modal-footer">
        <img src="../image/sus.png" alt="sus" style="width:3rem; height:3rem;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="Login">Login</button>
      </div>
      </form>
    </div>
  </div>
</div>


<div class="container text-center p-0">
<div class="row row-cols-6 gap-5 m-auto justify-content-center" style="width:fit-content;">

    <?php foreach($posts as $post): ?>
    <a style="height:13rem; width:fit-content;" class='col border p-0 text-decoration-none text-black position-relative hoverEffect' href="post.php?id=<?php echo $post['ID']; ?>">
    <img src='../image/nedladdning.png' alt='img' style="height:9.5rem; width:9.5rem;"></img>
    <hr class='mt-0'>
    <h5><?php echo $post['title']; ?></h5>
    </a>
    
    <?php endforeach; ?>
    
</div>
</div>

</body>
</html>