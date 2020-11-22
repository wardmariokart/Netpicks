<?php

class DAO {

  // Properties
  private static $dbHost = 'mysql';
	private static $dbName = 'planItdb';
	private static $dbUser = 'planIt';
	private static $dbPass = 'wilmaisstinkyfish:(';
	private static $sharedPDO;
  protected $pdo;

  protected $tableName;

  // Constructor
	function __construct($tableName) {

    $this->tableName = $tableName;

		if(empty(self::$sharedPDO)) {
			self::$sharedPDO = new PDO('mysql:host=' . self::$dbHost . ';dbname=' . self::$dbName, self::$dbUser, self::$dbPass);
			self::$sharedPDO->exec('SET CHARACTER SET utf8');
			self::$sharedPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$sharedPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

		$this->pdo =& self::$sharedPDO;
	}

  // Methods
  public function selectById($id)
  {
    $sql = "SELECT * FROM `" . $this->tableName . "` WHERE `id` = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function selectAll()
  {
    $sql = "SELECT * FROM `" . $this->tableName . "`";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
 ?>
