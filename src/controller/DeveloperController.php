<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/FilterCategoriesDAO.php';
require_once __DIR__ . '/../dao/ImdbKeywordsDAO.php';
require_once __DIR__ . '/../dao/FilterCategoryKeywordsDAO.php';
require_once __DIR__ . '/../dao/ImdbMoviesDAO.php';



class DeveloperController extends Controller {

  private $filterCategoriesDAO;
  private $imdbKeywordsDAO;
  private $filterCategoryKeywordsDAO;
  private $apiKey = 'da5442d98535330c1b4e09193cbcd4a9';

  function __construct() {
    $this->filterCategoriesDAO = new FilterCategoriesDAO();
    $this->imdbKeywordsDAO = new ImdbKeywordsDAO();
    $this->filterCategoryKeywordsDAO = new FilterCategoryKeywordsDAO();
  }

  public function devTools() {
    $this->set('title', 'Developer Tools');

    if(isset($_SESSION['updatedMoviePaths']))
    {
      $this->set('updatedMoviePaths', $_SESSION['updatedMoviePaths']);
      unset($_SESSION['updatedMoviePaths']);
    }

    // 1. Get filter categories
    $this->set('filterCategories', $this->filterCategoriesDAO->selectAll());

    if (!empty($_POST['action']))
    {
      if ($_POST['action'] == 'add-filter-keywords' && isset($_POST['filter-keywords']))
      {
        $category = $_POST['filter-category'];
        $keywords = explode(',', $_POST['filter-keywords']);

        $keywordsInserts = array();
        $successfulInserts = 0;
        foreach($keywords as $keyword)
        {
          $imdbKeyword = $this->imdbKeywordsDAO->selectByKeyword($keyword);
          if (isset($imdbKeyword['id']))
          {
            $categoryId = $_POST['filter-category'];
            $checkDuplicateData = array('categoryId' => $categoryId, 'keywordId' => $imdbKeyword['id']);
            $bDuplicate = $this->filterCategoryKeywordsDAO->selectByKeywordIdAndCategoryId($checkDuplicateData);

            if (!$bDuplicate)
            {

              $insertData = array('categoryId' => $categoryId, 'keywordId' => $imdbKeyword['id']);
              $insertResult = $this->filterCategoryKeywordsDAO->insert($insertData);
              if ($insertResult == false)
              {
                $keywordInserts[$keyword] = 'insertFailed';
              }
              else
              {
                $keywordInserts[$keyword] = 'insertOk';
                $successfulInserts += 1;
              }
            }
            else
            {
              $keywordInserts[$keyword] = 'duplicate';
            }
          }
          else
          {
            $keywordInserts[$keyword] = 'keyword not in imdb_keywords table';
          }
        }

        $info = strval($successfulInserts) . ' Keywords have been added. ';
        foreach($keywordInserts as $keyword => $bInserted)
        {
          if ($bInserted == 'insertOk')
          {
            $info .= '\'' .  $keyword . '\' ';
          }
        }

        $info .= '. Keywords not added because not found in imdb_keywords or duplicate: ';
        foreach($keywordInserts as $keyword => $bInserted)
        {
          if ($bInserted == 'keyword not in imdb_keywords table' || $bInserted == 'duplicate')
          {
            $info .= '\'' .  $keyword . '\' ';
          }
        }
        // Do something with $keywordInserts
        $_SESSION['info'] = $info;
        header('location:index.php?page=devTools');
        exit();
      }

      if ($_POST['action'] === 'updateMoviePosters')
      {
        $this->updateMoviePaths(intval($_POST['updateFrom']), intval($_POST['updateTo']));
      }
    }
  }

  private function updateMoviePaths($from, $to, $bAbortOnTitleDifference = false)
  {
    // 1. Get all movieIds
    $imdbMoviesDAO = new ImdbMoviesDAO();
    $outdatedMovieInfos = $imdbMoviesDAO->selectAllIdsTitlesPosters($from, $to);
    //$this->set('updateTemp', $outdatedMovieInfos);
    $requiredUpdates = array();
    foreach($outdatedMovieInfos as $outdatedMovieInfo)
    {
      // check if url is outdated
      $apiUrl = 'https://api.themoviedb.org/3/movie/' . $outdatedMovieInfo['id'] . '?api_key=' . $this->apiKey . '&language=en-US';
      $apiResponse = $this->callAPI('GET', $apiUrl, false);
      $apiMovieInfo = json_decode($apiResponse);

      if($bAbortOnTitleDifference && $apiMovieInfo->title !== $outdatedMovieInfo['title'])
      {
        if(strtolower($apiMovieInfo->title) !== strtolower($outdatedMovieInfo['title']))
        {
          // should never happen
          echo 'The id "' . $outdatedMovieInfo['id'] . '" did not correspond to the same movie title! Combell title: "' . $outdatedMovieInfo['title'] . '". TMDB title: ' . $apiMovieInfo->title . '". Nothing has been updated.';
          die;
        }
      }

      if (property_exists($apiMovieInfo, "poster_path") && $apiMovieInfo->poster_path !== $outdatedMovieInfo['poster'])
      {
        array_push($requiredUpdates, array('movieId' => $apiMovieInfo->id, 'title' => $apiMovieInfo->title, 'outdatedPoster' => $outdatedMovieInfo['poster'], 'updatedPoster' => $apiMovieInfo->poster_path), );
      }
    }

    foreach($requiredUpdates as $requiredUpdate)
    {
      $result = $imdbMoviesDAO->updatePosterById($requiredUpdate['movieId'], $requiredUpdate['updatedPoster']);
      if ($result === false)
      {
        echo 'failed to update a movie';
        die;
      }
    }

    $_SESSION['updatedMoviePaths'] = $requiredUpdates;
    $_SESSION['info'] = count($requiredUpdates) . ' Out of ' . count($outdatedMovieInfos) . ' movies had an outdated poster url and have been updated using TMDB API';
    header('location:index.php?page=devTools');
    exit();

  }

  // Did api calls using fetch in js
  // In php cUrl is used. I used code found online to handle this. (source: https://weichie.com/blog/curl-api-calls-with-php/)
  private function callAPI($method, $url, $data){
    $curl = curl_init();
    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       default:
          if ($data)
             $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'APIKEY: 111111111111111111111',
       'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
 }

}
