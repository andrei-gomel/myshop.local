<?

/**
* categoryController.php
*
* Контроллер страницы категорий (/category/1)
*
*/

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';

/**
* Формирование страницы категорий
*
* @param object $smarty шаблонизатор
*/

function indexAction($smarty)
{
  $catId = isset($_GET['id']) ? $_GET['id'] : null;
  if($catId == null) exit;
  $rsProducts = null;
  $rsChaldCats = null;

  $rsCategory = getCatById($catId);

  // если главная категория то показываем дочерние категории,
  // иначе показываем товар
  if($rsCategory['parent_id'] == 0)
  {
    $rsChaldCats = getChildrenForCat($catId);
  }
  else {
    $rsProducts = getProductsByCat($catId);
  }
  //d($rsChaldCats);
  $rsCategories = getAllMainCatsWithChildren();

  $smarty->assign('pageTitle', 'Товары категории ' . $rsCategory['name']);

  $smarty->assign('rsCategory', $rsCategory);
  /*
  if($rsProducts == null AND $rsChaldCats == null)
  {
    $smarty->assign('noProducts', 'В этой категории товаров нет');
  }*/
  
  
  $smarty->assign('rsProducts', $rsProducts);
  //d($rsProducts);
  $smarty->assign('rsChaldCats', $rsChaldCats);

  $smarty->assign('rsCategories', $rsCategories);

  loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'category');
	loadTemplate($smarty, 'footer');
}
