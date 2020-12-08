<?php
require_once (__DIR__ . '/DAO.php');

class ImdbActorsDAO extends DAO {
  function __construct()
  {
    parent::__construct('imdb_characters');
  }

  public function selectActorsByMovieId($movieId)
  {
    $sql = "SELECT `imdb_actors`.* from `imdb_characters` INNER JOIN `imdb_actors` ON `imdb_characters`.`actor_id` = `imdb_actors`.`id` WHERE `imdb_characters`.`movie_id` = :movieId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':movieId', $movieId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
