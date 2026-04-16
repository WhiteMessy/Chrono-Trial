<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

$leaderboard = new Leaderboard();
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getAll') {
        $entries = $leaderboard->getAll();
        echo json_encode($entries);
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$input) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    $action = $input['action'] ?? '';
    $entry = $input['entry'] ?? [];

    switch ($action) {
        case 'add':
            $result = $leaderboard->add($entry['username'], $entry['time'], $entry['date']);
            echo json_encode(['success' => $result]);
            break;

        case 'update':
            $result = $leaderboard->update($entry['id'], $entry['username'], $entry['time'], $entry['date']);
            echo json_encode(['success' => $result]);
            break;

        case 'delete':
            $result = $leaderboard->delete($entry['id']);
            echo json_encode(['success' => $result]);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
}
?>