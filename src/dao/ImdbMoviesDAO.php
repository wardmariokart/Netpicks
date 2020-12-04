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

  // negative value for no limit
  public function selectAllIdsTitlesPosters($from, $to)
  {


    $limitQuery = "";
    if($from >= 0)
    {
      $limitQuery .= " LIMIT :limitFrom";
      if ($to > 0)
      {
        $limitQuery .= ",:limitAmount";
      }
    }
    $sql = "SELECT `id`, `title`, `poster` FROM " . $this->tableName . $limitQuery;
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':limitFrom', $from);
    $stmt->bindValue(':limitAmount', $to - $from);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function updatePosterById($movieId, $updatedPoster)
  {
    $sql = "UPDATE " . $this->tableName . " SET `poster`=:poster WHERE `id`=:movieId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':poster', $updatedPoster);
    $stmt->bindValue(':movieId', $movieId);
    if ($stmt->execute())
    {
      return $this->selectById($movieId);
    }
    return false;
  }

  public function updateTitleByid($movieId, $updatedTitle)
  {
    $sql = "UPDATE " . $this->tableName . " SET `title`=:title WHERE `id`=:movieId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':title', $updatedTitle);
    $stmt->bindValue(':movieId', $movieId);
    if ($stmt->execute())
    {
      return $this->selectById($movieId);
    }
    return false;
  }
}
