<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Library Management</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 12px;
      background: #ffffff;
      color: #111;
    }
    h2, h3 {
      margin: 0 0 10px;
    }
    .wrap {
      max-width: 980px;
    }
    #form-area {
      background: #f7f7f7;
      border: 1px solid #999;
      padding: 10px;
      max-width: 600px;
    }
    .form-row {
      margin-bottom: 8px;
    }
    label {
      display: inline-block;
      width: 95px;
    }
    input[type=text], select {
      padding: 5px;
      width: 240px;
      border: 1px solid #888;
      background: #fff;
    }
    button {
      padding: 5px 10px;
      border: 1px solid #666;
      background: #e5e5e5;
      cursor: pointer;
    }
    button:hover {
      background: #d9d9d9;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background: #fff;
    }
    th, td {
      border: 1px solid #888;
      padding: 7px;
      text-align: left;
    }
    th {
      background: #efefef;
    }
    hr {
      margin: 14px 0;
      border: none;
      border-top: 1px solid #bbb;
    }
  </style>
</head>
<body>
  <div class="wrap">
  <h2>Library Management</h2>

  <div id="form-area">
    <h3 id="form-title">Add New Book</h3>
    <form id="book-form">
      <input type="hidden" name="id" id="book-id" />
      <div class="form-row"><label>Title</label><input type="text" name="title" id="title" required></div>
      <div class="form-row"><label>Author</label><input type="text" name="author" id="author" required></div>
      <div class="form-row"><label>Category</label><input type="text" name="category" id="category" required></div>
      <div class="form-row"><label>Status</label>
        <select name="status" id="status">
          <option value="available">available</option>
          <option value="borrowed">borrowed</option>
        </select>
      </div>
      <div class="form-row">
        <button type="submit" id="save-btn">Save</button>
        <button type="button" id="cancel-btn" style="display:none">Cancel</button>
      </div>
    </form>
  </div>

  <hr>

  <h3>Books List</h3>
  <table id="books-table">
    <thead>
      <tr><th>ID</th><th>Title</th><th>Author</th><th>Category</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
    </tbody>
  </table>

  <script>
    // simple helper to send form data via fetch
    function postData(url, data) {
      return fetch(url, { method: 'POST', body: data }).then(res => res.json());
    }

    function loadBooks() {
      fetch('../controller/ajax.php?action=list').then(r => r.json()).then(res => {
        if (res.success) {
          const tbody = document.querySelector('#books-table tbody');
          tbody.innerHTML = '';
          res.data.forEach(book => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td>${book.id}</td>
              <td>${book.title}</td>
              <td>${book.author}</td>
              <td>${book.category}</td>
              <td>${book.status}</td>
              <td>
                <button data-id="${book.id}" class="edit-btn">Edit</button>
                <button data-id="${book.id}" class="del-btn">Delete</button>
              </td>
            `;
            tbody.appendChild(tr);
          });
        } else {
          alert('Failed to load books');
        }
      });
    }

    document.addEventListener('DOMContentLoaded', function () {
      loadBooks();

      const form = document.getElementById('book-form');
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('book-id').value;
        const fd = new FormData(form);
        if (id) {
          fd.append('action', 'update');
          postData('../controller/ajax.php', fd).then(res => {
            if (res.success) {
              form.reset(); document.getElementById('book-id').value = '';
              document.getElementById('form-title').textContent = 'Add New Book';
              document.getElementById('cancel-btn').style.display = 'none';
              loadBooks();
            } else alert(res.message || 'Update failed');
          });
        } else {
          fd.append('action', 'add');
          postData('../controller/ajax.php', fd).then(res => {
            if (res.success) {
              form.reset(); loadBooks();
            } else alert(res.message || 'Insert failed');
          });
        }
      });

      document.getElementById('cancel-btn').addEventListener('click', function () {
        document.getElementById('book-form').reset();
        document.getElementById('book-id').value = '';
        document.getElementById('form-title').textContent = 'Add New Book';
        this.style.display = 'none';
      });

      // delegate edit/delete buttons
      document.querySelector('#books-table tbody').addEventListener('click', function (e) {
        if (e.target.classList.contains('edit-btn')) {
          const id = e.target.getAttribute('data-id');
          fetch('../controller/ajax.php?action=get&id=' + encodeURIComponent(id)).then(r => r.json()).then(res => {
            if (res.success) {
              const b = res.data;
              document.getElementById('book-id').value = b.id;
              document.getElementById('title').value = b.title;
              document.getElementById('author').value = b.author;
              document.getElementById('category').value = b.category;
              document.getElementById('status').value = b.status;
              document.getElementById('form-title').textContent = 'Edit Book';
              document.getElementById('cancel-btn').style.display = 'inline-block';
            } else alert('Book not found');
          });
        } else if (e.target.classList.contains('del-btn')) {
          const id = e.target.getAttribute('data-id');
          if (confirm('Delete this book?')) {
            const fd = new FormData(); fd.append('action', 'delete'); fd.append('id', id);
            postData('../controller/ajax.php', fd).then(res => {
              if (res.success) loadBooks(); else alert(res.message || 'Delete failed');
            });
          }
        }
      });
    });
  </script>
  </div>
</body>
</html>
