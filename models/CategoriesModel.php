<?

/**
* Модель для таблицы (categories)
*
*/

/**
* Получить дочерние категории для категории $catId
*
* @param integer $catId ID категории
* @return array массив дочерних категорий
*/

function getChildrenForCat($catId)
{
  $sql = "SELECT * FROM categories WHERE parent_id = '{$catId}'";

  $rs = mysqli_query($_SESSION['db'], $sql);

  return createSmartyRsArray($rs);

}

/**
* Получить главные категории с привязками дочерних
*
* @return array массив категорий
*/

function getAllMainCatsWithChildren()
{
  $sql = "SELECT * FROM categories WHERE parent_id = 0";

  $rs = mysqli_query($_SESSION['db'], $sql);

  $smartyRs = array();

  while($row = mysqli_fetch_assoc($rs))
  {
    $rsChildren = getChildrenForCat($row['id']);
//d($rsChildren);
    if($rsChildren)
    {
      $row['children'] = $rsChildren;
    }
    //echo "id = ".$row['id']." => ".$row['name']."<br>";
    $smartyRs[] = $row;
  }
//  d($smartyRs);
  return $smartyRs;
}

/**
* Получить данные категории по id
*
* @param integer $catId ID категории
* @return array массив - строка категории
*/

function getCatById($catId)
{
  $catId = intval($catId);
  $sql = "SELECT * FROM categories WHERE id = '{$catId}'";
  $rs = mysqli_query($_SESSION['db'], $sql);
  return mysqli_fetch_assoc($rs);
}

/**
* Получить все главные категории (категории кот. не являютмя дочерними)
*
* @return array массив категорий
*/
function getAllMainCategories()
{
  $sql = "SELECT * FROM categories WHERE parent_id = 0";

  $rs = mysqli_query($_SESSION['db'], $sql);

  return createSmartyRsArray($rs);
}

/**
*
* Добавление новой категории
*
* @param string $catName название категории
* @param integer $catParentId ID родительской категории
* $return integer id новой категории
*/
function insertCat($catName, $catParentId=0)
{
  $sql = "INSERT INTO categories (`parent_id`, `name`)
          VALUES ('{$catParentId}', '{$catName}')";

  mysqli_query($_SESSION['db'], $sql);

  $id = mysqli_insert_id($_SESSION['db']);

  return $id;
}

/**
*
* Получить все категории
*
* @return array массив категорий
*/
function getAllCategories()
{
  $sql = "SELECT * FROM categories ORDER BY parent_id ASC";

  $rs = mysqli_query($_SESSION['db'], $sql);

  return createSmartyRsArray($rs);
}

/**
* Обновление категории
*
* @param integer $itemId ID категории
* @param integer $parentId ID главной категории
* @param string $newName новое имя категории
* @return type
*/
function updateCategoryData($itemId, $parentId = -1, $newName='')
{
  $set = array();

  if($newName)
  {
    $set[] = "`name` = '{$newName}'";
  }

  if($parentId > -1)
  {
    $set[] = "`parent_id` = '{$parentId}'";
  }

  $setStr = implode($set, ", ");
  $sql = "UPDATE categories SET {$setStr} WHERE id = '{$itemId}'";

  $rs = mysqli_query($_SESSION['db'], $sql);

  return $rs;
}
