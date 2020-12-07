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

  public function selectAccessoireByOptionId($optionId)
  {
    $sql = "SELECT `netpicks_accessoires`.* FROM `movie_options` INNER JOIN `netpicks_accessoires` ON `movie_options`.`accessoire_id` = `netpicks_accessoires`.`id` WHERE `movie_options`.`id` = :optionId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':optionId', $optionId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function selectSnackByOptionId($optionId)
  {
    $sql = "SELECT `netpicks_snacks`.* FROM `movie_options` INNER JOIN `netpicks_snacks` ON `movie_options`.`snack_id` = `netpicks_snacks`.`id` WHERE `movie_options`.`id` = :optionId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':optionId', $optionId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
