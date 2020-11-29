<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/NightTypesDAO.php';
require_once __DIR__ . '/../dao/StepOneMovieOptionsDAO.php';
require_once __DIR__ . '/../dao/MovieNightsDAO.php';
require_once __DIR__ . '/../dao/ImdbMoviesDAO.php';
require_once __DIR__ . '/../dao/ImdbMoviesKeywordsDAO.php';
require_once __DIR__ . '/../dao/FilterCategoryKeywordsDAO.php';

class HomeController extends Controller {

  private $nightTypesDAO;
  private $stepOneMovieOptionsDAO;
  private $movieNightsDAO;
  private $imdbMoviesDAO;

  function __construct() {
    $this->nightTypesDAO = new NightTypesDAO();
    $this->stepOneMovieOptionsDAO = new StepOneMovieOptionsDAO();
    $this->movieNightsDAO = new MovieNightsDAO();
    $this->imdbMoviesDAO = new ImdbMoviesDAO();
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
      $this->set('myMovieNights', $this->movieNightsDAO->selectAll());
    }
  }

  public function extraQuestions() {
    $this->set('title', 'Setup - Step Two');

    $horrorMovieIds = $this->imdbMoviesDAO->selectByGenres(['horror'], true, 5000); // DOESNT WORK TODO !!!!
    $filteredMovies = $this->filterMoviesByCategoryKeywords($horrorMovieIds, 2);
    $filteredMovieIds = array_column($filteredMovies, 'movie_id');
    $filteredMovies = $this->filterMoviesByCategoryKeywords($filteredMovieIds, 1);
    $filteredMovieIds = array_column($filteredMovies, 'movie_id');


    if (isset($_GET['action']) && $_GET['action'] == 'filter')
    {
      if (!isset($_SESSION['filteredMovieIds']))
      {
        // Get movies by genre
        //$_SESSION['filteredMoviesIds'] = $this->imdbMoviesDAO->selectByGenre('horror')
      }


      $filterCategoriesDAO = new FilterCategoriesDAO();
      switch ($_GET['filter']) {
        case 'supernatural':
          $filterId = $filterCategoriesDAO->selectIdByName('supernatural');

          break;
        case 'psychological':
          break;
        case 'gore':


          break;

        default:
          # code...
          break;
      }


    }


    // To do next:
    // 1. creat dummy question cards (forms)

    // 2. Handle them here. Keep filter results per step with session
    // 3. make a detail page as finished. (show movie, the options you chose and a button to remove)


    $this->set('nbMoviesFound', count($filteredMovies));
    return;
    if (!empty($_POST['action']))
    {
      if ($_POST['action'] == 'setupFinish')
      {
        // insert a post
        header('location: index.php?page=detail');
        exit();
      }
    }
  }

  public function filterMoviesByCategoryKeywords($inMovieIds, $inCategoryFilterId)
  {
    $filterKeywordsDAO = new FilterCategoryKeywordsDAO();
    $filterKeywordIds = $filterKeywordsDAO->selectKeywordIdsbyCategoryId($inCategoryFilterId);

    $outMovies = array();
    $imdbMoviesKeywordsDOA = new ImdbMoviesKeywordsDAO();
    $bSqlMethod = true;
    if ($bSqlMethod)
    {
      // Implementation 2: Much faster. Takes around a second to process 4600 movies
      $outMovies = $imdbMoviesKeywordsDOA->selectMovieIdsWithKeywordIds($inMovieIds, $filterKeywordIds);
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
    $this->set('title', 'Detail - Your Movie Night');


  }

}
