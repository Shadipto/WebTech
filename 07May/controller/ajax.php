<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/BookController.php';

$action = $_REQUEST['action'] ?? '';

$response = ['success' => false, 'message' => 'Invalid action'];

switch ($action) {
    case 'list':
        $books = listBooks();
        $response = ['success' => true, 'data' => $books];
        break;
    case 'add':
        $data = [
            'title' => $_POST['title'] ?? '',
            'author' => $_POST['author'] ?? '',
            'category' => $_POST['category'] ?? '',
            'status' => $_POST['status'] ?? 'available'
        ];
        $id = createBook($data);
        if ($id) {
            $response = ['success' => true, 'message' => 'Book added', 'id' => $id];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
        break;
    case 'get':
        $id = $_GET['id'] ?? 0;
        $book = readBook($id);
        if ($book) {
            $response = ['success' => true, 'data' => $book];
        } else {
            $response = ['success' => false, 'message' => 'Not found'];
        }
        break;
    case 'update':
        $id = $_POST['id'] ?? 0;
        $data = [
            'title' => $_POST['title'] ?? '',
            'author' => $_POST['author'] ?? '',
            'category' => $_POST['category'] ?? '',
            'status' => $_POST['status'] ?? 'available'
        ];
        $ok = editBook($id, $data);
        $response = ['success' => (bool)$ok, 'message' => $ok ? 'Updated' : 'Update failed'];
        break;
    case 'delete':
        $id = $_POST['id'] ?? 0;
        $ok = removeBook($id);
        $response = ['success' => (bool)$ok, 'message' => $ok ? 'Deleted' : 'Delete failed'];
        break;
    default:
        $response = ['success' => false, 'message' => 'Unknown action'];
}

echo json_encode($response);

?>
