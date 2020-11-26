<?php

require_once( __DIR__ . '/DAO.php');

class NightTypesDAO extends DAO {

  function __construct()
  {
    parent::__construct('nights');
  }

  public function validate( $data ){
    $errors = [];
    if (!isset($data['created'])) {
      $errors['created'] = 'Gelieve created in te vullen';
    }
    if (!isset($data['modified'])) {
      $errors['modified'] = 'Gelieve modified in te vullen';
    }
    return $errors;
  }

}
