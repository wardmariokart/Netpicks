<?php
require_once (__DIR__ . '/DAO.php');

class ImdbKeywordsDAO extends DAO {
  function __construct()
  {
    parent::__construct('imdb_keywords');
  }

  public function selectByKeyword($keyword, $bMatchWord = true)
  {
    $sql = "SELECT * FROM `imdb_keywords` WHERE `keyword` LIKE :keyword";
    $stmt = $this->pdo->prepare($sql);

    $bindKeyword = '';
    if (!$bMatchWord)
    {
      $bindKeyword = '%' . $keyword . '%';
    }
    else
    {
      $bindKeyword = $keyword;
    }
    $stmt->bindValue(':keyword', $bindKeyword);

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
