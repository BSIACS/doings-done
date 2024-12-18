<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'queries.utils.php';

$SECONDS_PER_DAY = 86400;

$showCompleteTasks = 0;
$filteredProject = null;
$filteredTaskGroup = null;
$projectCount = 0;


if(isset($_GET['show_completed']) && $_GET['show_completed'] == 1) {
  $showCompleteTasks = 1;
}
else {
  $showCompleteTasks = 0;
}

if(isset($_GET['filter_by_project_id'])) {
  $filterByProjectId = $_GET['filter_by_project_id'];
}
else {
  $filterByProjectId = null;
}

if(isset($_GET['filter_by_task_group'])) {
  $filterByTaskGroup = $_GET['filter_by_task_group'];
}
else {
  $filterByTaskGroup = 'all';
}

// Тестовый вывод строки запроса
// print_r('showCompleteTasks = ' . $showCompleteTasks . ', ' . 'filterByProjectId = ' . $filterByProjectId . ', ' . 'filterByTaskGroup = ' . $filterByTaskGroup);

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
  $resultQueryTasks = getTasks($link, 2, $filterByProjectId, $showCompleteTasks);

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
    "SECONDS_PER_DAY" => $SECONDS_PER_DAY,
  ]);
}

$layout_content = include_template("layout.php", [
  "content" => $page_content,
  "title" => "Главная",
]);

print($layout_content);
