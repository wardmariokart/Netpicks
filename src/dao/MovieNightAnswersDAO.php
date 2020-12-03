<?php
require_once (__DIR__ . '/DAO.php');

class MovieNightAnswersDAO extends DAO {
  function __construct()
  {
    parent::__construct('movie_night_answers');
  }

  public function insert($data)
  {
    $errors = $this->validateInsertData($data);

    if (empty($errors))
    {

      $sql = "INSERT INTO `movie_night_answers` (`movie_night_id`, `question_id`,`answer`) VALUES(:movieNightId, :questionId, :answer)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':movieNightId', $data['movieNightId']);
      $stmt->bindValue(':questionId', $data['questionId']);
      $stmt->bindValue(':answer', $data['answer']);
      if ($stmt->execute())
      {
        return $this->selectById($this->pdo->lastInsertId());
      }
    }
    return false;
  }

  public function deleteAllWithMovieNightId($movieNightId)
  {
    $sql = "DELETE FROM " . $this->tableName . " WHERE `movie_night_id` = :movieNightId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':movieNightId', $movieNightId);
    $stmt->execute();
  }

  public function validateInsertData($insertData)
  {
    $errors = array();
    $toCheck = ['movieNightId', 'questionId', 'answer'];
    foreach($toCheck as $key)
    {
      $this->checkhasKey($insertData, $key, $errors);
    }
    return $errors;
  }


}
