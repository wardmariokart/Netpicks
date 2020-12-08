
<section class="sign-in-up">
  <h2 class="hidden">sign in</h2>
  <form class="sign-in-up__form" action="index.php?page=signIn" method="POST">
    <h3>sign in</h3>
    <label class="sign-in-up__label" for="sign-in__username">username</label>
    <input class="sign-in-up__field" id="sign-in__username" name="username" type="text" placeholder="username">
    <span class="validation--error"><?php if (!empty($errors['username'])) echo $errors['username']?></span>
    <label class="sign-in-up__label" for="sign-in__password">Password</label>
    <input class="sign-in-up__field" type="password" id="sign-in__password" name="password" placeholder="password">
    <span class="validation--error"><?php if (!empty($errors['password'])) echo $errors['password']?></span>
    <input class="button" type="submit" value="sign in">
  </form>

  <div class="sign-in-up__referal">
    <span>Don't have an account?</span>
    <a class="sign-in-up__link" href="index.php?page=signUp">Sign up</a>
  </div>
</section>
