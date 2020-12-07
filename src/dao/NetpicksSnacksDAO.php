<?php
require_once (__DIR__ . '/DAO.php');

class NetpicksSnacksDAO extends DAO {
  function __construct()
  {
    parent::__construct('filter_categories');
  }
}
