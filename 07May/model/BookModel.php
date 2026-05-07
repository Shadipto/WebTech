<?php
require_once __DIR__ . '/database.php';

// Model functions for books

function getAllBooks()
{
    $conn = db_connect();
    $sql = "SELECT * FROM books ORDER BY id DESC";
    $res = mysqli_query($conn, $sql);
    $books = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $books[] = $row;
        }
        mysqli_free_result($res);
    }
    mysqli_close($conn);
    return $books;
}

function getBookById($id)
{
    $conn = db_connect();
    $id = (int)$id;
    $sql = "SELECT * FROM books WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    $book = null;
    if ($res) {
        $book = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
    }
    mysqli_close($conn);
    return $book;
}

function addBook($title, $author, $category, $status)
{
    $conn = db_connect();
    $t = mysqli_real_escape_string($conn, $title);
    $a = mysqli_real_escape_string($conn, $author);
    $c = mysqli_real_escape_string($conn, $category);
    $s = mysqli_real_escape_string($conn, $status);

    $sql = "INSERT INTO books (title, author, category, status) VALUES ('$t', '$a', '$c', '$s')";
    $ok = mysqli_query($conn, $sql);
    $id = mysqli_insert_id($conn);
    mysqli_close($conn);
    return $ok ? $id : false;
}

function updateBook($id, $title, $author, $category, $status)
{
    $conn = db_connect();
    $id = (int)$id;
    $t = mysqli_real_escape_string($conn, $title);
    $a = mysqli_real_escape_string($conn, $author);
    $c = mysqli_real_escape_string($conn, $category);
    $s = mysqli_real_escape_string($conn, $status);

    $sql = "UPDATE books SET title='$t', author='$a', category='$c', status='$s' WHERE id=$id";
    $ok = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $ok;
}

function deleteBook($id)
{
    $conn = db_connect();
    $id = (int)$id;
    $sql = "DELETE FROM books WHERE id = $id";
    $ok = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $ok;
}

?>
