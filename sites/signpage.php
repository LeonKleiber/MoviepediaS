<?php 
include("../include/session.inc.php");
include("../include/dbconnector.inc.php");

if($_SERVER["REQUEST_METHOD"] == "GET"){
  $query = "SELECT * FROM pages WHERE id = ?"; 
  $id = $_GET['id'];
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows){
    while ($row = $result->fetch_assoc()){
      $page = $row;
    }
  }
  $stmt->close();

  if($page['approved']&& !isMod()){
    header("Location:./home.php");
  }


} else if($_SERVER["REQUEST_METHOD"] == "POST"){
  $parent = NULL;
  $approve= true;
  $changeQuery = "UPDATE PAGES SET approved=?, parent=? WHERE id = ?";
  $changestmt = $mysqli->prepare($changeQuery);
  $changestmt->bind_param('iii', $approve, $parent, $id);
  $changestmt->execute();
  $changestmt->close();
  //parent wird gelöscht
  if(isset($page['parent'])){
    header("Location:./delete.php?id=".$page['parent']);
  } else{
    header("Location:./home.php");
  }
  
  } else{
    header("Location:./home.php");
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/header.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/yourcode.js"></script>
  <title>Moviepedia</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
  <a class="navbar-brand" href="../sites/home.php">Moviepedia</a>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php
      if(isLoggedIn()){
        $tabs='<li class="nav-item active">
          <a class="nav-link" href="../sites/create.php">Create Page<span class="sr-only">(current)</span></a>
          </li>';
        if(isMod()){
          $tabs.='<li class="nav-item active">
            <a class="nav-link" href="../sites/sign.php">Sign Pages<span class="sr-only">(current)</span></a>
            </li>';
        }
        $tabs.='<li class="nav-item active">
          <a class="nav-link" href="../sites/account.php">Account<span class="sr-only">(current)</span></a>
          </li>';
        echo $tabs;
      } else {
        echo('<li class="nav-item active">
        <a class="nav-link" href="../sites/login.php">Log In <span class="sr-only">(current)</span></a>
        </li>');
      }

      ?>
    </ul>
  </div>
</nav>
  <main>
  <div class="container p-3">
      <h1>
        <?php echo $page['title']; ?>
      </h1>
      <p class="text-break"> <?php echo $page['text']; ?> </p>
      <form action="./signpage.php" method="post">
        <button type="submit" name="button" value="submit" class="btn btn-info">Approve</button>
      </form>
     <a class='btn btn-danger' href='./delete.php?id=<?php echo $page["id"] ?>'> Delete </a>
      </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>