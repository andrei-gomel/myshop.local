<?

/**
* AdminController.php
*
* Контроллер бэкенда сайта (/admin/)
*
*/

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';
include_once '../models/OrdersModel.php';
include_once '../models/PurchaseModel.php';

//$smarty->template_dir = TemplateAdminPrefix;
$smarty->template_dir = TemplatePrefix;

$smarty->assign('templateWebPath', TemplateWebPath);

function indexAction($smarty)
{
  $rsCategories = getAllMainCategories();

  $smarty->assign('rsCategories', $rsCategories);
  $smarty->assign('pageTitle', 'Управление сайтом');

  loadTemplate($smarty, 'adminHeader');
  loadTemplate($smarty, 'admin');
  loadTemplate($smarty, 'adminFooter');
}

/**
* Добавление новой категории
*/
function addnewcatAction()
{
  $catName = $_POST['newCategoryName'];
  $catParentId = $_POST['generalCatId'];

  $res = insertCat($catName, $catParentId);

  if ($res)
  {
      $resData['success'] = 1;
      $resData['message'] = 'Категория добавлена';
  }
  else {
    $resData['success'] = 0;
    $resData['message'] = 'Ошибка добавления категории';
  }

  echo json_encode($resData);
  return;
}

/**
* Страница управления категориями
*
* @param type $smarty
*/
function categoryAction($smarty)
{
  $rsCategories = getAllCategories();
  $rsMainCategories = getAllMainCategories();
//d($rsCategories);
  $smarty->assign('rsCategories', $rsCategories);
  $smarty->assign('rsMainCategories', $rsMainCategories);
  $smarty->assign('pageTitle', 'Управление сайтом');

  loadTemplate($smarty, 'adminHeader');
  loadTemplate($smarty, 'adminCategory');
  loadTemplate($smarty, 'adminFooter');
}

/**
* Функция обновления категорий
*/
function updatecategoryAction()
{
  $itemId = $_POST['itemId'];
  $parentId = $_POST['parentId'];
  $newName = $_POST['newName'];

  $res = updatecategoryData($itemId, $parentId, $newName);

  if ($res)
  {
    $resData['success'] = 1;
    $resData['message'] = 'Категория обновлена';
  }
  else {
    $resData['success'] = 0;
    $resData['message'] = 'Ошибка изменения данных категории';
  }

  echo json_encode($resData);
  return;
}

/**
*
* Функция получения всех товаров
*
* @param type $smarty
*/
function productsAction($smarty)
{
  $rsCategories = getAllCategories();
  $rsProducts = getProducts();

  $smarty->assign('rsCategories', $rsCategories);
  $smarty->assign('rsProducts', $rsProducts);
  $smarty->assign('pageTitle', 'Управление сайтом');

  loadTemplate($smarty, 'adminHeader');
  loadTemplate($smarty, 'adminProducts');
  loadTemplate($smarty, 'adminFooter');
}

/**
* функция добавления продукта
*/
function addproductAction()
{
  $itemName = $_POST['itemName'];
  $itemPrice = $_POST['itemPrice'];
  $itemDesc = $_POST['itemDesc'];
  $itemCat = $_POST['itemCatId'];

  $res = insertProduct($itemName, $itemPrice, $itemDesc, $itemCat);
//d($res);
  if ($res)
  {
    $resData['success'] = 1;
    $resData['message'] = 'Товар успешно добавлен';
  }
  else {
    $resData['success'] = 0;
    $resData['message'] = 'Ошибка добавления товара';
  }

  echo json_encode($resData);
  return;
}

/**
*
* Функция обновления данных продукта
*/
function updateproductAction()
{
  $itemId = $_POST['itemId'];
  $itemName = $_POST['itemName'];
  $itemPrice = $_POST['itemPrice'];
  $itemStatus = $_POST['itemStatus'];
  $itemDesc = $_POST['itemDesc'];
  $itemCat = $_POST['itemCatId'];

  $res = updateProduct($itemId, $itemName, $itemPrice, $itemStatus,
                        $itemDesc, $itemCat);

  if ($res)
  {
    $resData['success'] = 1;
    $resData['message'] = 'Изменения успешно внесены';
  }
  else {
    $resData['success'] = 0;
    $resData['message'] = 'Ошибка изменения данных';
  }

  echo json_encode($resData);
  return;
}

/**
* Загрузка изображений
*
*/
function uploadAction()
{
  $maxSize = 2 * 1024 * 1024;

  $itemId = $_POST['itemId'];
  // получаем расширение загружаемого файла // , PATHINFO_EXTENSION
  $ext = pathinfo($_FILES['filename']['name']);
  //d($ext['extension']);
  // создаем имя файла
  $newFileName = $itemId . "." . $ext['extension'];

  if($_FILES['filename']['size'] > $maxSize)
  {
    echo 'Размер файла превышает два мегабайта';
    return;
  }

  // загружен ли файл
  if(is_uploaded_file($_FILES['filename']['tmp_name']))
  {
    // если файл загружен, то перемещаем его из временной директории в конечную
    $dest = $_SERVER['DOCUMENT_ROOT'] . '/images/products/' . $newFileName;
//d($dest);
    $res = move_uploaded_file ($_FILES['filename']['tmp_name'], $dest);
//d($res);
    if ($res)
    {
      $res = updateProductImage($itemId, $newFileName);
      if ($res)
      {
        redirect('/admin/products/');
      }
    }
  }
  else {
    echo 'Ошибка загрузки файла';
  }
}

/**
* Страница выборки всех заказов
*/
function ordersAction($smarty)
{
  $rsOrders = getOrders();
//d($rsOrders);
  $smarty->assign('rsOrders', $rsOrders);
  $smarty->assign('pageTitle', 'Заказы');

  loadTemplate($smarty, 'adminHeader');
  loadTemplate($smarty, 'adminOrders');
  loadTemplate($smarty, 'adminFooter');
}

/**
* Страница выборки всех заказов
*/
function salesAction($smarty)
{
  $rsSales = null;
  //$rsSales = getSales();
//d($rsOrders);
  $smarty->assign('rsSales', $rsSales);
  $smarty->assign('pageTitle', 'Продажи');

  loadTemplate($smarty, 'adminHeader');
  loadTemplate($smarty, 'adminSales');
  loadTemplate($smarty, 'adminFooter');
}

/**
* функция обновления статуса заказа
*/
function setorderstatusAction()
{
  $itemId = $_POST['itemId'];
  $status = $_POST['status'];

  $res = updateOrderStatus($itemId, $status);

  if($res)
  {
    $resData['success'] = 1;
  }
  else {
    $resData['success'] = 0;
    $resData['message'] = 'Ошибка установки статуса';
  }

  echo json_encode($resData);
  return;
}

/**
* функция установки даты оплаты заказа
*/
function setorderdatepaymentAction()
{
  $itemId = $_POST['itemId'];
  $datePayment = $_POST['datePayment'];

  $res = updateOrderDatePayment($itemId, $datePayment);

  if ($res)
  {
    $resData['success'] = 1;
  }
  else
  {
    $resData['success'] = 0;
    $resData['message'] = 'Ошибка записи даты оплаты';
  }

  echo json_encode($resData);
  return;
}
