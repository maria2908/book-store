<?php
require_once('../partials/header.php');
require_once('../functions.php');
require_once('../classes/Login.php');


$login = new Login;

$passwordValidation = [];
$emailError = '';
if($_SERVER['REQUEST_METHOD'] == "POST") {
  $email = isset($_POST['email']) ? $_POST['email'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  if (empty($_POST["email"]) && !isset($_POST['email'])) {
    $emailError = "Email is required";
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailError = "Invalid email format";
    } else {
      $passwordValidation = validatePassword($password);

      if($passwordValidation !== true) {
        $passwordError = $passwordValidation;
      } else {
        $registerResult = $login->register($email, $password);

        if($registerResult === true) {
    
            header("Location: ./index.php");
            exit();
        } else {
            $emailError = $registerResult;
        }
      }
    }
  }
}

?>

<div class="bg-cover bg-center" style="background-image: url('../assets/img/login_logout.webp'); height: 100vh;" >
  <div class="h-full flex flex-col justify-center px-6 py-12 lg:px-8 text-white" 
        style="background-color:rgba(0, 0, 0, 0.5);">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm " style="opacity: 1;">
      <h2 class="mt-10 text-center text-3xl/9 font-bold tracking-tight">Sign in to your account</h2>
    </div>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form method="POST" action="login.php" class="space-y-6">
        <div>
          <label for="email" class="block text-sm/6 font-medium">Email address</label>
          <div class="mt-2">
            <input id="email" type="email" name="email" required autocomplete="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-black/10 focus:outline-2 focus:-outline-offset-2 focus:outline-blood-red-light sm:text-sm/6" />
          </div>
          <div><?php echo $emailError ?></div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm/6 font-medium">Password</label>
          </div>
          <div class="mt-2">
            <input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-black/10 focus:outline-2 focus:-outline-offset-2 focus:outline-blood-red-light sm:text-sm/6 " />
          </div>
          <?php if (!empty($passwordError)): ?>
            <?php foreach($passwordError as $error): ?>
                <?php echo $error ?>
            <?php endforeach ?>
          <?php endif; ?>
        </div>

        <div>
          <button type="submit" 
                  class="bg-blood-red flex w-full justify-center rounded-md  px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-blood-red-light">
          Sign in</button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm/6 text-gray-400">
        You have an account?
        <a href="login.php" class="text-blood-red-light font-semibold">Sign in</a>
      </p>
    </div>
  </div>
</div>

<?php
require_once('../partials/footer.php');
?>