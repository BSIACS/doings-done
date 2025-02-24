<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция вычисляет разницу между сегодняшней датой и датой переданной в качестве аргумента в секундах
 * @param string $time
 * @return int количество секунд
 */
function getTimeDiffInSec(string $time)
{
    date_default_timezone_set('Europe/Moscow');
    $timestamp_1 = strtotime($time);
    $timestamp_2 = strtotime('now');

    return $timestamp_1 - $timestamp_2;
}

/**
 * Функция удаляет перечисленные параметры из строки запроса
 * @param string $uri идентификатор ресурса
 * @param array $paramNames массив содержащий параметры строки, которые требуется удалить из строки запроса
 * @return string модифицированный идентификатор ресурса
 */
function removeQueryParams(string $uri, array $paramNames)
{
    list($url, $queryParamsStr) = array_pad(explode('?', $uri), 2, '');
    parse_str($queryParamsStr, $queryParamsArray);
    for ($i = 0; $i < count($paramNames); $i++) {
        unset($queryParamsArray[$paramNames[$i]]);
    }
    $modifiedQueryParamsString = http_build_query($queryParamsArray);
    return $url . '?' . $modifiedQueryParamsString;
}

function redirectToErrorPage()
{
    header('Location: http://' . $_SERVER["HTTP_HOST"] .  '/error.php');
}

function redirectToErrorPage500()
{
    header('Location: http://' . $_SERVER["HTTP_HOST"] .  '/error-500.php');
}

function redirectToIndexPage()
{
    header('Location: http://' . $_SERVER["HTTP_HOST"] .  '/index.php');
}

/**
 * Проверяет существуют ли директории по указанному пути и если нет, то содаёт их
 * @param string $directory путь к директории вида 'cat1/cat2/cat3'
 */
function mkdirIfNotExist(string $directory)
{
    $dirNames = [];

    for ($i = 0, $k = 0; $i < strlen($directory); $i++) {
        if (!isset($dirNames[$k])) {
            $dirNames[$k] = '';
        }

        if ($directory[$i] !== '/') {
            $dirNames[$k] .= $directory[$i];
        }

        if ($directory[$i] === '/') {
            $k++;
        }
    }

    for ($i = 0, $path = ''; $i < sizeof($dirNames); $i++) {
        if($i === 0) {
            $path .= $dirNames[$i];
        } else {
            $path .= '/' . $dirNames[$i];
        }

        if(!is_dir($path)) {
            mkdir($path);
        }
    }
}

function pathToFileName(string $path)
{
    $fileName = '';

    for ($i = strlen($path) - 1; $i >= 0; $i--) { 
        if($path[$i] === '/') {
            break;
        }
        $fileName = $path[$i] . $fileName;
    }

    return $fileName;
}
