<?

/**
* Инициализация подключения к БД
*/

$dblocation = "localhost";
$dbname = "myshop";
$dbuser = "root";
$dbpasswd ="";

/*
$mysqli = mysqli_init();
if (!$mysqli) {
    die('mysqli_init failed');
}

if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
    die('Setting MYSQLI_INIT_COMMAND failed');
}

if (!$mysqli->real_connect($dblocation, $dbuser, $dbpasswd, $dbname)) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}*/

// соединяемся с БД
$db = mysqli_connect($dblocation, $dbuser, $dbpasswd, $dbname);
$_SESSION['db'] = $db;
//$mysqli = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname);

/*
if (mysqli_connect_errno)
{
  printf("Ошибка соединения: %s\n", mysqli_connect_error());
}*/

//if($mysqli->connect_error)
if(!$db)
{
  echo "Ошибка доступа к MySQL";
  //printf("Ошибка соединения: %s\n", mysqli_connect_error());
  exit;
}

// Устанавливаем кодировку по умолчанию
//mysqli_query("SET NAMES utf8");

if(!mysqli_select_db($db, $dbname))
{
  echo "Ошибка доступа к базе данных: {$dbname}";
  exit;
}
