<?php

/**
 * dbInit
 *
 * @param  string $host
 * @param  string $name
 * @param  string $user
 * @param  string $pass
 * @return PDO
 */
function dbInit($host, $name, $user, $pass)
{
    try {
        $dsn = sprintf('mysql:host=%s;dbname=%s', $host, $name);
        return new PDO($dsn, $user, $pass);
    } catch (PDOException $e) {
        header('HTTP/1.1 503 Service Unavailable');
        exit('Sorry! There was an error.');
    }
}

/**
 * getDateRange
 *
 * Creates a data range, starting from
 * the date of the earliest trade message
 * up to and including today.
 *
 * @param  PDO    $db
 * @param  string $format
 * @return array
 */
function getDateRange(PDO $db, $format = 'Y-m-d')
{
    $stmt = $db->prepare('
        SELECT MIN(DATE(time_placed)) AS date_min
          FROM trade_message
    ');

    $stmt->execute();
    $dateMin = $stmt->fetch(PDO::FETCH_COLUMN);

    $dp = new DatePeriod(
        new DateTime($dateMin),
        new DateInterval('P1D'),
        new DateTime('tomorrow')
    );

    return array_map(function($dt) use ($format){
        return $dt->format($format);
    }, iterator_to_array($dp));
}
