<?php

class DAO {

  // Properties
  private static $dbHost = 'ID321519_planitdb.db.webhosting.be';
	private static $dbName = 'ID321519_planitdb';
	private static $dbUser = 'ID321519_planitdb';
	private static $dbPass = 'devine4life';
	private static $sharedPDO;
  protected $pdo;

  protected $tableName;

  // Constructor
	function __construct($tableName) {

    $this->tableName = $tableName;

		if(empty(self::$sharedPDO)) {
			self::$sharedPDO = new PDO('mysql:host=' . self::$dbHost . ';dbname=' . self::$dbName, self::$dbUser, self::$dbPass);
			self::$sharedPDO->exec('SET CHARACTER SET utf8mb4');
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
    $sql = "SELECT * FROM `" . $this->tableName . "` ORDER BY `id` ASC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
 ?>
