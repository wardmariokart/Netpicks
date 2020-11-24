<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/NightTypesDAO.php';
require_once __DIR__ . '/../dao/StepOneMovieOptionsDAO.php';
require_once __DIR__ . '/../dao/MovieNightsDAO.php';

class HomeController extends Controller {

  private $nightTypesDAO;
  private $stepOneMovieOptionsDAO;
  private $movieNightsDAO;

  function __construct() {
    $this->nightTypesDAO = new NightTypesDAO();
    $this->stepOneMovieOptionsDAO = new StepOneMovieOptionsDAO();
    $this->movieNightsDAO = new MovieNightsDAO();
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

  public function detail() {
    $this->set('title', 'Detail - Your Movie Night');


  }

}
