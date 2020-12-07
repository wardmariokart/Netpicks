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

  public function selectAccessoireByNightTypeId($nightTypeId)
  {
    $sql = "SELECT `netpicks_accessoires`.* FROM `nights` INNER JOIN `netpicks_accessoires` ON `nights`.`accessoire_id` = `netpicks_accessoires`.`id` WHERE `nights`.`id` = :nightTypeId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':nightTypeId', $nightTypeId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function selectSnackByNightTypeId($nightTypeId)
  {
    $sql = "SELECT `netpicks_snacks`.* FROM `nights` INNER JOIN `netpicks_snacks` ON `nights`.`snack_id` = `netpicks_snacks`.`id` WHERE `nights`.`id` = :nightTypeId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':nightTypeId', $nightTypeId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
