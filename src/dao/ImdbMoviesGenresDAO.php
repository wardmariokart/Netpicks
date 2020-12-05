<?php
require_once (__DIR__ . '/DAO.php');

class ImdbMoviesGenresDAO extends DAO {
  function __construct()
  {
    parent::__construct('imdb_movies_genres');
  }

  public function selectMovieIdsWithGenresId($genreIds)
  {
    //select subTable.movie_id from (SELECT movie_id, count(*) as 'count' FROM `imdb_movies_genres` where genre_id in (16,35) group by movie_id) as subTable where count = 2
    $genreIdQuery = implode(',', array_fill(0, count($genreIds), '?'));

    $sql = "SELECT `subTable`.`movie_id` FROM (SELECT `movie_id`, COUNT(*) AS 'nbMatchingGenres' FROM `imdb_movies_genres` WHERE `genre_id` IN (" . $genreIdQuery . ") GROUP BY `movie_id`) AS subTable WHERE nbMatchingGenres = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(array_merge($genreIds, array(count($genreIds))));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
