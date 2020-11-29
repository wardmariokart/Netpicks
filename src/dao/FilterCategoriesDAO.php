<?php
require_once (__DIR__ . '/DAO.php');

class FilterCategoriesDAO extends DAO {
  function __construct()
  {
    parent::__construct('filter_categories');
  }

  public function selectIdByName($categoryName)
  {
    $sql = "SELECT `id` from `filter_categories` WHERE `category_name` = :categoryName";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue($categoryName);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN, 0);
  }
}
