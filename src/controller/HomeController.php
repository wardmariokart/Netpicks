<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/NightTypesDAO.php';

class HomeController extends Controller {

  private $nightTypesDAO;

  function __construct() {
    $this->nightTypesDAO = new NightTypesDAO();
  }

  public function home() {
    $this->set('title', 'Movie Night Planner 🍿');



    // required data: DateNights, KindsOfMovies
    $this->set('nightTypes', $this->nightTypesDAO->selectAll());

  }
}
