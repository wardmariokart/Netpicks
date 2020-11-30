<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/NightTypesDAO.php';
require_once __DIR__ . '/../dao/StepOneMovieOptionsDAO.php';
require_once __DIR__ . '/../dao/MovieNightsDAO.php';
require_once __DIR__ . '/../dao/ImdbMoviesDAO.php';
require_once __DIR__ . '/../dao/ImdbMoviesKeywordsDAO.php';
require_once __DIR__ . '/../dao/FilterCategoryKeywordsDAO.php';
require_once __DIR__ . '/../dao/FilterCategoriesDAO.php';


class HomeController extends Controller {

  private $nightTypesDAO;
  private $stepOneMovieOptionsDAO;
  private $movieNightsDAO;
  private $imdbMoviesDAO;
  private $filterCategoriesDAO;

  function __construct() {
    $this->nightTypesDAO = new NightTypesDAO();
    $this->stepOneMovieOptionsDAO = new StepOneMovieOptionsDAO();
    $this->movieNightsDAO = new MovieNightsDAO();
    $this->imdbMoviesDAO = new ImdbMoviesDAO();
    $this->filterCategoriesDAO = new FilterCategoriesDAO();
  }

  public function home() {
    $this->set('title', 'Home - Movie Night Planner 🍿');

    // required data: DateNights, KindsOfMovies
    $this->set('nightTypes', $this->nightTypesDAO->selectAll());
    $this->set('stepOneOptions', $this->stepOneMovieOptionsDAO->selectAll());

    if (!empty($_GET['action']))
    {
      if ($_GET['action'] == 'planMovieNight')
      {
        $nightType = $_GET['nightType'];
        $movieOptionOne = $_GET['movieOptionOne'];
        $movieOptionTwo = $_GET['movieOptionTwo'];
        header('location:index.php?page=extraQuestions&nightType=' . $nightType . '&movieOptionOne=' . $movieOptionOne . '&movieOptionTwo=' . $movieOptionTwo);
        exit();
      }
    }

    if (!empty($_SESSION['user']))
    {
      $this->set('myMovieNights', $this->movieNightsDAO->selectByUserId($_SESSION['user']['id']));
    }
  }

  public function extraQuestions() {
    $this->set('title', 'Setup - Step Two');

    if (empty($_GET['nightType']) || empty($_GET['movieOptionOne']) || empty($_GET['movieOptionTwo']))
    {
      $_SESSION['error'] = 'Invalid options selected';
      header('location:index.php');
      exit();
    }

    $stepOne = array();
    $stepOne['nightType'] = $this->nightTypesDAO->selectByValue($_GET['nightType']);
    $stepOne['movieOptionOne'] = $this->stepOneMovieOptionsDAO->selectByValue($_GET['movieOptionOne']);
    $stepOne['movieOptionTwo'] = $this->stepOneMovieOptionsDAO->selectByValue($_GET['movieOptionTwo']);

    if(empty($stepOne['nightType']) || empty($stepOne['movieOptionOne']) || empty($stepOne['movieOptionTwo']))
    {
      $_SESSION['error'] = 'Invalid options selected';
      header('location:index.php');
      exit();
    }
    $this->set('stepOne', $stepOne);


    if (!isset($_SESSION['filteredMovieIds']))
    {
      // Get movies by genre
      $_SESSION['filteredMovieIds'] = $this->imdbMoviesDAO->selectByGenres(['horror'], true, 5000);
    }






    //$horrorMovieIds = $this->imdbMoviesDAO->selectByGenres(['horror'], true, 5000); // DOESNT WORK TODO !!!!
    //$filteredMovies = $this->filterMoviesByCategoryKeywords($horrorMovieIds, 'supernatural', 'filter');
    //$filteredMovieIds = array_column($filteredMovies, 'movie_id');
    //$filteredMovies = $this->filterMoviesByCategoryKeywords($filteredMovieIds, 'gore', 'filter');
    //$filteredMovieIds = array_column($filteredMovies, 'movie_id');


    // Javascript action
    if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json')
    {
      $content = trim(file_get_contents('php://input'));
      $data = json_decode($content, true);

      if ($data['action'] === 'filter')
      {
        $this->handleFilterActionJs($data);
        $result = $this->pickFromFilteredMovies();
        echo json_encode($result);
        exit();
      }
      else if ($data['action'] === 'pickOtherMovie')
      {
        $result = $this->pickFromFilteredMovies();
        echo json_encode($result);
        exit();
      }
      else if ($data['action'] === 'confirmPick')
      {
        // insert post into + assign it to user + open detail page!!!
        // doesn't work yet TODO
        $insertData = array();
        $insertData['userId'] = $this->safeKeySelector($_SESSION, 'user', '-1');
        $insertData['movieId'] = $this->safeKeySelector($data, 'pickedId', '-1');
        $insertData['name'] = 'action packed, tear jerking girls night in';
        $insertResult = $this->movieNightsDAO->insert($insertData);
        if (!empty($insertResult))
        {
          $_SESSION['info'] = 'New post has been created!! id: ' . $insertResult['id'];
          header('location:index.php?page=detail&id=' . $insertResult['id']);
          exit();
        }
      }
    }

    // php action
    if (isset($_POST['action']))
    {
      if($_POST['action'] === 'confirmPick')
      {
        $insertData = array();
        $insertData['userId'] = $this->safeKeySelector($_SESSION, 'user', '-1');
        $insertData['movieId'] = $this->safeKeySelector($_POST, 'pickedId', '-1');
        $insertData['name'] = 'action packed, tear jerking girls night in';
        $insertResult = $this->movieNightsDAO->insert($insertData);
        if (!empty($insertResult))
        {
          $this->handleConfirmPick(-1,-1);//TEMP
          $_SESSION['info'] = 'New post has been created!! id: ' . $insertResult['id'];
          header('location:index.php?page=detail&id=' . $insertResult['id']);
          exit();
        }
      }

      if ($_POST['action'] === 'filter')
      {

        if (!empty($_POST['filterType']))
        {
          switch ($_POST['filterType'])
          {
            case 'supernatural':
              if($_POST['filterSupernatural'] == 'true')
              {
                $_SESSION['filteredMovieIds'] = $this->filterMoviesByCategoryKeywords($_SESSION['filteredMovieIds'], $_POST['filterType'], 'filter');
              }
              else if ($_POST['filterSupernatural'] == 'false')
              {
                $result =  $this->filterMoviesByCategoryKeywords($_SESSION['filteredMovieIds'], $_POST['filterType'], 'reject');
                $_SESSION['filteredMovieIds'] = $result;
              }
              else if ($_POST['filterSupernatural'] == 'skip')
              {

              }

            break;
            case 'gorePsychological':



            break;

            default:
            unset($_SESSION['filteredMovieIds']);
            $_SESSION['error'] = $_POST['filterType'] . 'Is invalid filter type';
            header('location: index.php');
            exit();
          break;
          }
        }
      }
    }


    $this->set('nbMoviesFound', count($_SESSION['filteredMovieIds']));

  }

