<?php

require_once( __DIR__ . '/DAO.php');

class StepOneMovieOptionsDAO extends DAO {

  function __construct()
  {
    parent::__construct('movie_options');
  }

  public function selectByValue($value)
  {
    $sql = "SELECT * FROM `movie_options` WHERE `value` = :value";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':value', $value);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

}
