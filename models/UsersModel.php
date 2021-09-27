<?

/**
* Модель для работы с таблицей пользователей (users)
*
*/

/**
* Регистрация нового пользователя
*
* @param string $email почта
* @param string $pwdMD5 пароль зашифрованный в md5
* @param string $name имя пользователя
* @param string $phone телефон
* @param string $adress адрес пользователя
* @param array массив данных нового пользователя
*/

function registerNewUser($email, $pwdMD5, $name, $phone, $adress)
{
  $email = htmlspecialchars(mysqli_real_escape_string($_SESSION['db'], $email));
  $name = htmlspecialchars(mysqli_real_escape_string($_SESSION['db'], $name));
  $phone = htmlspecialchars(mysqli_real_escape_string($_SESSION['db'], $phone));
  $adress = htmlspecialchars(mysqli_real_escape_string($_SESSION['db'], $adress));

  $sql = "INSERT INTO users (`email`, `pwd`, `name`, `phone`, `adress`)
          VALUES ('{$email}', '{$pwdMD5}', '{$name}', '{$phone}', '{$adress}')";

  $rs = mysqli_query($_SESSION['db'],$sql);

  if($rs)
  {
    $sql = "SELECT * FROM users WHERE (`email` = '{$email}' AND `pwd` = '{$pwdMD5}') LIMIT 1";

    $rs = mysqli_query($_SESSION['db'],$sql);
    //d($rs);
    $rs = createSmartyRsArray($rs);

    if(isset($rs[0]))
    {
      $rs['success'] = 1;
    }
    else {
      $rs['success'] = 0;
    }
  }
  else {
    $rs['success'] = 0;
  }
//d($rs);
  return $rs;
}

/**
* Проверка параметров для регистрации пользователя
*
* @param string $email email
* @param string $pwd1 пароль
* @param string $pwd2 повтор пароля
* @return array результат
*/
function checkRegisterParams($email, $pwd1, $pwd2)
{
  $res = null;

  if(! $email)
  {
    $res['success'] = false;
    $res['message'] = 'Введите email';
  }

  if(! $pwd1)
  {
    $res['success'] = false;
    $res['message'] = 'Введите пароль';
  }

  if(! $pwd2)
  {
    $res['success'] = false;
    $res['message'] = 'Введите повтор пароля';
  }

  if($pwd1 != $pwd2)
  {
    $res['success'] = false;
    $res['message'] = 'Пароли не совпадают';
  }

  return $res;
}

/**
* Проверка почты (есть ли email адрес в БД)
*
* @param string $email
* @return array массив - строка из таблицы users, либо пустой Массив
*/
/*function checkUserEmail($email)
{
  $email = mysqli_real_escape_string($_SESSION['db'], $email);
  $sql = "SELECT id FROM users WHERE email = '{$email}'";

  //d($sql);

  $rs = mysqli_query($_SESSION['db'], $sql);

  $rs = createSmartyRsArray($rs);

  return $rs;
}*/

function checkUserEmail($email)
{
  $email = mysqli_real_escape_string($_SESSION['db'], $email);

  $sql = "SELECT id FROM users WHERE email = '{$email}'";
  //$sql = "SELECT email FROM users";

  $res = mysqli_query($_SESSION['db'], $sql);
  $row_cnt = mysqli_num_rows($res);

//d($row_cnt);
  return $row_cnt;
}

/**
* Авторизация пользователя
*
* @param string $email почта(логин)
* @param string $pwd пароль
* @return array массив данных пользователя
*/

function loginUser($email, $pwd)
{
  $email = htmlspecialchars(mysqli_real_escape_string($_SESSION['db'], $email));
  $pwd = md5($pwd);

  $sql = "SELECT * FROM users WHERE (`email` = '{$email}' AND `pwd` = '{$pwd}') LIMIT 1";

  $rs = mysqli_query($_SESSION['db'], $sql);

  $rs = createSmartyRsArray($rs);

  if(isset($rs[0]))
  {
    $rs['success'] = 1;
  }
  else {
    $rs['success'] = 0;
  }

  return $rs;
}

/**
* Изменение данных пользователя
*
* @param string $name имя пользователя
* @param string $phone телефон
* @param string $adress адрес
* @param string $pwd1 новый пароль
* @param string $pwd2 повтор пароля
* @param string $curPwd текущий пароль
* @return boolean TRUE в случае успеха
*/
function updateUserData($name, $phone, $adress, $pwd1, $pwd2, $curPwdMD5)
{
  $email = htmlspecialchars(mysqli_real_escape_string($_SESSION['db'], $_SESSION['user']['email']));
  $name = htmlspecialchars(mysqli_real_escape_string($_SESSION['db'], $name));
  $phone = htmlspecialchars(mysqli_real_escape_string($_SESSION['db'], $phone));
  $adress = htmlspecialchars(mysqli_real_escape_string($_SESSION['db'], $adress));
  $pwd1 = trim($pwd1);
  $pwd2 = trim($pwd2);
  $id = $_SESSION['user']['id'];
  $newPwd = null;
  if($pwd1 && ($pwd1 == $pwd2))
  {
    $newPwd = md5($pwd1);
  }

  $sql = "UPDATE users SET ";

  if($newPwd)
  {
    $sql .= "`pwd` = '{$newPwd}', ";
  }

  $sql .= "`name` = '{$name}',
            `phone` = '{$phone}',
            `adress` = '{$adress}'
            WHERE `id` = '{$id}' AND `email` = '{$email}'
            LIMIT 1";
            // AND `pwd` = '{$curPwdMD5}'
//d($sql,0);
  $rs = mysqli_query($_SESSION['db'], $sql);
  //$rr = mysqli_affected_rows($_SESSION['db']);
  //d($rr);

  return($rs);
}

/**
*
* Получить данные заказа текущего пользователя
*
* @return array массив заказов с привязкой к продуктам
*/
function getCurUserOrders()
{
  $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
  $rs = getOrdersWithProductsByUser($userId);

  return $rs;
}
