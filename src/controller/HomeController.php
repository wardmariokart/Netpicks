<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/NightTypesDAO.php';
require_once __DIR__ . '/../dao/StepOneMovieOptionsDAO.php';
require_once __DIR__ . '/../dao/MovieNightsDAO.php';
require_once __DIR__ . '/../dao/ImdbMoviesDAO.php';
require_once __DIR__ . '/../dao/ImdbMoviesKeywordsDAO.php';
require_once __DIR__ . '/../dao/FilterCategoryKeywordsDAO.php';
require_once __DIR__ . '/../dao/FilterCategoriesDAO.php';
require_once __DIR__ . '/../dao/NetpicksQuestionsDAO.php';
require_once __DIR__ . '/../dao/MovieNightAnswersDAO.php';
require_once __DIR__ . '/../dao/ImdbMoviesGenresDAO.php';

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

    $bJavascriptCall = isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json';
    if(!$bJavascriptCall)
    {
      // Reset all $_SESSION variables
      $_SESSION['step2']['filteredMovieIds'] = array();
      $_SESSION['step2']['answers'] = array();
      unset($_SESSION['step2']['pickedMovieId']);

      $this->set('stepOne', $stepOne);
      $this->setupQuestionCards($stepOne);
      $this->setupFilteredMovieIds($stepOne);
    }

    // Javascript action
    if ($bJavascriptCall)
    {
      $content = trim(file_get_contents('php://input'));
      $data = json_decode($content, true);

      if ($data['action'] === 'filter')
      {
        $jsAnswer = array();
        $this->saveAnswer($data['questionId'], $data['answer']);
        $this->handleFilterActionJs($data);
        $jsAnswer['updateMoviesLeft'] = count($_SESSION['step2']['filteredMovieIds']);


        if (intval($data['nbQuestionsLeft']) === 0)
        {
          // Pick & propose a movie
          $jsAnswer['proposeMovie'] = $this->proposeMovie();
        }
        echo json_encode($jsAnswer);
        exit();
      }
      else if ($data['action'] === 'proposeResponse')
      {

        $jsAnswer = array();

        if ($data['answer'] === 'reject')
        {
          $jsAnswer['proposeMovie'] = $this->proposeMovie();
        }
        else if ($data['answer'] === 'accept')
        {
          $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : false;
          $result = $this->createMovieNight($stepOne, $userId, $_SESSION['step2']['pickedMovieId'], $_SESSION['step2']['answers']);

          if($result !== false)
          {
            if ($result['bOwnerless'])
            {
              $_SESSION['ownerlessMovieNightId'] = $result['movieNight']['id'];
            }
          }
          // insert new post and go to that post
          $jsAnswer['redirect'] = array('url' => '?page=detail&id=' . $result['movieNight']['id']);

        }

        echo json_encode($jsAnswer);
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
    }


    $this->set('nbMoviesFound', count($_SESSION['step2']['filteredMovieIds']));

  }

  // $answerStr can be 'included', 'excluded' or 'skipped'
  private function saveAnswer($questionId, $answerStr)
  {
    array_push($_SESSION['step2']['answers'], array('questionId' => $questionId, 'answer' => $answerStr));
  }

  private function setupFilteredMovieIds($stepOneInputs)
  {
    $imdbMoviesGenresDAO = new ImdbMoviesGenresDAO();
    $genresIds = array();
    if(isset($stepOneInputs['movieOptionOne'])) array_push($genresIds, $stepOneInputs['movieOptionOne']['imdb_genre_id']);
    if(isset($stepOneInputs['movieOptionTwo'])) array_push($genresIds, $stepOneInputs['movieOptionTwo']['imdb_genre_id']);
    $_SESSION['step2']['filteredMovieIds'] = $imdbMoviesGenresDAO->selectMovieIdsWithGenresId($genresIds);
  }

  private function setupQuestionCards($stepOneInputs)
  {
    // select cards for movie option one
    $netpicksQuestionsDAO = new NetpickQuestionsDAO();
    $optionOneQuestions = $netpicksQuestionsDAO->selectAllByMovieOption($stepOneInputs['movieOptionOne']['id']);
    $optionTwoQuestions = $netpicksQuestionsDAO->selectAllByMovieOption($stepOneInputs['movieOptionTwo']['id']);

    $this->set('questions', array_merge($optionOneQuestions, $optionTwoQuestions));
  }

  private function handleConfirmPick($userId, $pickId)  // TODO
  {
    unset($_SESSION['step2']['filteredMovieIds']);
  }

  private function handleFilterActionJs($data)
  {
    if (isset($data['filterType']))
    {

      // take current movieIds and apply a filter or reject to it.
      $answer = $data['answer'];
      if ($answer === 'include' || $answer === 'exclude')
      {
        $_SESSION['step2']['filteredMovieIds'] = $this->filterMoviesByCategoryKeywords($_SESSION['step2']['filteredMovieIds'], $data['filterType'], $answer);
      }
      return;

      switch ($data['filterType'])
      {


        // Flow of this page
        // first time entering => Create and display 4 questions. First movie selection based on step one answers. Movie options are linked with genres. Select multiple genres and their movie ids (give option if move must have both or either)
        // Send off card  => Receive fetch request (action = filter, filterId = question.filterId, answer = include, exclude or skip, nbQuestionLeft: 2). 1. Do filtering using the existing function. 2. if 0 questions left. Respond with 'Present movie'  3. Respond with number of possible movies left
        // send off another card
        // Send off last card
        // Show picked movie for you => in JS we receive respons['pickedMovie'] is set. Display the picked movie on top of screen. with little spinning animation behind it and black out background.
        // Reject that movie (throw left) => In php we receive action = 'RejectPropsedMovie'. In JS we receive the same as above.
        // Accept that movie (throw right) => From js we send fetch to 'acceptProposedMovie' and we create a new post with all the information needed (id, nightType, movieOption1 & 2, movieNightName, AND question and answer are stored in a new table (id, question_id, 'included','excluded','skipped'))

          case 'supernatural':
            if($data['filterSupernatural'] == 'true')
            {

              $_SESSION['step2']['filteredMovieIds'] = array_column($this->filterMoviesByCategoryKeywords($_SESSION['step2']['filteredMovieIds'], $data['filterType'], 'include'), 'movie_id');
            }
            else if ($data['filterSupernatural'] == 'false')
            {
              $result =  $this->filterMoviesByCategoryKeywords($_SESSION['step2']['filteredMovieIds'], $data['filterType'], 'exclude');
              $_SESSION['step2']['filteredMovieIds'] = $result;
            }
            else if ($_POST['filterSupernatural'] == 'skip')
            {
              // do nothing
            }
          break;
          default:
          unset($_SESSION['step2']['filteredMovieIds']);
          $_SESSION['error'] = $_POST['filterType'] . 'Is invalid filter type';
          header('location: index.php');
          exit();
        break;
      }
    }
  }

  private function proposeMovie ()
  {
    $filteredId = $_SESSION['step2']['filteredMovieIds'][array_rand($_SESSION['step2']['filteredMovieIds'])];
    $pickedMovie = $this->imdbMoviesDAO->selectById($filteredId);
    $_SESSION['step2']['pickedMovieId'] = $filteredId; // saved so user cannot mess with this value (if he wanted to)
    return $pickedMovie;
  }

  private function safeKeySelector($array, $key, $alternative = false)
  {
    return isset($array[$key]) ? $array[$key] : $alternative;
  }

  private function filterMoviesByCategoryKeywords($inMovieIds, $categoryFilterId, $includeOrExclude)
  {
    if(!is_numeric($categoryFilterId))
    {
      $_SESSION['error'] = 'Your php code is still using a string for the filter category while it should be an id. (from HomeController::filterMoviesByCategoryKeywords)';
      exit();

    }

    $filterKeywordsDAO = new FilterCategoryKeywordsDAO();
    $filterKeywordIds = $filterKeywordsDAO->selectKeywordIdsbyCategoryId($categoryFilterId);

    $outMovies = array();
    $imdbMoviesKeywordsDOA = new ImdbMoviesKeywordsDAO();
    $bSqlMethod = true;
    if ($bSqlMethod)
    {

      if ($includeOrExclude == 'include')
      {
        $outMovies = array_column($imdbMoviesKeywordsDOA->filterMovieIdsWithKeywordIds($inMovieIds, $filterKeywordIds), 'movie_id');
      }
      else if ($includeOrExclude == 'exclude')
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

  private function createMovieNight($stepOne, $userId, $pickedMovieId, $answers)
  {

      // signed in
      $insertData = array();
      $insertData['userId'] = $userId === false ? -1 : $userId;
      $insertData['movieId'] = $_SESSION['step2']['pickedMovieId'];
      $insertData['movieOptionOneId'] = $stepOne['movieOptionOne']['id'];
      $insertData['movieOptionTwoId'] = $stepOne['movieOptionTwo']['id'];
      $insertData['nightTypeId'] = $stepOne['nightType']['id'];
      $insertData['name'] = 'Temporary movie night name';

      $insertedMovieNight = $this->movieNightsDAO->insert($insertData);
      if($insertedMovieNight !== false)
      {
        $_SESSION['info'] = 'Movie night has been created';
        // Now add the given answers

        $movieNightAnswersDAO = new MovieNightAnswersDAO();
        foreach($_SESSION['step2']['answers'] as $answer)
        {
          $insertData = array();  // length should be 0 at this point
          $insertData['questionId'] = $answer['questionId'];
          $insertData['answer'] = $answer['answer'];
          $insertData['movieNightId'] = $insertedMovieNight['id'];
          $movieNightAnswersDAO->insert($insertData);
        }

        $out = array('movieNight' => $insertedMovieNight, 'bOwnerless' => $userId === false);
        return $out;
      }
      return false;
  }

  public function detail() {

    $bOwnerless = isset($_SESSION['ownerlessMovieNightId']);
    // if ownerless, there should be a button to claim it by signing in or signing up.
    $this->set('bOwnerless', $bOwnerless);

    $movieNightId = $this->safeKeySelector($_GET, 'id', '1');
    $movieNightRow = $this->movieNightsDAO->selectById($movieNightId);
    $this->set('title', $movieNightRow['name']);
    $imdbMovieRow = $this->imdbMoviesDAO->selectById($movieNightRow['movie_id']);
    $details = array('movie' => $imdbMovieRow);
    $this->set('details', $details);
  }

}
