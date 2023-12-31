<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/UsersDAO.php';

class UsersController extends Controller
{

  private $UsersDAO;

  function __construct()
  {
    $this->UsersDAO = new UsersDAO();
  }

  public function redirectIfSignedIn()
  {
    $bSignedIn = isset($_SESSION['user']);
    if ($bSignedIn)
    {
      $_SESSION['info'] = 'already signed in. Redirected to Home';
      header('location: index.php');
      exit();
    }
  }
  public function signIn()
  {
    $this->redirectIfSignedIn();

    $this->set('title', 'Sign In');

    if (!empty($_POST)) {
      $errors = array();
      if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $existing = $this->UsersDAO->selectByUsername($_POST['username']);
        if (!empty($existing)) {
          if (password_verify($_POST['password'], $existing['password'])) {
            $_SESSION['user'] = $existing;
            $_SESSION['info'] = 'Logged In';
            header('location:index.php');
            exit();
          } else {
            $errors['username'] = 'Unknown username / password';
            $_SESSION['error'] = 'Unknown username / password';
          }
        } else {
          $errors['username'] = 'Unknown username / password';
          $_SESSION['error'] = 'Unknown username / password';
        }
      } else {
        $errors['username'] = 'Unknown username / password';
        $_SESSION['error'] = 'Unknown username / password';
      }
      $this->set('errors', $errors);
    }

  }

  public function signOut()
  {
    if (!empty($_SESSION['user'])) {
      unset($_SESSION['user']);
    }
    $_SESSION['info'] = 'Logged Out';
    header('location:index.php');
    exit();
  }

  public function signUp()
  {
    $this->redirectIfSignedIn();
    $this->set('title', 'Sign Up');

    if (!empty($_POST)) {
      $errors = array();
      if (empty($_POST['username'])) {
        $errors['username'] = 'Please enter your username';
      } else {
        $existing = $this->UsersDAO->selectByUsername($_POST['username']);
        if (!empty($existing)) {
          $errors['username'] = 'Username address is already in use';
        }
      }
      if (empty($_POST['password'])) {
        $errors['password'] = 'Please enter a password';
      }
      if ($_POST['confirm_password'] != $_POST['password']) {
        $errors['confirm_password'] = 'Passwords do not match';
      }
      if (empty($errors)) {
        $inserteduser = $this->UsersDAO->insert(array(
          'username' => $_POST['username'],
          'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
        ));
        if (!empty($inserteduser)) {
          $_SESSION['info'] = 'Registration Successful!';
          $_SESSION['user'] = $inserteduser;
          header('location:index.php');
          exit();
        }
      }
      $_SESSION['error'] = 'Registration Failed!';
      $this->set('errors', $errors);
    }
  }
}
