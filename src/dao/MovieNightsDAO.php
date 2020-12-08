<?php
require_once (__DIR__ . '/DAO.php');
require_once (__DIR__ . '/MovieNightAnswersDAO.php');

class MovieNightsDAO extends DAO {
  function __construct()
  {
    parent::__construct('movie_nights');
  }

  /*
  * $data structure:
   * data['movieNightId']
   * data['newMovieId'] */
  public function updateMovieId($data)
  {
    $errors = $this->validateUpdateData($data);
    if (empty($errors))
    {
      $sql = "UPDATE " . $this->tableName . " SET movie_id = :newMovieId WHERE id = :movieNightId";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':newMovieId', $data['newMovieId']);
      $stmt->bindValue(':movieNightId', $data['movieNightId']);
      if ($stmt->execute())
      {
        return $this->selectById($data['movieNightId']);
      }
    }
    return false;
  }

  public function insert($data)
  {
    $errors = $this->validateInsertData($data);

    if (empty($errors))
    {
      $sql = "INSERT INTO `movie_nights` (`user_id`,`movie_id`,`title`, `movie_option_one_id`, `movie_option_two_id`, `night_type_id`) VALUES(:userId, :movieId, :title, :movieOptionOneId, :movieOptionTwoId, :nightTypeId)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':userId', $data['userId']);
      $stmt->bindValue(':movieId', $data['movieId']);
      $stmt->bindValue(':title', $data['title']);
      $stmt->bindValue(':movieOptionOneId', $data['movieOptionOneId']);
      $stmt->bindValue(':movieOptionTwoId', $data['movieOptionTwoId']);
      $stmt->bindValue(':nightTypeId', $data['nightTypeId']);
      if ($stmt->execute())
      {
        return $this->selectById($this->pdo->lastInsertId());
      }
    }
    return false;
  }

  public function selectByUserId($userId)
  {
    $sql = "SELECT movie_nights.id, movie_nights.title, movie_nights.user_id as userId, imdb_movies.poster FROM movie_nights LEFT JOIN imdb_movies ON movie_nights.movie_id=imdb_movies.id WHERE user_id = :userId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function validateInsertData($insertData)
  {
    $toCheck = ['userId', 'movieId', 'title', 'movieOptionOneId', 'movieOptionTwoId', 'nightTypeId'];
    return $this->checkIfAllDataPresent($insertData, $toCheck);
  }

  public function validateUpdateData($updateData)
  {
    $toCheck = ['movieNightId', 'newMovieId'];
    return $this->checkIfAllDataPresent($updateData, $toCheck);
  }

  public function deleteById($id)
  {
    parent::deleteById($id);
    $movieNightAnswersDAO = new MovieNightAnswersDAO();
    $movieNightAnswersDAO->deleteAllWithMovieNightId($id);
  }
}
