<?php
require_once __DIR__ . '/../dao/MovieNightsDAO.php';

class Controller {

  public $route;
  protected $viewVars = array();
  protected $env = 'development';
  private $sessionLifespans = array(
    array('property' => 'step2',
          'allowedPage' => 'extraQuestions'),
    array('property' => 'detail',
          'allowedPage' => 'detail')
  );

  public function filter() {
    if (basename(dirname(dirname(__FILE__))) != 'src') {
      $this->env = 'production';
    }
    call_user_func(array($this, $this->route['action']));

    if(isset($_SESSION['detail']['ownerlessMovieNightId']) && $this->route['action'] !== 'detail')
    {
      $movieNightsDAO = new MovieNightsDAO();
      $movieNightsDAO->deleteById($_SESSION['detail']['ownerlessMovieNightId']);
      unset($_SESSION['detail']['ownerlessMovieNightId']);
    }

    foreach($this->sessionLifespans as $lifespan)
    {
      if ($this->route['action'] !== $lifespan['allowedPage'] && isset($_SESSION[$lifespan['property']]))
      {
        unset($_SESSION[$lifespan['property']]);
      }
    }
  }

  public function render() {
    // set js variable according to environment (development / production)
    $this->set('js', '<script src="http://localhost:8900/script.js"></script>'); // webpack dev server
    // NEW : CSS
    $this->set('css', ''); // webpack dev server: css is injected by the script
    if ($this->env == 'production') {
      $this->set('js', '<script src="script.js"></script>'); // regular script
      $this->set('css', '<link href="style.css" rel="stylesheet">'); // regular css tag
    }
    $this->createViewVarWithContent();
    $this->renderInLayout();
    if (!empty($_SESSION['info'])) {
      unset($_SESSION['info']);
    }
    if (!empty($_SESSION['error'])) {
      unset($_SESSION['error']);
    }
  }

  public function set($variableName, $value) {
    $this->viewVars[$variableName] = $value;
  }

  private function createViewVarWithContent() {
    extract($this->viewVars, EXTR_OVERWRITE);
    ob_start();
    require __DIR__ . '/../view/' . strtolower($this->route['controller']) . '/' . $this->route['action'] . '.php';
    $content = ob_get_clean();
    $this->set('content', $content);
    $this->set('page', $this->route['action']);
  }

  private function renderInLayout() {
    extract($this->viewVars, EXTR_OVERWRITE);
    include __DIR__ . '/../view/layout.php';
  }



}
