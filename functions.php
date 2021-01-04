<?php

/**
 * Обрезает текст до указанной длины и
 * добавляет в конце три точки
 *
 * @param  string $text            Строка для обрезания
 * @param  int    $truncate_length Длина строки
 * @return string Обрезанная строка
 */
function truncate_text(string $text, int $truncate_length = 300): string
{
    if (mb_strlen($text) <= $truncate_length) {
        return $text;
    } else {
        $words = explode(" ", $text);
        $i = 0;
        while ($current_length - 1 < $truncate_length) {
            $current_length += mb_strlen($words[$i]);
            $current_length++;
            $i++;
        }
        $final_text = implode(" ", array_slice($words, 0, $i - 1));
        $final_text .= '...';
        return $final_text;
    }
}

/**
 * Загружает из БД виды контента
 * @param  mysqli $connection
 * @return array Виды контента
 */
function get_content_types($connection)
{
    $content_types_mysqli = mysqli_query(
        $connection,
        'SELECT * FROM content_types'
    );
    $content_types = mysqli_fetch_all($content_types_mysqli, MYSQLI_ASSOC);
    return $content_types;
}

/**
 * Преобразует время из абсолютного формата в относительный
 * @param string $time Когда событие случилось в формате Y-m-d H:i:s
 * @param  string $last_word Слово, добавляемое в конце предложения.
 * @return string Сколько времени прошло с события в годах, месяцах, неделях, днях, часах и минутах
 */
function absolute_time_to_relative(string $time, string $last_word = 'назад'): string
{
    if (!$time) {
        return '';
    }
    date_default_timezone_set('Asia/Yekaterinburg');
    $date = date_create_from_format('Y-m-d H:i:s', $time);
    $current_date = date_create();
    $interval = date_diff($date, $current_date);
    $interval_in_minutes = $interval->days * 24 * 60;
    $interval_in_minutes += $interval->h * 60;
    $interval_in_minutes += $interval->i;
    if ($interval_in_minutes < 60) {
        $relative_time = $interval->i . ' ' . get_noun_plural_form($interval->i, 'минута', 'минуты', 'минут') .
        ' ' . $last_word;
    } elseif ($interval_in_minutes < 1440) {
        $relative_time = $interval->h . ' ' . get_noun_plural_form($interval->h, 'час', 'часа', 'часов') .
         ' ' . $last_word;
    } elseif ($interval_in_minutes < 10080) {
        $relative_time = $interval->d . ' ' . get_noun_plural_form($interval->d, 'день', 'дня', 'дней') .
         ' ' . $last_word;
    } elseif ($interval_in_minutes < 50400) {
        $weeks = floor($interval->d / 7);
        $relative_time = $weeks . ' ' . get_noun_plural_form($weeks, 'неделя', 'недели', 'недель') . ' ' . $last_word;
    } elseif ($interval_in_minutes < 525600) {
        $relative_time = $interval->m . ' ' . get_noun_plural_form($interval->m, 'месяц', 'месяца', 'месяцев') .
        ' ' . $last_word;
    } else {
        $relative_time = $interval->y . ' ' . get_noun_plural_form($interval->y, 'год', 'года', 'лет') .
        ' ' . $last_word;
    }
    return $relative_time;
}

/**
 * Подготовка и выполнение безопасного запроса
 *
 * @param  mysqli $connection
 * @param  string $sql        Исходный запрос со знаками ? в месте подстановки параметров
 * @param  mixed  $params Типы параметров в формате 'i','s','d','b'
 * @return void   Результат выполнения подготовленного запроса
 */
function secure_query(mysqli $connection, string $sql, ...$params)
{
    foreach ($params as $param) {
        $param_types .= (gettype($param) == 'integer') ? 'i' : 's';
    }
    $prepared_sql = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($prepared_sql, $param_types, ...$params);
    mysqli_stmt_execute($prepared_sql);
    return mysqli_stmt_get_result($prepared_sql);
}

/**
 * Подготовка и выполнение безопасного запроса с bind результата
 *
 * @param  mysqli $connection
 * @param  string $sql Исходный запрос со знаками ? в месте подстановки параметров
 * @param  mixed $params Типы параметров в формате 'i','s','d','b'
 * @return void Результат выполнения подготовленного запроса
 */
function secure_query_bind_result(mysqli $connection, string $sql, ...$params)
{
    foreach ($params as $param) {
        $param_types .= (gettype($param) == 'integer') ? 'i' : 's';
    }
    $prepared_sql = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($prepared_sql, $param_types, ...$params);
    mysqli_stmt_execute($prepared_sql);
    mysqli_stmt_bind_result($prepared_sql, $bind);
    mysqli_stmt_fetch($prepared_sql);
    mysqli_stmt_close($prepared_sql);
    return $bind;
}

/**
 * Возвращает значение, если оно содержится в массиве, иначе возвращает null
 *
 * @param  mixed $value Искомое значение
 * @param  array $allowed Массив, в котором ищем
 * @return mixed Исходное значение, если найдено в массиве. Иначе - null
 */
