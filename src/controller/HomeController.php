<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/NightTypesDAO.php';
require_once __DIR__ . '/../dao/StepOneMovieOptionsDAO.php';

class HomeController extends Controller {

  private $nightTypesDAO;
  private $stepOneMovieOptionsDAO;

  function __construct() {
    $this->nightTypesDAO = new NightTypesDAO();
    $this->stepOneMovieOptionsDAO = new StepOneMovieOptionsDAO();
  }

  public function home() {
    $this->set('title', 'Movie Night Planner 🍿');

    // required data: DateNights, KindsOfMovies
    $this->set('nightTypes', $this->nightTypesDAO->selectAll());
    $this->set('stepOneOptions', $this->stepOneMovieOptionsDAO->selectAll());
  }

  public function wizardStepTwo() {

  }

}
