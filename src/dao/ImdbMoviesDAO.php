<?php
require_once (__DIR__ . '/DAO.php');

class ImdbMoviesDAO extends DAO {
  function __construct()
  {
    parent::__construct('imdb_movies');
  }

  public function selectByGenreIds($genreIds, $bOnlyMovieIds = false, $limit = 50000)
  {


    $selectColumns = '';
    if ($bOnlyMovieIds)
    {
      $selectColumns = 'DISTINCT `' . $this->tableName . '`.`id`';
    }
    else
    {
      $selectColumns = '`' . $this->tableName . '`.*';
    }

    if (!is_array($genreIds))
    {
      $genreIds = array($genreIds);
    }

    //$genreQueryString = implode(',', array_fill(0, count($genreIds), '?'));
    $formattedGenres = array();
    foreach($genreIds as $genreId)
    {
      array_push($formattedGenres, '`imdb_movies_genres`.`genre_id` = \'' . $genreId . '\'');
    }
    $genreQueryString = implode(' OR ', $formattedGenres);

    $sql = "SELECT " . $selectColumns .  " FROM `" . $this->tableName . "` INNER JOIN `imdb_movies_genres` ON `imdb_movies_genres`.`movie_id` = `" . $this->tableName . "`.`id` WHERE " . $genreQueryString . " LIMIT :limit";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit);
    $stmt->execute();

    $result = null;
    if ($bOnlyMovieIds)
    {
      $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    else
    {
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $result;
  }
}
