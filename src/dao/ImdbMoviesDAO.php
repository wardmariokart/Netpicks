<?php
require_once (__DIR__ . '/DAO.php');

class ImdbMoviesDAO extends DAO {
  function __construct()
  {
    parent::__construct('imdb_movies');
  }

  public function selectByGenres($genreNames, $bOnlyMovieIds = false, $limit = 1000)
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

    if (!is_array($genreNames))
    {
      $genreNames = array($genreNames);
    }

    $genreQueryString = implode(',', array_fill(0, count($genreNames), '?'));
    $formattedGenres = array();
    foreach($genreNames as $genreName)
    {
      array_push($formattedGenres, '`imdb_genres`.`genre` = \'' . $genreName . '\'');
    }
    $genreQueryString = implode(' AND ', $formattedGenres);

    $sql = "SELECT " . $selectColumns .  " FROM `" . $this->tableName . "` INNER JOIN `imdb_movies_genres` ON `imdb_movies_genres`.`movie_id` = `" . $this->tableName . "`.`id` WHERE `imdb_movies_genres`.`genre_id` = (SELECT `imdb_genres`.`id` FROM `imdb_genres` WHERE " . $genreQueryString. ") LIMIT :limit";
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
