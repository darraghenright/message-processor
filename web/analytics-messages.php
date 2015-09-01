<?php

require_once __DIR__ . '/../pdo.php';

// build available date range
$dates = getDateRange($db);
$range = array_fill_keys($dates, 0);

// get count of all messages by date
$stmt = $db->prepare('
    SELECT DATE(time_placed) AS date,
           COUNT(id) AS messages
      FROM trade_message
     GROUP BY date
     ORDER BY date ASC
');

// fetch, merge and rotate data
$stmt->execute();
$data = [];
$stmt->fetchAll(PDO::FETCH_FUNC, function($date, $messages) use (&$data) {
    $data[$date] = (int) $messages;
});

$merged = array_replace($range, $data);

$rotated = [
    'date'     => array_keys($merged),
    'messages' => array_values($merged),
];

// jsonify
header('Content-Type: application/json');
echo json_encode($rotated, JSON_NUMERIC_CHECK);
