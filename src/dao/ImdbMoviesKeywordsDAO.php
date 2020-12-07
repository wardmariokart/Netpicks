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

  public function filterMovieIdsByKeywordCategoryId($startMovieIds, $filterCategoryId)
  {

    $movieIdQuery = implode(',', array_fill(0, count($startMovieIds), '?'));
    $subQuery = "SELECT `imdb_movies_keywords`.`movie_id` FROM `imdb_movies_keywords` INNER JOIN `filter_category_keywords` ON `imdb_movies_keywords`.`keyword_id` = `filter_category_keywords`.`keyword_id` WHERE `filter_category_keywords`.`category_id` = ?";
    $sql = "SELECT `subTable`.* FROM (" . $subQuery . ") AS subTable WHERE `subTable`.`movie_id` IN (" . $movieIdQuery . ")";
    $stmt = $this->pdo->prepare($sql);
    $executeArray =array_merge([$filterCategoryId], $startMovieIds);
    $stmt->execute($executeArray);
    return $stmt->fetchAll(PDO::FETCH_COLUMN,0); // movies with multipe matching keywords will appear multiple times. This is intended to give these movies a higher chance of getting picked.
  }

  public function rejectMovieIdsByFilterCategoryId($startMovieIds, $filterCategoryId)
  {
    if(count($startMovieIds) > 0)
    {
      $startTabelQuery = '(SELECT ' . $startMovieIds[0] . ' AS movie_id ' . (count($startMovieIds) > 1 ? 'UNION SELECT ' : '');
      $startMovieIdsCopy = $startMovieIds;
      array_shift($startMovieIdsCopy);
      $startTabelQuery .= implode(' UNION SELECT ', $startMovieIdsCopy);
      $startTabelQuery .= ')';

      $movieIdQuery = implode(',', array_fill(0, count($startMovieIds), '?'));
      $subQuery = "SELECT `imdb_movies_keywords`.`movie_id` FROM `imdb_movies_keywords` INNER JOIN `filter_category_keywords` ON `imdb_movies_keywords`.`keyword_id` = `filter_category_keywords`.`keyword_id` WHERE `filter_category_keywords`.`category_id` = ?";
      $moviesInCategoryQuery = "SELECT `subTable`.* FROM (" . $subQuery . ") AS subTable WHERE `subTable`.`movie_id` IN (" . $movieIdQuery . ")";

      $sql = "SELECT * FROM " . $startTabelQuery . " AS subTable WHERE `subTable`.`movie_id` NOT IN (" . $moviesInCategoryQuery . ")";
      $stmt = $this->pdo->prepare($sql);
      $executeArray =array_merge([$filterCategoryId], $startMovieIds);
      $stmt->execute($executeArray);

      return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    return $startMovieIds;
  }
}
