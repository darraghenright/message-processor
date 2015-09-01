<?php

require_once __DIR__ . '/../pdo.php';

// get date range and jsonify
header('Content-Type: application/json');
echo json_encode(['dates' => getDateRange($db, 'D, jS M Y')]);
