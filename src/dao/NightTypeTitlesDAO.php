<?php
require_once (__DIR__ . '/DAO.php');

class NightTypeTitlesDAO extends DAO {
  function __construct()
  {
    parent::__construct('night_type_titles');
  }

  public function selectTitleByNightType($nightTypeId)
  {
    $sql = "SELECT `title_text` FROM " . $this->tableName . " WHERE `night_type_id` = :nightTypeId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':nightTypeId', $nightTypeId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN, 0);
  }
}
