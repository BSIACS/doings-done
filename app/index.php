<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'queries.utils.php';

const SECONDS_PER_DAY = 86400;
const TASK_FILTER_VALUES = [
  'all' => 'all',
  'today' => 'today',
  'tomorrow' => 'tomorrow',
  'overdue' => 'overdue',
];

$showCompleteTasks = 0;
$filteredProject = null;
$filteredTaskGroup = null;
$projectCount = 0;


// ПОИСК ПАРАМЕТРОВ ДЛЯ ИЗМЕНЕНИЯ СТАТУСА ЗАДАЧИ - 'ВЫПОЛНЕНА'
if(isset($_GET['task_id']) && isset($_GET['checked'])) {
  $taskIdToUpdate = $_GET['task_id'];
  $isTaskChecked = $_GET['checked'];
  $currentUri = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  $modifiedUri = removeQueryParams($currentUri, ["task_id", "checked"]);
  header('Location: '.$modifiedUri);
  setIsTaskComplete($link, $taskIdToUpdate, $isTaskChecked);
}

// ИНИЦИАЛИЗАЦИЯ ПЕРЕМЕННЫХ ФИЛЬТРОВ
isset($_GET['show_completed']) && $_GET['show_completed'] == 1 ? $showCompleteTasks = 1 : $showCompleteTasks = 0;

isset($_GET['filter_by_project_id']) ? $filterByProjectId = $_GET['filter_by_project_id'] : $filterByProjectId = null;

isset($_GET['filter_by_task_group']) ? $filterByTaskGroup = $_GET['filter_by_task_group'] : $filterByTaskGroup = 'all';

if (!$link) {
  $error = mysqli_connect_error();
  $content = include_template('error.php', ['error' => $error]);
} else {

  // ЗАПРОС ПРОЕКТОВ
  $resultQueryProjects = getProjects($link, 2);

  if ($resultQueryProjects) {
    $projects = mysqli_fetch_all($resultQueryProjects, MYSQLI_ASSOC);
  }
  else {
    $error = mysqli_error($link);
    $content = include_template('error.php', ['error' => $error]);
  }

  // ЗАПРОС ЗАДАЧ
  print_r($filterByTaskGroup);
  $resultQueryTasks = getTasks($link, 2, $filterByProjectId, $showCompleteTasks, $filterByTaskGroup);

  if ($resultQueryTasks) {
    $tasks = mysqli_fetch_all($resultQueryTasks, MYSQLI_ASSOC);
  }
  else {
    $error = mysqli_error($link);
    $content = include_template('error.php', ['error' => $error]);
  }

  $page_content = include_template("main.php", [
    "projects" => $projects,
    "tasks" => $tasks,
    "showCompleteTasks" => $showCompleteTasks,
    "filterByTaskGroup" => $filterByTaskGroup,
    "filterByProjectId" => $filterByProjectId,
    "projectCount" => $projectCount,
    "SECONDS_PER_DAY" => SECONDS_PER_DAY,
  ]);
}

$layout_content = include_template("layout.php", [
  "content" => $page_content,
  "title" => "Главная",
]);

print($layout_content);
