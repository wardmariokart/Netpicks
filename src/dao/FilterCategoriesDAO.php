<?php
require_once (__DIR__ . '/DAO.php');

class FilterCategoriesDAO extends DAO {
  function __construct()
  {
    parent::__construct('filter_categories');
  }
}
