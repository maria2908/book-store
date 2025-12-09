<?php
require_once('../partials/header.php');
require_once('../classes//Login.php');

$emailError = '';

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $user = new Login();

    $result = $user->forgot_password($email);
    if ($result === true) {
      echo "<script type='text/javascript'>alert('Check your Email to reset the password');</script>";
      exit(); 
    } else {
        $emailError = $result;
    }
}


?>

<div class="bg-cover bg-center" style="background-image: url('../assets/img/login_logout.webp'); height: 100vh;" >
  <div class="h-full flex flex-col justify-center px-6 py-12 lg:px-8 text-white" style="background-color:rgba(0, 0, 0, 0.5);">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm " style="opacity: 1;">
      <h2 class="mt-10 text-center text-3xl/9 font-bold tracking-tight">Please Enter Your Email</h2>
    </div>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form method="POST" action="forgot_password.php" class="space-y-6">
        <div>
          <label for="email" class="block text-sm/6 font-medium">Email</label>
          <div class="mt-2">
            <input id="email" type="email" name="email" required autocomplete="email" class="block text-black w-full rounded-md bg-white px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-black/10 focus:outline-2 focus:-outline-offset-2 focus:outline-blood-red-light sm:text-sm/6" />
          </div>
          <div><?php echo $emailError ?></div>
        </div>

        <div>
          <button type="submit" 
                  class="bg-blood-red flex w-full justify-center rounded-md  px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-blood-red-light">
          Send Email</button>
        </div>
      </form>
    </div>
  </div>
</div>