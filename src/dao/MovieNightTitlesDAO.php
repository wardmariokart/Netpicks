<?php
require_once (__DIR__ . '/DAO.php');

class MovieNightTitlesDAO extends DAO {
  function __construct()
  {
    parent::__construct('movie_night_titles');
  }

  public function selectTitleByMovieOption($movieOptionsId)
  {
    $sql = "SELECT `title_text` FROM " . $this->tableName . " WHERE `movie_options_id` = :movieOptionsId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':movieOptionsId', $movieOptionsId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN, 0);
  }
}
