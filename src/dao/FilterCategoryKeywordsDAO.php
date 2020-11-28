<?php
require_once (__DIR__ . '/DAO.php');

class FilterCategoryKeywordsDAO extends DAO {
  function __construct()
  {
    parent::__construct('filter_category_keywords');
  }

  public function insert($data)
  {
    $errors = $this->getErrors($data);
    if(empty($errors))
    {
      $sql = "INSERT INTO `filter_category_keywords` (`category_id`, `keyword_id`) VALUES (:categoryId, :keywordId)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':categoryId', $data['categoryId']);
      $stmt->bindValue(':keywordId', $data['keywordId']);
      if ($stmt->execute())
      {
        return $this->selectById($this->pdo->lastInsertId());
      }
    }
    return false;
  }

  public function selectByKeywordIdAndCategoryId($data)
  {
    $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE `keyword_id` = :keywordId AND `category_id` = :categoryId';
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':keywordId', $data['keywordId']);
    $stmt->bindValue(':categoryId', $data['categoryId']);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function selectKeywordIdsbyCategoryId($filterCategoryId)
  {
    $sql = "SELECT DISTINCT `keyword_id` FROM `filter_category_keywords` WHERE `category_id` = :categoryId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':categoryId', $filterCategoryId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  }

  public function getErrors($insertData)
  {
    $errors = array();
    if (empty($insertData['categoryId']))
    {
      $errors['categoryId'] = '\'' . $insertData['categoryId'] . '\' Is an invalid categoryId';
    }
    if (empty($insertData['keywordId']))
    {
      $errors['keywordId'] = '\'' . $insertData['keywordId'] . '\' Is an invalid keywordId';
    }
    return $errors;
  }

}
