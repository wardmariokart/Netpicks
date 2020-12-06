<?php
require_once (__DIR__ . '/DAO.php');

class NetpicksQuestionsDAO extends DAO {
  function __construct()
  {
    parent::__construct('netpicks_questions');
  }

  public function selectAllByMovieOption($movieOptionId)
  {
    $sql = "SELECT * from `netpicks_questions` WHERE `movie_option_id` = :movieOptionId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':movieOptionId', $movieOptionId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
