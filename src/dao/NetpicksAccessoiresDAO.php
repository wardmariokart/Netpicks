<?php
require_once (__DIR__ . '/DAO.php');

class NetpicksAccessoiresDAO extends DAO {
  function __construct()
  {
    parent::__construct('netpicks_accessoires');
  }
}
