<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/TodoDAO.php';

class TodosController extends Controller {

  private $todoDAO;

  function __construct() {
    $this->todoDAO = new TodoDAO();
  }

  public function index() {
    // We kijken of de gebruiken op de index geen todo wilt toevoegen
    if (!empty($_POST['action'])) {
      if ($_POST['action'] == 'insertTodo') {
        $this->handleInsertTodo();
      }
    }

    $todos = $this->todoDAO->selectAll();
    $this->set('todos', $todos); // todos worden opgehaald via JS, eigenlijk niet nodig
    $this->set('title', 'Overview');

    // in het geval van een request uit JavaScript sturen we JSON terug met todos
    if (strtolower($_SERVER['HTTP_ACCEPT']) == 'application/json') {
      header('Content-Type: application/json');
      echo json_encode($todos);
      exit();
    }
  }

  private function handleInsertTodo() {
    $data = array(
      'created' => date('Y-m-d H:i:s'),
      'modified' => date('Y-m-d H:i:s'),
      'checked' => 0,
      'text' => $_POST['text']
    );
    $insertTodoResult = $this->todoDAO->insert($data);
    if (!$insertTodoResult) {
      $errors = $this->todoDAO->validate($data);
      $this->set('errors', $errors);

      // in het geval van een POST request uit JavaScript sturen we JSON terug met errors
      if (strtolower($_SERVER['HTTP_ACCEPT']) == 'application/json') {
        header('Content-Type: application/json');
        echo json_encode(array(
          'result' => 'error',
          'errors' => $errors
        ));
        // We sturen de response direct terug
        exit();
      }
      $_SESSION['error'] = 'De todo kon niet toegevoegd worden!';
    } else {
      // in het geval van een POST request uit JavaScript sturen we JSON terug met aangemaakte todo
      if (strtolower($_SERVER['HTTP_ACCEPT']) == 'application/json') {
        header('Content-Type: application/json');
        echo json_encode(array(
          'result' => 'ok',
          'todo' => $insertTodoResult
        ));
        // We sturen de response direct terug
        exit();
      }
      $_SESSION['info'] = 'De todo is toegevoegd!';
      header('Location: index.php');
      exit();
    }
  }

}
