<?

/**
* Модель для таблицы заказов (orders)
*
*/

/**
* Создание заказа (без привязки товара)
*
* @param string $name
* @param string $phone
* @param string $adress
* @return integer ID созданного заказа
*/
function makeNewOrder($name, $phone, $adress)
{
  // инициализация переменных
  $userId = $_SESSION['user']['id'];
  $comment = "id пользователя: {$userId}<br>
  Имя: {$name}<br>
  Тел: {$phone}<br>
  Адрес: {$adress}";

  $dateCreated = date('Y.m.d H:i:s');
  $userIp = $_SERVER['REMOTE_ADDR'];

  // формирование запроса к БД
  $sql = "INSERT INTO orders (`user_id`, `date_created`, `date_payment`,
                              `status`, `comment`, `user_ip`)
          VALUES ('{$userId}', '{$dateCreated}', null, '0', '{$comment}', '{$userIp}')";

  $rs = mysqli_query($_SESSION['db'], $sql);

  // получить ID созданного заказа
  if ($rs)
  {
    $sql = "SELECT id FROM orders ORDER BY id DESC LIMIT 1";

    $rs = mysqli_query($_SESSION['db'], $sql);

    // преобразование результатов запроса
    $rs = createSmartyRsArray($rs);

    // возвращаем ID созданного запроса
    if(isset($rs[0]))
    {
      return $rs[0]['id'];
    }
  }

  return false;
}

/**
* Получить список заказов с привязкой к продуктам для пользователя $userId
*
* @param integer $userId ID пользователя
* @return array массив заказов с привязкой к продуктам
*/
function getOrdersWithProductsByUser($userId)
{
  $userId = intval($userId);
  $sql = "SELECT * FROM orders WHERE `user_id` = '{$userId}' ORDER BY id DESC";

  $rs = mysqli_query($_SESSION['db'], $sql);

  $smartyRs = array();
  while ($row = mysqli_fetch_assoc($rs))
  {
    // получаем по номеру ID заказа
    $rsChildren = getPurchaseForOrder($row['id']);

    if ($rsChildren)
    {
      $row['children'] = $rsChildren;
      $smartyRs[] = $row;
    }
  }

  return $smartyRs;
}

/**
* Функция выборки всех заказов для админки
*/
function getOrders()
{
  $sql = "SELECT o.*, u.name, u.email, u.phone, u.adress
          FROM orders AS `o`
          LEFT JOIN users AS `u` ON o.user_id = u.id
          ORDER BY id DESC";
//d($sql);
  $rs = mysqli_query($_SESSION['db'], $sql);

  $smartyRs = array();

  while ($row = mysqli_fetch_assoc($rs))
  {
    $rsChildren = getProductsForOrder($row['id']);

    if($rsChildren)
    {
      $row['children'] = $rsChildren;
      $smartyRs[] = $row;
    }
  }

  return $smartyRs;
}

/**
* Получить продукты заказа
*
* @param integer $orderId ID заказа
* @return array массив данных товаров
*/
function getProductsForOrder($orderId)
{
  $sql = "SELECT * FROM purchase AS pe
          LEFT JOIN products AS ps
          ON pe.product_id = ps.id
          WHERE (`order_id` = '{$orderId}')";

  $rs = mysqli_query($_SESSION['db'], $sql);

  return createSmartyRsArray($rs);
}

/**
* Функция обновления статуса заказа
*/
function updateOrderStatus($itemId, $status)
{
  $status = intval($status);
  $sql = "UPDATE orders SET `status` = '{$status}' WHERE id = '{$itemId}'";
//d($sql);
  $rs = mysqli_query($_SESSION['db'], $sql);
  return $rs;
}

/**
* Функция обновления даты заказа
*/
function updateOrderDatePayment($itemId, $datePayment)
{
  $sql = "UPDATE orders SET `date_payment` = '{$datePayment}' WHERE id = '{$itemId}'";

  $rs = mysqli_query($_SESSION['db'], $sql);
  return $rs;
}
