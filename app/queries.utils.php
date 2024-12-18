<?php
function getTasks(mysqli $link, string $userId, string $projectId = null, $showCompleted = 0)
{
  $queryTasks = '';

  if($projectId != null) {
    $queryTasks = "SELECT tasks.*, projects.name as project_name FROM `tasks`
    JOIN projects ON tasks.project_id = projects.id
    WHERE tasks.author_id=$userId AND tasks.project_id = $projectId" . " ORDER BY tasks.date_expiration ASC;";
  }
  else {
    $queryTasks = "SELECT tasks.*, projects.name as project_name FROM `tasks`
    JOIN projects ON tasks.project_id = projects.id
    WHERE tasks.author_id=$userId" . ($showCompleted ? "" : " AND is_complete = 0") . " ORDER BY tasks.date_expiration ASC;";
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
