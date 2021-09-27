<?
session_start();
if(! isset($_SESSION['cart']))
{
  $_SESSION['cart'] = array();
}

$controllerName = isset ($_GET['controller']) ? ucfirst ($_GET['controller']) : 'Index';

include_once '../config/config.php';           // Инициализация настроек
include_once '../config/db.php';               // Инициализация БД
include_once '../library/mainFunctions.php';   // Основные функции

// Определяем с каким контроллером будем работать

// Определяем с какой функцией будем работать
$actionName = isset ($_GET['action']) ? $_GET['action'] : 'index';

// если в сессии есть данные об авторизированном пользователе, то передаем
// их в шаблон
if(isset($_SESSION['user']))
{
  $smarty->assign('arUser', $_SESSION['user']);
}

// инициализируем переменную шаблонизатора количества эл-тов в корзине
$smarty->assign('cartCntItems', count($_SESSION['cart']));
loadPage($smarty, $controllerName, $actionName);
