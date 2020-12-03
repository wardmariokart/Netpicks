<?php

require_once( __DIR__ . '/DAO.php');

class NightTypesDAO extends DAO {

  function __construct()
  {
    parent::__construct('nights');
  }

  public function selectByValue ($value)
  {
    $sql = "SELECT * FROM `nights` WHERE `value` = :value";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':value', $value);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

}
