<?php
require_once './constants.php';

/**
 * Валидирует данные полей, в соответствии с правилами валидации
 * @param array $inputs массив вида ключ-значение. 
 * Ключ - идентификатор поля; значение - введенные пользователем данные
 * @param array $rules массив содержащий функции, описывающие правила валидации
 * @return array $validationErrors массив вида ключ-значение.
 * Ключ - идентификатор поля; значение - массив, содержащий описания несоответствий
 */
function validateInputs(array $inputs, array $rules): array
{
  $validationErrors = [];

  foreach ($inputs as $key => $value) {
    if (isset($rules[$key])) {

      foreach ($rules[$key] as $rule) {
        $validationResult = $rule($value);
        if ($validationResult) {
          if (!isset($validationErrors[$key])) {
            $validationErrors[$key] = [];
          }

          array_push($validationErrors[$key], $validationResult);
        }
      }
    }
  }

  return $validationErrors;
}

function validateIsNotEmpty(string $name)
{
  if (empty($name)) {
    return 'Поле обязательно для заполнения';
  }

  return null;
}

function validateInputLength(string $name)
{
  if (strlen($name) > PROJECT_NAME_MAX_LENGTH) {
    return "Поле должно содержать не более " . PROJECT_NAME_MAX_LENGTH . " символов";
  }

  return null;
}

function validateIsProjectExist(string $name, array $projects)
{
  $projectNames = [];

  foreach ($projects as $value) {
    $upperCaseNameValue = mb_strtoupper($value['name']);
    array_push($projectNames, $upperCaseNameValue);
  }

  $upperCaseNewNameValue = mb_strtoupper($name);

  if (in_array($upperCaseNewNameValue, $projectNames)) {
    return 'Проект с таким именем уже существует';
  };

  return null;
}

function validateIsProjectMemberOfSet(string $id, array $projects)
{
  $projectIds = [];

  foreach ($projects as $value) {
    array_push($projectIds, $value['id']);
  }

  if (!in_array($id, $projectIds)) {
    return 'Проект с таким именем не существует';
  };

  return null;
}

function validateIsDateFormatValid(string $date)
{
  if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
    return 'Неверный формат даты';
  }

  return null;
}
