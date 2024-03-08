<?php 
require '../db_shenanigans/dbconn.php';

if (isset($_GET['id'])){
$post_id = $_GET['id'];

$sql = "SELECT title FROM posts WHERE ID = :post_id";

$stmt = $dbconn->prepare($sql);

$stmt->bindParam(':post_id', $post_id);
$stmt->execute();

$post_data = $stmt->fetch(PDO::FETCH_ASSOC);


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $post_data['title'];?></title>
</head>
<body>
    <?php echo "$post_data[title]"; 

    ?>


</body>
</html>