<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/NightTypeDAO.php';

class HomeController extends Controller {

  private $nightTypesDAO;

  function __construct() {
    $this->nightTypesDAO = new NightTypesDAO();
  }

  public function home() {
    $this->set('title', 'Movie Night Planner 🍿');
  }
}
