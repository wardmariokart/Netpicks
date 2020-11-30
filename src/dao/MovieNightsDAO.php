<?php
require_once (__DIR__ . '/DAO.php');

class MovieNightsDAO extends DAO {
  function __construct()
  {
    parent::__construct('movie_nights');
  }

  public function insert($data)
  {
    $sql = "INSERT INTO `movie_nights` (`user_id`,`movie_id`,`name`) VALUES(:userId, :movieId, :name)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':userId', $data['userId']);
    $stmt->bindValue(':movieId', $data['movieId']);
    $stmt->bindValue(':name', $data['name']);
    if ($stmt->execute())
    {
      return $this->selectById($this->pdo->lastInsertId());
    }
    return false;
  }

  public function selectByUserId($userId)
  {
    $sql = "SELECT * FROM `movie_nights` WHERE `user_id` = :userId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
