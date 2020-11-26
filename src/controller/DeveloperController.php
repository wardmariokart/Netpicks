<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/FilterCategoriesDAO.php';
require_once __DIR__ . '/../dao/ImdbKeywordsDAO.php';
require_once __DIR__ . '/../dao/FilterCategoryKeywordsDAO.php';


class DeveloperController extends Controller {

  private $filterCategoriesDAO;
  private $imdbKeywordsDAO;
  private $filterCategoryKeywordsDAO;

  function __construct() {
    $this->filterCategoriesDAO = new FilterCategoriesDAO();
    $this->imdbKeywordsDAO = new ImdbKeywordsDAO();
    $this->filterCategoryKeywordsDAO = new FilterCategoryKeywordsDAO();
  }

  public function devTools() {
    $this->set('title', 'Developer Tools');


    // 1. Get filter categories
    $this->set('filterCategories', $this->filterCategoriesDAO->selectAll());

    if (!empty($_POST['action']))
    {
      if ($_POST['action'] == 'add-filter-keywords' && isset($_POST['filter-keywords']))
      {
        $category = $_POST['filter-category'];
        $keywords = explode(',', $_POST['filter-keywords']);

        $keywordsInserts = array();
        $successfulInserts = 0;
        foreach($keywords as $keyword)
        {
          $imdbKeyword = $this->imdbKeywordsDAO->selectByKeyword($keyword);
          if (isset($imdbKeyword['id']))
          {
            $categoryId = $_POST['filter-category'];
            $checkDuplicateData = array('categoryId' => $categoryId, 'keywordId' => $imdbKeyword['id']);
            $bDuplicate = $this->filterCategoryKeywordsDAO->selectByKeywordIdAndCategoryId($checkDuplicateData);

            if (!$bDuplicate)
            {

              $insertData = array('categoryId' => $categoryId, 'keywordId' => $imdbKeyword['id']);
              $insertResult = $this->filterCategoryKeywordsDAO->insert($insertData);
              if ($insertResult == false)
              {
                $keywordInserts[$keyword] = 'insertFailed';
              }
              else
              {
                $keywordInserts[$keyword] = 'insertOk';
                $successfulInserts += 1;
              }
            }
            else
            {
              $keywordInserts[$keyword] = 'duplicate';
            }
          }
          else
          {
            $keywordInserts[$keyword] = 'keyword not in imdb_keywords table';
          }
        }

        $info = strval($successfulInserts) . ' Keywords have been added. ';
        foreach($keywordInserts as $keyword => $bInserted)
        {
          if ($bInserted == 'insertOk')
          {
            $info .= '\'' .  $keyword . '\' ';
          }
        }

        $info .= '. Keywords not added because not found in imdb_keywords or duplicate: ';
        foreach($keywordInserts as $keyword => $bInserted)
        {
          if ($bInserted == 'keyword not in imdb_keywords table' || $bInserted == 'duplicate')
          {
            $info .= '\'' .  $keyword . '\' ';
          }
        }
        // Do something with $keywordInserts
        $_SESSION['info'] = $info;
        header('location:index.php?page=devTools');
        exit();
      }
    }

  }

}
