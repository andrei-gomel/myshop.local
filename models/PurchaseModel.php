<?

/**
* Модель для таблицы продукции (purchase)
*
*/

/**
* Внесение в БД данных продуктов с привязкой к заказу
*
* @param integer $orderId ID заказа
* @param array $cart массив корзины
* @return boolean true в случае успешного добавления в БД
*/
function setPurchaseForOrder($orderId, $cart)
{
  $sql = "INSERT INTO purchase (`order_id`, `product_id`, `price`, `amount`)
          VALUES ";

  $values = array();
  // формируем массив строк для запроса для каждого товара
  foreach($cart as $item)
  {
    $values[] = "('{$orderId}', '{$item['id']}', '{$item['price']}', '{$item['cnt']}')";
  }

  // преобразовываем массив в строку
  $sql .= implode($values, ', ');
  //d($sql,0);
  $rs = mysqli_query($_SESSION['db'], $sql);

  return $rs;
}

/**
*
* Получаем продукты к заказу номер ID
*/
function getPurchaseForOrder($orderId)
{
  $sql = "SELECT `pe`.*, `ps`.`name`
          FROM purchase as `pe`
          JOIN products as `ps` ON `pe`.product_id = `ps`.id
          WHERE `pe`.order_id = {$orderId}";
  //d($sql);
  $rs = mysqli_query($_SESSION['db'], $sql);
  return createSmartyRsArray($rs);
}
