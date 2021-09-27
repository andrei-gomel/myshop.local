<?

/**
* Контроллер функций пользователя
*
*/

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/UsersModel.php';
include_once '../models/OrdersModel.php';
include_once '../models/PurchaseModel.php';

function checkemailAction()
{
  $email = isset($_POST['email']) ? $_POST['email'] : null;
  $email = trim($email);

  $result = checkUserEmail($email);

  if($result > 0)
  {

    $resData['success'] = false;
    $resData['message'] = "<img src='/images/icons--x-mark.jpg' height=20 width=20>";
    //$resData['message'] = "<font color='red'>email занят</font>";
    //echo "<img src='/images/icons--x-mark.jpg'>";die;
  }
  else
  {
    $resData['success'] = true;
    $resData['message'] = "<img src='/images/checkmark_icon.png' height=20 width=20>";
    //$resData['message'] = "<font color='green'>email свободен</font>";
    //echo "<img src='/images/checkmark_icon.png'>";die;
  }
  echo json_encode($resData);
}

/**
* AJAX регистрация пользователя
* Инициализация сессионной переменной ($_SESSION['user'])
*
* @return json массив данных нового пользователя
*/
function registerAction()
{
  $email = isset($_POST['email']) ? $_POST['email'] : null;
  $email = trim($email);

  $pwd1 = isset($_POST['pwd1']) ? $_POST['pwd1'] : null;
  $pwd2 = isset($_POST['pwd2']) ? $_POST['pwd2'] : null;

  $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
  $adress = isset($_POST['adress']) ? $_POST['adress'] : null;
  $name = isset($_POST['name']) ? $_POST['name'] : null;
  $name = trim($name);

  $resData = null;
  $resData = checkRegisterParams($email, $pwd1, $pwd2);

  if(! $resData && checkUserEmail($email))
  {
    $resData['success'] = false;
    //$resData['message'] = "email занят";
    $resData['message'] = "Пользователь с таким email ({$email}) уже зарегистрирован";
  }

  if(! $resData)
  {
    $pwdMD5 = md5($pwd1);
    $userData = registerNewUser($email, $pwdMD5, $name, $phone, $adress);

    if($userData['success'])
    {
      $resData['message'] = 'Пользователь успешно зарегистрирован';
      $resData['success'] = 1;

      $userData = $userData[0];
      $resData['userName'] = $userData['name'] ? $userData['name'] : $userData['email'];
      $resData['userEmail'] = $email;

      $_SESSION['user'] = $userData;
      $_SESSION['user']['displayName'] = $userData['name'] ? $userData['name'] : $userData['email'];
    }
    else {
      $resData['success'] = 0;
      $resData['message'] = 'Ошибка регистрации';
    }
  }

  echo json_encode($resData);
}

/**
* Разлогирование пользователя
*
*/

function logoutAction()
{
  if(isset($_SESSION['user']))
  {
    unset($_SESSION['user']);
    unset($_SESSION['cart']);
  }

  redirect('/');
}

/**
* AJAX Авторизация пользователя
*
* @return json массив данных пользователя
*/

function loginAction()
{
  $email = isset($_POST['email']) ? ($_POST['email']) : null;
  $email = trim($email);

  $pwd = isset($_POST['pwd']) ? ($_POST['pwd']) : null;
  $pwd = trim($pwd);

  $userData = loginUser($email, $pwd);

  if($userData['success'])
  {
    $userData = $userData[0];

    $_SESSION['user'] = $userData;
    $_SESSION['user']['displayName'] = $userData['name'] ? $userData['name'] : $userData['email'];

    $resData = $_SESSION['user'];
    $resData['success'] = 1;
    //d($_SESSION['user']['id']);
  }
  else {
    $resData['success'] = 0;
    $resData['message'] = 'Неверный логин или пароль';
  }

  echo json_encode($resData);
}

/**
* Формирование главной страницы пользователя
*
* @link /user/
* @param object $smarty шаблонизатор
*/
function indexAction($smarty)
{
  // если пользователь не залогинен то редирект на главную страницу
  if(!isset($_SESSION['user']))
  {
    redirect('/');
  }

  // получаем список категорий для Меню
  $rsCategories = getAllMainCatsWithChildren();

  // получение списка заказов пользователя
  $rsUserOrders = getCurUserOrders();
  //d($rsUserOrders);
  $smarty->assign('pageTitle', 'Кабинет пользователя');
  $smarty->assign('rsCategories', $rsCategories);
  $smarty->assign('rsUserOrders', $rsUserOrders);

  loadTemplate($smarty, 'header');
  loadTemplate($smarty, 'user');
  loadTemplate($smarty, 'footer');
}

/**
* Обновление данных пользователя
*
* @return json результат выполнения функции
*/
function updateAction()
{
  // если пользователь не залогинен то выходим
  if(!isset($_SESSION['user']))
  {
    redirect('/');
  }

  // Инициализация переменных для перехвата передаваемых данных из main.js
  $resData = array();
  $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
  $adress = isset($_POST['adress']) ? $_POST['adress'] : null;
  $name = isset($_POST['name']) ? $_POST['name'] : null;
  $pwd1 = isset($_POST['pwd1']) ? $_POST['pwd1'] : null;
  $pwd2 = isset($_POST['pwd2']) ? $_POST['pwd2'] : null;
  $curPwd = isset($_POST['curPwd']) ? $_POST['curPwd'] : null;

  // проверка правильности пароля(введенный и тот, под которым залогинились)
  $curPwdMD5 = md5($curPwd);
  if(! $curPwd || ($_SESSION['user']['pwd'] != $curPwdMD5))
  {
    $resData['success'] = 0;
    $resData['message'] = 'Текущий пароль не верный!';
    echo json_encode($resData);
    return false;
  }

  // вызываем функцию updateUserData в UsersModel для обновления данных

  $res = updateUserData($name, $phone, $adress, $pwd1, $pwd2, $curPwdMD5);
  //d($res);

  if($res)
  {
    $resData['success'] = 1;
    $resData['message'] = 'Данные сохранены';
    $resData['userName'] = $name;

    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['adress'] = $adress;

    $newPwd = $_SESSION['user']['pwd'];
    if($pwd1 && ($pwd1 == $pwd2))
    {
      $newPwd = md5(trim($pwd1));
    }

    $_SESSION['user']['pwd'] = $newPwd;
    $_SESSION['user']['displayName'] = $name ? $name : $_SESSION['user']['email'];
  }
  else {

    $resData['success'] = 0;
    $resData['message'] = 'Ошибка сохранения данных';
  }
  echo json_encode($resData);

}
