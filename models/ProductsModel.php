<?

/**
* Модель для таблицы продукции (products)
*
*/

/**
* Получаем последние добавленные товары
*
* @param integer $limit Лимит товаров
* @return array Массив товаров
*/

function getLastProducts($limit = null)
{
  $sql = "SELECT * FROM products ORDER BY id DESC";
  if($limit)
  {
    $sql .= " LIMIT {$limit}";
  }

  $rs = mysqli_query($_SESSION['db'], $sql);
  //d($rs);
  return createSmartyRsArray($rs);
}

/**
* Получить продукты для категории $item
*
* @param integer $itemId ID категории
* @return array массив продуктов
*/

function getProductsByCat($itemId)
{
  $itemId = intval($itemId);
  $sql = "SELECT * FROM products WHERE category_id = '{$itemId}'";

  $rs = mysqli_query($_SESSION['db'], $sql);

  return createSmartyRsArray($rs);

}

/**
* Получить данные продукта по ID
*
* @param integer $itemId ID продукта
* @return array массив данных продукта
*/

function getProductById($itemId)
{
  $itemId = intval($itemId);
  $sql = "SELECT * FROM products WHERE id = '{$itemId}'";

  $rs = mysqli_query($_SESSION['db'], $sql);
  return mysqli_fetch_assoc($rs);
}

/**
* Получить список продуктов из массива идентификаторов (IDs)
*
* @param array $itemsIds массив идентификаторов продуктов
* @return array массив данных продуктов
*/

function getProductsFromArray($itemsIds)
{
  //d($itemsIds);
  $strIDs = implode($itemsIds, ', ');
  //d($strIDs);
  $sql = "SELECT * FROM products WHERE id in ({$strIDs})";
  //d($sql);
  $rs = mysqli_query($_SESSION['db'], $sql);

  return createSmartyRsArray($rs);
}

/**
* функция получения всех продуктов
*
*/
function getProducts()
{
  $sql = "SELECT * FROM `products` ORDER BY category_id";

  $rs = mysqli_query($_SESSION['db'], $sql);

  return createSmartyRsArray($rs);
}

/**
* функция сохранения нового продукта в БД
*
* @param string $itemName Название продукта
* @param integer $itemPrice цена
* @param string $itemDesc Описание
* @param integer $itemCat ID категории
* return type
*/
function insertProduct($itemName, $itemPrice, $itemDesc, $itemCat)
{
  $sql = "INSERT INTO products
          SET `name` = '{$itemName}',
              `price` = '{$itemPrice}',
              `description` = '{$itemDesc}',
              `category_id` = '{$itemCat}',
              `image` = ''";
//d($sql);
  $rs = mysqli_query($_SESSION['db'], $sql);

  return $rs;
}

/**
*
* Функция обновления данных товара
*/
function updateProduct($itemId, $itemName, $itemPrice, $itemStatus, $itemDesc,
                        $itemCat, $newFileName = null)
{
  $set = array();

  if ($itemName)
  {
    $set[] = "`name` = '{$itemName}'";
  }

  if ($itemPrice > 0)
  {
    $set[] = "`price` = '{$itemPrice}'";
  }

  if ($itemStatus !== null)
  {
    $set[] = "`status` = '{$itemStatus}'";
  }

  if ($itemDesc)
  {
    $set[] = "`description` = '{$itemDesc}'";
  }

  if ($itemCat)
  {
    $set[] = "`category_id` = '{$itemCat}'";
  }

  if ($newFileName)
  {
    $set[] = "`image` = '{$newFileName}'";
  }

  $setStr = implode($set, ", ");

  $sql = "UPDATE products SET {$setStr} WHERE id = '{$itemId}'";

  $rs = mysqli_query($_SESSION['db'], $sql);

  return $rs;
}

/**
* Обновление картинки продукта
*
*/
function updateProductImage($itemId, $newFileName)
{
  $rs = updateProduct($itemId, null, null, null, null, null, $newFileName);

  return $rs;
}
