<?php

/**
 * Функция запроса задач из БД
 * @param mysqli $link mysqli соединение
 * @param string $userId идентификатор пользователя
 * @param string $projectId идентификатор проекта
 * @param string $showCompleted показывать выполненные
 * @param string $taskFilterValue значение параметра фильтра по времени
 * @return mysqli_result|bool
 */
function getTasks(mysqli $link, string $userId, string $projectId = null, string $showCompleted, string $taskFilterValue = TASK_FILTER_VALUES['all'])
{
  $queryTasks = '';
  $queryDateFilter = '';
  $queryAlsoShowIsComplete = $showCompleted ? "" : " AND is_complete = 0";

  switch ($taskFilterValue) {
    case TASK_FILTER_VALUES['today']:
      $queryDateFilter = " AND DATE(tasks.date_expiration) = CURRENT_DATE()";
      break;
    case TASK_FILTER_VALUES['tomorrow']:
      $queryDateFilter = " AND DATE(tasks.date_expiration) = CURRENT_DATE() + INTERVAL 1 DAY";
      break;
    case TASK_FILTER_VALUES['overdue']:
      $queryDateFilter = " AND DATE(tasks.date_expiration) < CURRENT_DATE() AND tasks.is_complete = 0";
      break;
    default:
      $queryDateFilter = "";
  }

  if ($projectId != null) {
    $queryTasks = "SELECT tasks.*, projects.name as project_name FROM `tasks`
    JOIN projects ON tasks.project_id = projects.id
    WHERE tasks.author_id=$userId AND tasks.project_id = $projectId" . $queryAlsoShowIsComplete  . $queryDateFilter . " ORDER BY tasks.date_expiration DESC;";
  } else {
    $queryTasks = "SELECT tasks.*, projects.name as project_name FROM `tasks`
    JOIN projects ON tasks.project_id = projects.id
    WHERE tasks.author_id=$userId" . $queryAlsoShowIsComplete . $queryDateFilter . " ORDER BY tasks.date_expiration DESC;";
  }

  try {
    $tasks = mysqli_query($link, $queryTasks);
  } catch (Throwable $throwable) {
    redirectToErrorPage500();
  }

  return $tasks;
}

function getProjects(mysqli $link, string $userId)
{
  $queryProjects = "SELECT COUNT(tasks.project_id) project_count, projects.name, projects.id FROM projects
                  LEFT JOIN tasks ON projects.id = tasks.project_id
                  WHERE projects.author_id = $userId
                  GROUP BY projects.id;";

  try {
    $projects = mysqli_query($link, $queryProjects);
  } catch (Throwable $throwable) {
    redirectToErrorPage500();
  }

  return $projects;
}

function setIsTaskComplete(mysqli $link, string $taskId, string $isCompleteNewValue)
{
  $query = "UPDATE tasks
            SET tasks.is_complete = $isCompleteNewValue 
            WHERE tasks.id = $taskId;";

  try {
    mysqli_query($link, $query);
  } catch (Throwable $throwable) {
    redirectToErrorPage500();
  }
}

/**
 * Добавляет новый проект в БД
 * @param mysqli $link mysqli соединение
 * @param string $projectName название проекта
 * @param string $authorId идентификатор пользователя, создающего проект
 */
function addProject(mysqli $link, string $projectName, string $authorId)
{
  $query = "INSERT INTO projects
            SET name = '$projectName', author_id = $authorId";

  try {
    $res = mysqli_query($link, $query);
  } catch (Throwable $throwable) {
    redirectToErrorPage500();
  }
}

/**
 * Добавляет новую задачу в БД
 * @param mysqli $link mysqli соединение
 * @param string $taskName название задачи
 * @param string $expirationDate срок выполнения задачи
 * @param string $authorId идентификатор пользователя, создающего задачу
 * @param string $projectId идентификатор проекта, к которому относится задача
 */
function addTask(mysqli $link, string $taskName, string $expirationDate, string $authorId, string $projectId)
{
  $query = "INSERT INTO tasks (is_complete, name, date_expiration, author_id, project_id)
            VALUES
            (false, '$taskName', '$expirationDate', '$authorId', '$projectId');";

  try {
    $res = mysqli_query($link, $query);
  } catch (Throwable $throwable) {
    redirectToErrorPage500();
  }
}

function updateTaskFilePath(mysqli $link, string $taskId, string $filePath)
{
  $query = "UPDATE tasks
            SET tasks.file_path = '$filePath' WHERE id = $taskId;";

  try {
    $res = mysqli_query($link, $query);
  } catch (Throwable $throwable) {
    redirectToErrorPage500();
  }
}
