
<section class="sign-in-up">
  <h2 class="hidden">Sign up</h2>
  <form class="sign-in-up__form" action="index.php?page=signUp" method="POST">
    <h3>sign up</h3>
    <label for="sign-up__username">Username</label>
    <input type="text" name="username" id="sign-up__username" placeholder="username">
    <span class="validation--error"><?php if (!empty($errors['username'])) echo $errors['username']?></span>
    <label for="sign-in__password">Password</label>
    <input type="password" name="password" id="sign-up__password" placeholder="password">
    <span class="validation--error"><?php if (!empty($errors['password'])) echo $errors['password']?></span>
    <label for="sign-in__password-confirm">Confirm password</label>
    <input type="password" name="confirm_password" id="sign-up__password-confirm" placeholder="confirm password">
    <span class="validation--error"><?php if (!empty($errors['confirm_password'])) echo $errors['confirm_password']?></span>
    <input class="button" type="submit" value="Sign me up">
  </form>

  <div class="sign-in-up__referal">
    <span>already have an account?</span>
    <a class="sign-in-up__link" href="index.php?page=signIn">Sign in</a>
  </div>
</section>
