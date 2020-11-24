<?php
require_once (__DIR__ . '/DAO.php');

class MovieNightsDAO extends DAO {
  function __construct()
  {
    parent::__construct('PlannedMovieNights');
  }
}
