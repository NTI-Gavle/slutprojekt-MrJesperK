<?php
session_start();
require '../db_shenanigans/dbconn.php';

if (isset($_SESSION['username']) && $_SESSION['admin'] === 'N'){
    header('Location: index.php');
}
elseif (!isset($_SESSION['username'])) {
    header('Location: index.php');
}

$sql = "SELECT ID, title, image, created_by FROM posts ORDER BY ID DESC";

$stmt = $dbconn->prepare($sql);

$stmt->execute();

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);



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
            <li><a class="dropdown-item" href="#"><b>all Fans</b></a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Tower Fans</a></li>
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
            echo "<button class='nav-link btn' data-bs-toggle='modal' data-bs-target='#LoginModal' onclick='modal2()'>Login</button>";
          } 
          else {
            echo "<a href='account.php' class='btn'>Account</a>";
          }
          ?>
        </li>
        <li class="nav-item">
        <?php 
        if (isset($_SESSION['username'])){
            echo "<button type='button' class='btn' data-bs-toggle='modal' data-bs-target='#PostModal' onclick='modal1()'>
            New Post
          </button>";
        }
        
        ?>
        </li>
        <li class="nav-item">
        <?php 
  
          
        
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

<div class="container text-center p-0">
<div class="row row-cols-6 m-auto justify-content-center position-relative" style="top:3rem; gap:5rem!important;">

    <?php foreach($posts as $post): ?>
    <a style="height:20rem;"class='col border p-0 text-decoration-none position-relative text-black hoverEffect overflow-hidden z-0' href="post.php?id=<?php echo $post['ID']; ?>" >
    <?php 
    if (isset($post['image'])){
    echo '<img class="object-fit-cover" style="height:14rem;" src="data:image/jpeg;base64,'.base64_encode($post['image']).'" />';
    }
    else {
      echo "<img src='../image/nedladdning.png' class='object-fit-cover' />";
    }
    ?>
    <hr class='mt-0 z-1'>
    <p class="z-1 bg-body-white text-break position-relative mb-0 p-2 text-start" style="bottom:1rem;"><?php echo $post['title']; ?></p>
    <p class="z-1 bg-body-white text-body-secondary position-relative text-end p-2"><?php echo "Posted by: " . $post['created_by']; ?></p>
    </a>
    <?php endforeach; ?>
    
</div>
</div>



</body>
</html>