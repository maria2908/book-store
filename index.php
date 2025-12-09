<?php
require_once('./partials/header.php');

session_start();

if (!$_SESSION['user_id']) {
    header("Location: ./pages/login.php");
}
?>
<a href="./pages/logout.php">Log out</a>
<div>HI</div>
  <img style="height: 30vh; width: 30%;" scr='assets/img/login_logout.webp'/>  

<?php
require_once('./partials/footer.php');
?>