function white_list($value, array $allowed)
{
    $key = array_search($value, $allowed, true);
    if ($key === false) {
        return null;
    }
    return $value;
}

/**
 * Выводит страницу 404
 *
 */
function display_404_page()
{
    $page_content = include_template('404.php');
    $layout_content = include_template('layout.php', ['content' => $page_content]);
    print($layout_content);
    http_response_code(404);
}

/**
 * Разделяет строку с правилами валидации на отдельные элементы
 *
 * @param  array $rules Массив с правилами валидации в формате строки с разделителями
 * @return array Преобразованный массив, каждое правило - отдельный элемент.
 */
function getValidationRules(array $rules): array
{
    $result = [];
    foreach ($rules as $fieldName => $rule) {
        $result[$fieldName] = explode('|', $rule);
    }
    return $result;
}

/**
 * Формирует имя функции валидации для дальнейшего вызова
 *
 * @param  string $name Название метода валидации
 * @return string Имя функции валидации без дефисов, подчеркиваний, с большой буквы, с приставкой 'validate'
 */
function getValidationMethodName(string $name): string
{
    $studlyWords = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    return "validate{$studlyWords}";
}

/**
 * Разделяет название метода валидации и его параметры
 *
 * @param  string $rule
 * @return array Массив из названия и массива параметров
 */
function getValidationNameAndParameters(string $rule): array
{
    $nameParams = explode(':', $rule);
    $parameters = [];
    $name = $nameParams[0];
    if (isset($nameParams[1])) {
        $parameters = explode(',', $nameParams[1]);
    }
    return [$name, $parameters];
}

/**
 * Валидация заполненого поля
 *
 * @param  array $inputArray
 * @param  string $parameterName
 * @return string Ошибка или null
 */
function validateFilled(array $inputArray, string $parameterName): ?string
{
    if (empty($inputArray[$parameterName])) {
        return 'Это поле должно быть заполнено';
    }
    return null;
}

/**
 * Проверяет, что строка не длиннее заданного числа символов
 *
 * @param  array $inputArray Массив полей и их значений
 * @param  string $parameterName Имя текстового поля
 * @param  int $length Длина
 * @return string Ошибка либо null
 */
function validateLong(array $inputArray, string $parameterName, int $length): ?string
{
    if (strlen(trim($inputArray[$parameterName])) < $length) {
        return 'Текст должен быть не короче ' . $length . ' символов';
    }
    return null;
}

/**
 * Проверяет корректность URL-адреса
 *
 * @param array $inputArray Массив полей и их значений
 * @param string $parameterName Имя поля, содержащего URL
 * @return string Ошибка либо Null
 */
function validateCorrectURL(array $inputArray, string $parameterName): ?string
{
    if (!filter_var($inputArray[$parameterName], FILTER_VALIDATE_URL)) {
        return 'Некорретный URL-адрес';
    }
    return null;
}

/**
 * Проверяет совпадение двух вводов пароля
 *
 * @param  array $inputArray
 * @param  array $parameterName
 * @return string Сообщение об ошибке, если нет ошибки - null
 */
function validateRepeatPassword(array $inputArray, string $parameterName): ?string
{
    if ($inputArray['password'] !== $inputArray['password-repeat']) {
        return 'Пароли не совпадают';
    }
    return null;
}

/**
 * Проверяет корректность введенного email-адреса
 *
 * @param  array $inputArray
 * @param  string $parameterName
 * @return string Сообщение об ошибке, если нет ошибки - null
 */
function validateCorrectEmail(array $inputArray, string $parameterName): ?string
{
    if (!filter_var($inputArray[$parameterName], FILTER_VALIDATE_EMAIL)) {
        return 'Некорретный email';
    }
    return null;
}

/**
 * Проверяет отсутствие значения в БД
 *
 * @param  array $validationArray Валидируемый массив
 * @param  string $parameterName Имя параметра, который ищем
 * @param  string $tableName Имя таблицы БД
 * @param  string $columnName Имя столбца таблицы
 * @param  mysqli $dbConnection
 * @return string Сообщение об ошибке, если нет ошибки - null
 */
function validateExists(
    array $validationArray,
    string $parameterName,
    $tableName,
    $columnName,
    mysqli $dbConnection
): ?string {
    $sql = "select count(*) as amount from $tableName where $columnName = ?";
    $amount = secure_query_bind_result($dbConnection, $sql, $validationArray[$parameterName]);
    return $amount > 0 ? "Запись с таким $parameterName уже присутствует в базе данных" : null;
}


/**
 * Проверяет наличия значения в БД
 *
 * @param  array $validationArray Валидируемый массив
 * @param  string $parameterName Имя параметра, который ищем
 * @param  string $tableName Имя таблицы БД
 * @param  string $columnName Имя столбца таблицы
 * @param  mysqli $dbConnection
 * @return string Сообщение об ошибке, если нет ошибки - null
 */