  private function handleConfirmPick($userId, $pickId)  // TODO
  {
    unset($_SESSION['filteredMovieIds']);
  }

  private function handleFilterActionJs($data)
  {
    if ($this->safeKeySelector($data, 'filterType'))
    {
      switch ($data['filterType'])
      {
          case 'supernatural':
            if($data['filterSupernatural'] == 'true')
            {

              $_SESSION['filteredMovieIds'] = array_column($this->filterMoviesByCategoryKeywords($_SESSION['filteredMovieIds'], $data['filterType'], 'filter'), 'movie_id');
            }
            else if ($data['filterSupernatural'] == 'false')
            {
              $result =  $this->filterMoviesByCategoryKeywords($_SESSION['filteredMovieIds'], $data['filterType'], 'reject');
              $_SESSION['filteredMovieIds'] = $result;
            }
            else if ($_POST['filterSupernatural'] == 'skip')
            {
              // do nothing
            }
          break;
          default:
          unset($_SESSION['filteredMovieIds']);
          $_SESSION['error'] = $_POST['filterType'] . 'Is invalid filter type';
          header('location: index.php');
          exit();
        break;
      }
    }
  }

  private function pickFromFilteredMovies ()
  {
    $movieIdPick = $_SESSION['filteredMovieIds'][array_rand($_SESSION['filteredMovieIds'])];
    $pickedMovie = $this->imdbMoviesDAO->selectById($movieIdPick);

    $result = array();
    $result['type'] = 'confirm pick';
    $result['data'] = array('nbMoviesLeft' => count($_SESSION['filteredMovieIds']), 'pickData' => $pickedMovie);

    return $result;
  }

  private function safeKeySelector($array, $key, $alternative = false)
  {
    return isset($array[$key]) ? $array[$key] : $alternative;
  }

  private function filterMoviesByCategoryKeywords($inMovieIds, $inCategoryFilter, $filterOrReject)
  {
    $categoryFilterId = $this->filterCategoriesDAO->selectIdByName($inCategoryFilter);
    $filterKeywordsDAO = new FilterCategoryKeywordsDAO();
    $filterKeywordIds = $filterKeywordsDAO->selectKeywordIdsbyCategoryId($categoryFilterId);


    $outMovies = array();
    $imdbMoviesKeywordsDOA = new ImdbMoviesKeywordsDAO();
    $bSqlMethod = true;
    if ($bSqlMethod)
    {

      if ($filterOrReject == 'filter')
      {
        $outMovies = $imdbMoviesKeywordsDOA->filterMovieIdsWithKeywordIds($inMovieIds, $filterKeywordIds);
      }
      else if ($filterOrReject == 'reject')
      {
        $outMovies = $imdbMoviesKeywordsDOA->rejectMovieIdsWithKeywordIds($inMovieIds, $filterKeywordIds);
      }
      // Implementation 2: Much faster. Takes around a second to process 4600 movies
    }
    else
    {
      // Implementation 1: took 4 minutes to process 4600 movies
      foreach($inMovieIds as $movieId)
      {
        // Get all keywords for a movieId from table imdb_movies_keywords
        $movieKeywordIds = $imdbMoviesKeywordsDOA->selectKeywordIdsbyMovieId($movieId);
        $matchingKeywords = array_intersect($movieKeywordIds, $filterKeywordIds);
        if (!empty($matchingKeywords))
        {
          array_push($outMovies, array('movie_id' => $movieId, 'nbMatchingKeywords' => ($matchingKeywords)));
        }
      }
    }
    return $outMovies;
  }

  public function detail() {
    $movieNightId = $this->safeKeySelector($_GET, 'id', '1');
    $movieNightRow = $this->movieNightsDAO->selectById($movieNightId);
    $this->set('title', $movieNightRow['name']);
    $imdbMovieRow = $this->imdbMoviesDAO->selectById($movieNightRow['movie_id']);
    $details = array('movie' => $imdbMovieRow);
    $this->set('details', $details);
  }

}
