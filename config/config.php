<?
/**
*
* Файл настроек
*/

// Константы для обращения к контроллерам
define('PathPrefix', "../controllers/");
define('PathPostfix', "Controller.php");

// Используемый шаблон

if($controllerName == "Admin")
{
  $template = 'admin';
}
else{
  $template = 'default';
  //$template = 'texturia';
}
/*
$template = 'default';
$templateAdmin = 'admin';
*/
define('TemplatePrefix' , '../views/' . $template . '/');
//define('TemplateAdminPrefix' , "../views/" . $templateAdmin . "/" );
// Пути к файлам шаблонов .tpl
define('TemplatePostfix', '.tpl');

// Пути к файлам шаблонов в вебпространстве
define('TemplateWebPath', '/templates/' . $template . '/');
//define('TemplateAdminWebPath', "/templates/$templateAdmin/");


// Инициализация шаблонов Smarty
// put full path to Smarty.class.php

require("../library/Smarty/libs/Smarty.class.php");

$smarty = new Smarty;

$smarty->template_dir = TemplatePrefix;
$smarty->compile_dir = '../tmp/smarty/templates_c';
$smarty->cache_dir = '../tmp/smarty/cache';
//$smarty->config_dir = '../library/smarty/configs';

$smarty->assign('templateWebPath', TemplateWebPath);