function validateNotexists(
    array $validationArray,
    string $parameterName,
    string $tableName,
    string $columnName,
    mysqli $dbConnection
): ?string {
    $sql = "select count(*) as amount from $tableName where $columnName = ?";
    $amount = secure_query_bind_result($dbConnection, $sql, $validationArray[$parameterName]);
    return $amount === 0 ? "Записи с таким $parameterName нет в базе данных" : null;
}

/**
 * Проверяет правильность введенного пароля
 *
 * @param  array $validationArray
 * @param  string $parameterName
 * @param  string $tableName
 * @param  string $usersColumnName
 * @param  string $passwordColumnName
 * @param  mixed $dbConnection
 * @return string Ошибка либо null
 */
function validateCorrectPassword(
    array $validationArray,
    string $parameterName,
    string $tableName,
    string $usersColumnName,
    string $passwordColumnName,
    mysqli $dbConnection
): ?string {
    $sql = "select password as dbpassword from $tableName where $usersColumnName = ?";
    $dbpassword = secure_query_bind_result($dbConnection, $sql, $validationArray[$parameterName]);
    return !password_verify($validationArray[$parameterName], $dbpassword) ? "Вы ввели неверный email/пароль" : null;
}

/**
 * Проверяет загружен ли файл и является ли он изображением
 *
 * @param  array $inputArray Массив полей и их значений
 * @param  string $parameterName Имя поля, через которое загружен файл
 * @return string Ошибка либо null
 */
function validateImgLoaded(array $inputArray, string $parameterName): ?string
{
    if ($inputArray[$parameterName]['error'] !== 0) {
        return 'Код ошибки:' . $inputArray[$parameterName]['error'];
    }
    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $file_name = $inputArray[$parameterName]['tmp_name'];
    $file_type = finfo_file($file_info, $file_name);
    return !in_array($file_type, ['image/png','image/jpeg', 'image/gif']) ? 'Недопустимый тип изображения' : null;
}

/**
 * Сохраняет файл в заданную папку
 *
 * @param  string $img Название поля с изображением
 * @param  string $img_folder Папка, куда сохраняем
 * @return string
 */
function save_image(string $img, string $img_folder): ?string
{
    if ($_FILES[$img]['error'] !== 0) {
        return null;
    }
    $file_name = $_FILES[$img]['name'];
    move_uploaded_file($_FILES[$img]['tmp_name'], $img_folder . $file_name);
    return $file_name;
}

/**
 * Проверяет наличие по ссылке изображения
 *
 * @param  array $inputArray Массив полей и их значений
 * @param  string $parameterName Имя поля, содержащего ссылку на изображение
 * @return string Ошибка либо null
 */
function validateImageURLContent(array $inputArray, string $parameterName): ?string
{
    if (!file_get_contents($inputArray[$parameterName])) {
        return 'По ссылке отсутствует изображение';
    }
    return null;
}

/**
 * Проверяет, что переданная ссылка ведет на публично доступное видео с youtube
 * @param string $youtube_url Ссылка на youtube видео
 * @return bool Доступна или недоступна ссылка
 */
function validateYoutubeURL(array $inputArray, string $parameterName): ?string
{
    $res = false;
    $id = extract_youtube_id($inputArray[$parameterName]);

    if ($id) {
        $api_data = ['id' => $id, 'part' => 'id,status', 'key' => 'AIzaSyD24lsJ4BL-azG188tHxXtbset3ehKXeJg'];
        $url = "https://www.googleapis.com/youtube/v3/videos?" . http_build_query($api_data);

        $resp = file_get_contents($url);

        if ($resp && $json = json_decode($resp, true)) {
            $res = $json['pageInfo']['totalResults'] > 0 && $json['items'][0]['status']['privacyStatus'] == 'public';
        } else {
            $res = 'Видео по ссылке не найдено';
        }
    }
    return $res;
}

/**
 * Валидация массива значений из форм
 *
 * @param  mysqli $db_connection
 * @param  array $fields Валидируемый массив вида поле - значение
 * @param  array $validationArray Массив правил валидации вида поле - список правил валидации
 * @return array Список ошибок валидации
 */
function validate(mysqli $db_connection, array $fields, array $validationArray): array
{
    $db_functions = ['validateExists', 'validateNotexists', 'validateCorrectpassword'];
    $validations = getValidationRules($validationArray);
    $errors = [];
    foreach ($validations as $field => $rules) {
        foreach ($rules as $rule) {
            [$name, $parameters] = getValidationNameAndParameters($rule);
            $methodName = getValidationMethodName($name);
            $methodParameters = array_merge([$fields, $field], $parameters);
            if (!function_exists($methodName)) {
                return 'Функции валидации ' . $methodName . ' не существует';
            }
            if (in_array($methodName, $db_functions)) {
                array_push($methodParameters, $db_connection);
            }
            if ($errors[$field] = call_user_func_array($methodName, $methodParameters)) {
                break;
            };
        }
    }
    return $errors;
}
