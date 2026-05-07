<?php
require_once __DIR__ . '/../model/BookModel.php';


function listBooks()
{
    return getAllBooks();
}

function createBook($data)
{
    return addBook($data['title'] ?? '', $data['author'] ?? '', $data['category'] ?? '', $data['status'] ?? 'available');
}

function readBook($id)
{
    return getBookById($id);
}

function editBook($id, $data)
{
    return updateBook($id, $data['title'] ?? '', $data['author'] ?? '', $data['category'] ?? '', $data['status'] ?? 'available');
}

function removeBook($id)
{
    return deleteBook($id);
}

?>
