<?php
require_once (__DIR__ . '/DAO.php');

class ImdbMoviesKeywordsDAO extends DAO {
  function __construct()
  {
    parent::__construct('imdb_movies_keywords');
  }

  public function selectKeywordIdsByMovieId($movieId)
  {
    $sql = "SELECT `keyword_id` FROM `imdb_movies_keywords` WHERE `movie_id` = :movieId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':movieId', $movieId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  }

  public function selectMovieIdsWithKeywordIds($movieIds, $keywordIds)
  {
    /* Need to format the sql query insted of binding values because you cannot bind an array to a single placeholder */
    /*  ... IN (:movieIds)... ; bindValue(':movieId', $movieIds); IS NOT VALID */
    $movieIdQuery = implode(',', array_fill(0, count($movieIds), '?'));
    $keywordIdQuery = implode(',', array_fill(0, count($keywordIds), '?'));

    $sql = "SELECT `imdb_movies_keywords`.`movie_id`, count(*) as 'nbMatchingKeywords' FROM `imdb_movies_keywords` WHERE `movie_id` IN ('10824','10779','10987','25750') AND `keyword_id` IN ('12339','162846') GROUP BY `movie_id`";
    $sql = "SELECT `imdb_movies_keywords`.`movie_id`, count(*) as 'nbMatchingKeywords' FROM `imdb_movies_keywords` WHERE `movie_id` IN (" . $movieIdQuery . ") AND `keyword_id` IN (" . $keywordIdQuery . ") GROUP BY `movie_id`";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(array_merge($movieIds, $keywordIds));  // Replaces all '?' with a value by order
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
