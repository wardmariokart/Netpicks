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
    $toCheck = ['movieNightId', 'questionId', 'answer'];
    return $this->checkIfAllDataPresent($insertData, $toCheck);
  }

  public function selectAllByMovieNight($movieNightId)
  {
    /* SELECT movie_night_answers.id as answer_id, netpicks_questions.id as question_id, filter_categories.category_name as filter, netpicks_questions.display_question, movie_night_answers.answer from movie_night_answers
inner join movie_nights on movie_nights.id = movie_night_answers.movie_night_id
inner join netpicks_questions on netpicks_questions.id = movie_night_answers.question_id
inner join filter_categories on netpicks_questions.filter_category_id = filter_categories.id
where movie_night_answers.movie_night_id = 32 */

    $sql = 'SELECT movie_night_answers.id as answer_id, netpicks_questions.id as question_id, filter_categories.category_name as filter, netpicks_questions.display_question, movie_night_answers.answer ';
    $sql .= 'from movie_night_answers ';
    $sql .= 'inner join movie_nights on movie_nights.id = movie_night_answers.movie_night_id ';
    $sql .= 'inner join netpicks_questions on netpicks_questions.id = movie_night_answers.question_id ';
    $sql .= 'inner join filter_categories on netpicks_questions.filter_category_id = filter_categories.id ';
    $sql .= 'where movie_night_answers.movie_night_id = :movieNightId';

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':movieNightId', $movieNightId);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function updateAnswer($answerId, $newAnswer)
  {
    $errors = $this->validateUpdateAnswer($newAnswer);
    if(empty($errors))
    {
      $sql = "UPDATE " . $this->tableName . " SET answer = :newAnswer WHERE id = :answerId";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':newAnswer', $newAnswer);
      $stmt->bindValue(':answerId', $answerId);
      if ($stmt->execute())
      {
        return $this->selectByid($answerId);
      }
    }
    return false;
  }

  public function validateUpdateAnswer($newAnswer)
  {
    $errors = array();
    if(empty($newAnswer))
    {
      $errors['newAnswer'] = 'invalid new answer';
    }
  }

}
