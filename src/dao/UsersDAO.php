<?php
require_once __DIR__ . '/DAO.php';

class UsersDAO extends DAO {

  function __construct()
  {
    parent::__construct('netpicks_users');
  }

  public function insert($data) {
    $errors = $this->getValidationErrors($data);
    if (empty($errors)) {
      $sql = "INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':email', $data['email']);
      $stmt->bindValue(':password', $data['password']);
      if($stmt->execute()) {
        $insertedId = $this->pdo->lastInsertId();
        return $this->selectById($insertedId);
      }
    }
    return false;
  }

  public function selectByEmail($email) {
    $sql = "SELECT * FROM `users` WHERE `email` = :email";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getValidationErrors($data) {
    $errors = array();
    if (empty($data['email'])) {
      $errors['email'] = 'please enter the email';
    }
    if (empty($data['password'])) {
      $errors['password'] = 'please enter the password';
    }
    return $errors;
  }
}
