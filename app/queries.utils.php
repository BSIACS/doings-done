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
      $queryDateFilter = "";//" AND DATE(tasks.date_expiration) >= CURRENT_DATE()";
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

  $tasks = mysqli_query($link, $queryTasks);

  return $tasks;
}

function getProjects(mysqli $link, string $userId)
{
  $queryProjects = "SELECT COUNT(tasks.project_id) project_count, projects.name, projects.id FROM projects
                  LEFT JOIN tasks ON projects.id = tasks.project_id
                  WHERE projects.author_id = $userId
                  GROUP BY projects.id;";


  $projects = mysqli_query($link, $queryProjects);

  return $projects;
}

function setIsTaskComplete(mysqli $link, string $taskId, string $isCompleteNewValue)
{
  $query = "UPDATE tasks
            SET tasks.is_complete = $isCompleteNewValue 
            WHERE tasks.id = $taskId;";

  printf($query);

  mysqli_query($link, $query);
}
