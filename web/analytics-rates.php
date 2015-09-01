<?php

require_once __DIR__ . '/../pdo.php';

// build available date range
$dates = getDateRange($db);
$range = array_fill_keys($dates, 0);

// prepare daily currency rate summary/average
$stmt = $db->prepare('
    SELECT DATE(time_placed)   AS dates,
           ROUND(AVG(rate), 6) AS rates
      FROM trade_message
     WHERE currency_from = :currency_from
       AND currency_to = :currency_to
    GROUP BY dates
    ORDER BY dates
');

// build result
$result = [
    'dates' => $dates,
    'rates' => [],
];

foreach (['AUD', 'CAD', 'GBP', 'USD'] as $currencyTo) {

    $stmt->execute([
        'currency_from' => 'EUR',
        'currency_to'   => $currencyTo,
    ]);

    // fetch, merge etc.
    $data = [];
    $stmt->fetchAll(PDO::FETCH_FUNC, function($dates, $rates) use (&$data) {
        $data[$dates] = (float) $rates;
    });

    $merged = array_replace($range, $data);
    $key = sprintf('EUR/%s', $currencyTo);
    $result['rates'][$key] = array_values($merged);
}

// jsonify
header('Content-Type: application/json');
echo json_encode($result, JSON_NUMERIC_CHECK);
