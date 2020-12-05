<?php
require_once (__DIR__ . '/DAO.php');
require_once (__DIR__ . '/MovieNightAnswersDAO.php');

class MovieNightsDAO extends DAO {
  function __construct()
  {
    parent::__construct('movie_nights');
  }

  public function insert($data)
  {
    $errors = $this->validateInsertData($data);

    if (empty($errors))
    {

      $sql = "INSERT INTO `movie_nights` (`user_id`,`movie_id`,`name`, `movie_option_one_id`, `movie_option_two_id`, `night_type_id`) VALUES(:userId, :movieId, :name, :movieOptionOneId, :movieOptionTwoId, :nightTypeId)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':userId', $data['userId']);
      $stmt->bindValue(':movieId', $data['movieId']);
      $stmt->bindValue(':name', $data['name']);
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
    $sql = "SELECT movie_nights.name as name, movie_nights.user_id as id, imdb_movies.poster as poster FROM movie_nights LEFT JOIN imdb_movies ON movie_nights.movie_id=imdb_movies.id WHERE user_id = :userId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // public function selectByUserId($userId)
  // {
  //   $sql = "SELECT * FROM `movie_nights` WHERE `user_id` = :userId";
  //   $stmt = $this->pdo->prepare($sql);
  //   $stmt->bindValue(':userId', $userId);
  //   $stmt->execute();
  //   return $stmt->fetchAll(PDO::FETCH_ASSOC);
  // }

  public function validateInsertData($insertData)
  {
    $errors = array();
    $toCheck = ['userId', 'movieId', 'name', 'movieOptionOneId', 'movieOptionTwoId', 'nightTypeId'];
    foreach($toCheck as $key)
    {
      $this->checkhasKey($insertData, $key, $errors);
    }
    return $errors;
  }

  public function deleteById($id)
  {
    parent::deleteById($id);
    $movieNightAnswersDAO = new MovieNightAnswersDAO();
    $movieNightAnswersDAO->deleteAllWithMovieNightId($id);
  }
}
