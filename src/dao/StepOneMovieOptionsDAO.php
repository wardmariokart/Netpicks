<?php

require_once( __DIR__ . '/DAO.php');

class StepOneMovieOptionsDAO extends DAO {

  function __construct()
  {
    parent::__construct('movie_options');
  }

}
