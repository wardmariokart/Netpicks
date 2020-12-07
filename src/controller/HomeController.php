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
  private $movieNightAnswersDAO;
  private $imdbMoviesGenresDAO;
  private $netpicksQuestionsDAO;

  function __construct() {
    $this->imdbMoviesDAO = new ImdbMoviesDAO();
    $this->imdbMoviesGenresDAO = new ImdbMoviesGenresDAO();
    $this->movieNightsDAO = new MovieNightsDAO();
    $this->movieNightAnswersDAO = new MovieNightAnswersDAO();
    $this->nightTypesDAO = new NightTypesDAO();
    $this->netpicksQuestionsDAO = new NetpicksQuestionsDAO();
    $this->filterCategoriesDAO = new FilterCategoriesDAO();
    $this->stepOneMovieOptionsDAO = new StepOneMovieOptionsDAO();
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
      $_SESSION['step2']['filteredMovieIds'] = $this->selectMovieIdsByStepOneInputs($stepOne);
    }

    // Javascript action
    if ($bJavascriptCall)
    {
      $content = trim(file_get_contents('php://input'));
      $data = json_decode($content, true);
      $jsAnswer = array();

      if ($data['action'] === 'filter')
      {
        $this->handleFilterActionJs($data, $jsAnswer);
      }
      else if ($data['action'] === 'proposeResponse')
      {

        if ($data['answer'] === 'reject')
        {
          $jsAnswer['proposeMovie'] = $this->proposeMovie($_SESSION['step2']['filteredMovieIds']);
          $_SESSION['step2']['pickedMovieId'] = $jsAnswer['proposeMovie']['id']; // saved so user cannot mess with this value (if he wanted to)
        }
        else if ($data['answer'] === 'accept')
        {
          $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : false;
          $result = $this->createMovieNight($stepOne, $userId, $_SESSION['step2']['pickedMovieId'], $_SESSION['step2']['answers']);

          if($result !== false)
          {
            if ($result['bOwnerless'])
            {
              $_SESSION['detail']['ownerlessMovieNightId'] = $result['movieNight']['id'];
            }
          }
          // insert new post and go to that post
          $jsAnswer['redirect'] = array('url' => '?page=detail&id=' . $result['movieNight']['id']);
        }
      }
      else if ($data['action'] === 'noMovieFoundResponse')
      {
          if ($data['answer'] === 'tryAgain')
          {
            $jsAnswer['redirect'] = array('url' => '?page=extraQuestions&nightType=' . $_GET['nightType'] . '&movieOptionOne=' . $_GET['movieOptionOne'] . '&movieOptionTwo=' . $_GET['movieOptionTwo']);
          }
          else if ($data['answer'] === 'closestMovie')
          {
            $proposedMovie = $this->proposeMovieFromScratch($stepOne, $_SESSION['step2']['answers']);
            if($proposedMovie !== false)
            {
              $jsAnswer['proposeMovie'] = $proposedMovie;
              $_SESSION['step2']['pickedMovieId'] = $jsAnswer['proposeMovie']['id']; // saved so user cannot mess with this value (if he wanted to)
            }
          }
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

      echo json_encode($jsAnswer);
      exit();
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

  private function selectMovieIdsByStepOneInputs($stepOneInputs)
  {
    $genresIds = array();
    if(isset($stepOneInputs['movieOptionOne'])) array_push($genresIds, $stepOneInputs['movieOptionOne']['imdb_genre_id']);
    if(isset($stepOneInputs['movieOptionTwo'])) array_push($genresIds, $stepOneInputs['movieOptionTwo']['imdb_genre_id']);
    return $this->imdbMoviesGenresDAO->selectMovieIdsWithGenresId($genresIds);
  }

  private function setupQuestionCards($stepOneInputs)
  {
    // select cards for movie option one
    $optionOneQuestions = $this->netpicksQuestionsDAO->selectAllByMovieOption($stepOneInputs['movieOptionOne']['id']);
    $optionTwoQuestions = $this->netpicksQuestionsDAO->selectAllByMovieOption($stepOneInputs['movieOptionTwo']['id']);

    $this->set('questions', array_merge($optionOneQuestions, $optionTwoQuestions));
  }

  private function handleConfirmPick($userId, $pickId)  // TODO
  {
    unset($_SESSION['step2']['filteredMovieIds']);
  }

  private function handleFilterActionJs($data, &$jsAnswer)
  {
    if (isset($data['filterType']))
    {
      // take current movieIds and apply a filter or reject to it.
      $answer = $data['answer'];
      if ($answer === 'include' || $answer === 'exclude')
      {
        $_SESSION['step2']['filteredMovieIds'] = $this->filterMoviesByCategoryKeywords($_SESSION['step2']['filteredMovieIds'], $data['filterType'], $answer);
      }

      $jsAnswer['updateMoviesLeft'] = array ();
      $filteredCount = count($_SESSION['step2']['filteredMovieIds']);
      $jsAnswer['updateMoviesLeft']['count'] = $filteredCount;

      if ($filteredCount === 0)
      {
        $jsAnswer['noMoviesFound'] = true;
        $questionsToAutoSkip = array();
        $questionsLeft = explode(',',$data['questionsLeft']);
        $questionsToAutoSkip = array_merge([$data['questionId']], $questionsLeft);
        foreach($questionsToAutoSkip as $questionId)
        {
          $this->saveAnswer($questionId, 'skip');
        }
        $jsAnswer['noMoviesFound'] = true;
        return;
      }
      else
      {
        $this->saveAnswer($data['questionId'], $data['answer']);

        $bWasLastQuestion = intval($data['nbQuestionsLeft']) === 0;
        if ($bWasLastQuestion)
        {
          // Pick & propose a movie
          $proposedMovie = $this->proposeMovie($_SESSION['step2']['filteredMovieIds']);
          if($proposedMovie !== false)
          {
            $jsAnswer['proposeMovie'] = $proposedMovie;
            $_SESSION['step2']['pickedMovieId'] = $jsAnswer['proposeMovie']['id']; // saved so user cannot mess with this value (if he wanted to)
          }
        }
      }
    }
  }

  private function proposeMovie($filteredMovieIds)
  {
    $pickedMovie = false;
    if (count($filteredMovieIds) > 0)
    {
      $filteredId = $filteredMovieIds[array_rand($filteredMovieIds)];
      $pickedMovie = $this->imdbMoviesDAO->selectById($filteredId);
    }
    return $pickedMovie;
  }

  private function safeKeySelector($array, $key, $alternative = false)
  {
    return isset($array[$key]) ? $array[$key] : $alternative;
  }

  // answer should be 'include', 'exclude' or 'skip'
  private function filterMoviesByCategoryKeywords($inMovieIds, $categoryFilterId, $answer)
  {
    if(!is_numeric($categoryFilterId))
    {
      $_SESSION['error'] = 'Your php code is still using a string for the filter category while it should be an id. (from HomeController::filterMoviesByCategoryKeywords)';
      exit();
    }

    if (empty($inMovieIds))
    {
      return $inMovieIds;
    }

    $filterKeywordsDAO = new FilterCategoryKeywordsDAO();
    $filterKeywordIds = $filterKeywordsDAO->selectKeywordIdsbyCategoryId($categoryFilterId);

    $outMovies = array();
    $imdbMoviesKeywordsDOA = new ImdbMoviesKeywordsDAO();

    if ($answer === 'include')
    {
      $outMovies = $imdbMoviesKeywordsDOA->filterMovieIdsByKeywordCategoryId($inMovieIds, $categoryFilterId);
    }
    else if ($answer === 'exclude')
    {
      $outMovies = $imdbMoviesKeywordsDOA->rejectMovieIdsByFilterCategoryId($inMovieIds, $categoryFilterId);
    }
    else if ($answer === 'skip')
    {
      $outMovies = $inMovieIds;
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
      foreach($_SESSION['step2']['answers'] as $answer)
      {
        $insertData = array();  // length should be 0 at this point
        $insertData['questionId'] = $answer['questionId'];
        $insertData['answer'] = $answer['answer'];
        $insertData['movieNightId'] = $insertedMovieNight['id'];
        $this->movieNightAnswersDAO->insert($insertData);
      }
      $out = array('movieNight' => $insertedMovieNight, 'bOwnerless' => $userId === false);
      return $out;
    }
    return false;
  }

  public function detail()
  {
      $this->set('title', 'Your movie night');  // Hidden

    if (!isset($_SESSION['detail']))
    {
      $_SESSION['detail'] = array();
    }

    // Is id valid?
    $movieNight = false;
    if (isset($_GET['id']))
    {
      $movieNight = $this->movieNightsDAO->selectById($_GET['id']);
    }

    if ($movieNight === false)
    {
      $_SESSION['error'] = 'The movie night you wanted to access doesn\'t exist';
      header('location: index.php');
      exit();
    }

    // Can the visitor delete this post?
    $bIsOwner = false;
    if (isset($_SESSION['user']))
    {
      $bIsOwner = $_SESSION['user']['id'] === $movieNight['user_id'];
    }
    $this->set('bIsOwner', $bIsOwner);

    // is it an ownerless movie night?
    $bOwnerless = isset($_SESSION['detail']['ownerlessMovieNightId']);
    $this->set('bOwnerless', $bOwnerless);


    $settings = $this->movieNightAnswersDAO->selectAllByMovieNight($movieNight['id']);
    $movieNight['settings'] = $this->transformAnswerOfSettings($settings);

    $movie = $this->imdbMoviesDAO->selectById($movieNight['movie_id']);
    $this->set('movieNight', $movieNight);
    $details = array('movie' => $movie);
    $this->set('details', $details);

    $bJavascriptCall = isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json';
    if ($bJavascriptCall)
    {
      $content = trim(file_get_contents('php://input'));
      $jsPost = json_decode($content, true);

      $jsAnswer = array();
      if (isset($jsPost['action']))
      {
        if ($jsPost['action'] === 'updateSettingsRequest')
        {
          $this->handleUpdateSettingRequestJs($jsPost, $jsAnswer);
        }
        else if ($jsPost['action'] === 'filter')
        {
          $this->handleUpdateSettingJs($jsPost, $movieNight, $jsAnswer);
        }
        else if ($jsPost['action'] === 'proposeResponse')
        {
          $this->handleProposeResponseJs($jsPost, $movieNight, $jsAnswer);
        }
      }
      echo json_encode($jsAnswer);
      exit();
    }


    if (!empty($_POST['action']))
    {
      if ($_POST['action'] === 'delete')
      {
        $deleteId = $_GET['id'];

        // check if you should be able to delete -> must be either ownerless OR user must be owner of movienight
        $this->movieNightsDAO->deleteById($_GET['id']);
        $_SESSION['info'] = 'Movie night has been deleted';
        header('location: index.php');
        exit();
      }
    }
  }

  private function transformAnswerOfSettings($settings)
  {
    $answerTransform = array();
    $answerTransform['include'] = 'yes';
    $answerTransform['exclude'] = 'no';
    $answerTransform['skip'] = 'allowed';
    foreach($settings as $index => $setting)
    {
      $newAnswer = $answerTransform[$setting['answer']];
      $settings[$index]['answer'] = $newAnswer;
    }
    return $settings;
  }

  private function handleUpdateSettingRequestJs($jsPost, &$jsAnswerRef)
  {
    $question = $this->netpicksQuestionsDAO->selectById($jsPost['questionId']);
    $jsAnswerRef['showQuestion'] = $question;
    $jsAnswerRef['showQuestion']['answerId'] = $jsPost['answerId'];
  }

  private function handleUpdateSettingJs($jsPost, $movieNight, &$jsAnswerRef)
  {
    $answerId = $jsPost['answerId'];
    $newAnswer = $jsPost['answer'];
    $updateResult = $this->movieNightAnswersDAO->updateAnswer($answerId, $newAnswer);
    if ($updateResult !== false)
    {
      $proposedMovie = $this->proposeMovieFromMovieNightId($movieNight['id']);
      $_SESSION['detail']['proposedMovieId'] = $proposedMovie['id'];
      $jsAnswerRef['proposeMovie'] = $proposedMovie;
    }
  }

  private function handleProposeResponseJs($jsPost, $movieNight, &$jsAnswerRef)
  {
    if ($jsPost['answer'] === 'accept')
    {
      $udpateData = array();
      $updateData['movieNightId'] = $movieNight['id'];
      $updateData['newMovieId'] = $_SESSION['detail']['proposedMovieId'];
      $result = $this->movieNightsDAO->updateMovieId($updateData);
      if($result === false)
      {
        $_SESSION['error'] = 'Failed to update movie night';
        header('location:index.php');
        exit();
      }

      $jsAnswerRef['movieUpdated'] = true;// reload page
    }
    else if ($jsPost['answer'] === 'reject')
    {
      $proposedMovie = $this->proposeMovie($_SESSION['detail']['filteredMovieIds']);
      $_SESSION['detail']['proposedMovieId'] = $proposedMovie['id'];
      $jsAnswerRef['proposeMovie'] = $proposedMovie;
    }
  }

  // Note: function is meant to be used when on page 'extraQuestions.php'
  private function proposeMovieFromScratch($stepOneInputs, $savedAnswers)
  {
    $filteredMovieIds = $this->selectMovieIdsByStepOneInputs($stepOneInputs);

    foreach($savedAnswers as $answer)
    {
      $filterId = $this->netpicksQuestionsDAO->selectById($answer['questionId'])['filter_category_id'];
      $filteredMovieIds = $this->filterMoviesByCategoryKeywords($filteredMovieIds, $filterId, $answer['answer']);
    }

    $_SESSION['step2']['filteredMovieIds'] = $filteredMovieIds;
    $proposedMovie = $this->proposeMovie($filteredMovieIds);
    return $proposedMovie;
  }


  // Note: function is meant to be used when on page 'detail.php'
  private function proposeMovieFromMovieNightId ($movieNightId)
  {
    // 1. Movies Id's by selected movie options (step one)
    $movieNight = $this->movieNightsDAO->selectById($movieNightId);
    $genreIds = array();
    array_push($genreIds, $this->stepOneMovieOptionsDAO->selectById($movieNight['movie_option_one_id'])['imdb_genre_id']);
    array_push($genreIds, $this->stepOneMovieOptionsDAO->selectById($movieNight['movie_option_two_id'])['imdb_genre_id']);
    $filteredMovieIds = $this->imdbMoviesGenresDAO->selectMovieIdsWithGenresId($genreIds);

    $answers = $this->movieNightAnswersDAO->selectAllByMovieNight($movieNight['id']);
    foreach($answers as $answer)
    {
      // get filter id
      $filterId = $this->filterCategoriesDAO->selectIdByName($answer['filter']);
      $filteredMovieIds = $this->filterMoviesByCategoryKeywords($filteredMovieIds, $filterId, $answer['answer']);
    }

    $_SESSION['detail']['filteredMovieIds'] = $filteredMovieIds;
    $proposedMovie = $this->proposeMovie($filteredMovieIds);
    return $proposedMovie;
  }

  private function invite()
  {
    $this->set('invite', 'Your movie night');
  }
}



// FIX: On answer, check if unanswered cards would have any results, if not the cards should be removed from the stack.
// => Therefore there should me way more questions
