<?php

require_once 'helpers.php';
require_once 'db-init.php';
require_once 'queries.utils.php';
require_once 'validation.utils.php';

$projectCount = 0;
$validationErrors = [];


if (!$link) {
  redirectToErrorPage500();
} else {
  // ЗАПРОС ПРОЕКТОВ
  $resultQueryProjects = getProjects($link, 2);

  if ($resultQueryProjects) {
    $projects = mysqli_fetch_all($resultQueryProjects, MYSQLI_ASSOC);
  } else {
    redirectToErrorPage500();
  }

  // ОБРАБОТКА ДАННЫХ ОТПРАВЛЕННОЙ ФОРМЫ
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $args = array(
      'name' => FILTER_DEFAULT,
      'project' => FILTER_DEFAULT,
      'date' => FILTER_DEFAULT,
    );

    // ПРАВИЛА ВАЛИДАЦИИ
    $rules = [
      'name' => [
        function ($value) {
          return validateIsNotEmpty($value);
        },
      ],
      'project' => [
        function ($value) use ($projects) {
          return validateIsProjectMemberOfSet($value, $projects);
        },
      ],
      'date' => [
        function ($value) {
          return validateIsNotEmpty($value);
        },
        function ($value) {
          return validateIsDateFormatValid($value);
        }
      ],
    ];

    $inputs = filter_input_array(INPUT_POST, $args);

    $validationErrors = [];
    validateInputs($inputs, $rules, $validationErrors);  
    validateFileInput('file', $validationErrors);

    if (count($validationErrors) <= 0) {
      addTask($link, $inputs['name'], $inputs['date'], 2, $inputs['project']);
      if ($_FILES && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $createdTaskId = mysqli_insert_id($link);
        mkdirIfNotExist("uploads/2/$createdTaskId");
        updateTaskFilePath($link, $createdTaskId, "uploads/2/$createdTaskId/" . $_FILES["file"]['name']);
        move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/2/$createdTaskId/" . $_FILES["file"]['name']);
      }
      redirectToIndexPage();
    }
  }

  $page_content = include_template("add-task.template.php", [
    "projects" => $projects,
    "projectCount" => $projectCount,
    "validationErrors" => $validationErrors,
  ]);
}

$layout_content = include_template("layout.php", [
  "content" => $page_content,
  "title" => "Добавление задачи",
]);

print($layout_content);
