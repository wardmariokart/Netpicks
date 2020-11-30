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

  public function filterMovieIdsWithKeywordIds($movieIds, $keywordIds)
  {
    return $this->selectMovieIdsByKeywordIds($movieIds, $keywordIds, false);
  }

  public function rejectMovieIdsWithKeywordIds($movieIds, $keywordIds)
  {
    $movieIdQuery = implode(',', array_fill(0, count($movieIds), '?'));
    $keywordIdQuery = implode(',', array_fill(0, count($keywordIds), '?'));
    $filteredSql = "SELECT DISTINCT `imdb_movies_keywords`.`movie_id` as 'nbMatchingKeywords' FROM `imdb_movies_keywords` WHERE `movie_id` IN (" . $movieIdQuery . ") AND `keyword_id` IN (" . $keywordIdQuery . ")";
    $sql = "SELECT DISTINCT `imdb_movies_keywords`.`movie_id` FROM `imdb_movies_keywords` WHERE `imdb_movies_keywords`.`movie_id` NOT IN (" . $filteredSql . ") AND `imdb_movies_keywords`.`movie_id` IN (". $movieIdQuery . ")";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(array_merge($movieIds, $keywordIds, $movieIds));  // Replaces all '?' with a value by order
    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  }

  private function selectMovieIdsByKeywordIds($movieIds, $keywordIds, $bRejectKeywords)
  {
    /* Need to format the sql query insted of binding values because you cannot bind an array to a single placeholder */
    /*  ... IN (:movieIds)... ; bindValue(':movieId', $movieIds); IS NOT VALID */
    $movieIdQuery = implode(',', array_fill(0, count($movieIds), '?'));
    $keywordIdQuery = implode(',', array_fill(0, count($keywordIds), '?'));

    // $bRejectKeywords = true  ---> select movieIds WITHOUT keywords
    // $bRejectKeywords = false ---> select movieIds WITH keywords
    $rejectQuery = $bRejectKeywords ? 'NOT ' : '';

    $sql = "SELECT `imdb_movies_keywords`.`movie_id`, count(*) as 'nbMatchingKeywords' FROM `imdb_movies_keywords` WHERE `movie_id` IN (" . $movieIdQuery . ") AND `keyword_id` " . $rejectQuery . "IN (" . $keywordIdQuery . ") GROUP BY `movie_id`";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(array_merge($movieIds, $keywordIds));  // Replaces all '?' with a value by order
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
