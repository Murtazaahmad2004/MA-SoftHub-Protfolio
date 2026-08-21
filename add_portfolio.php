<?php
require 'config.php';
require 'newsletter.php';

/* ---------------- DELETE ---------------- */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = mysqli_prepare($conn, "DELETE FROM portfolio_items WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: add_portfolio.php");
    exit;
}

/* ---------------- ADD / UPDATE ---------------- */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id    = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $desc  = $_POST['description'] ?? '';

    if ($id) {
        // UPDATE
        $stmt = mysqli_prepare($conn, "UPDATE portfolio_items SET title = ?, description = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $title, $desc, $id);
        mysqli_stmt_execute($stmt);

        $updatedItem = ['title' => $title, 'description' => $desc];
        notifySubscribers('Updated', $updatedItem);

        $successMsg = "Portfolio item updated and subscribers notified!";
    } else {
        // INSERT
        $stmt = mysqli_prepare($conn, "INSERT INTO portfolio_items (title, description) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $title, $desc);
        mysqli_stmt_execute($stmt);

        $newItem = ['title' => $title, 'description' => $desc];
        notifySubscribers('Added', $newItem);

        $successMsg = "Portfolio item added and subscribers notified!";
    }
}

/* ---------------- FETCH ALL ---------------- */
$result = mysqli_query($conn, "SELECT * FROM portfolio_items ORDER BY id DESC");
$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Portfolio Item</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 700px;
            margin-top: 50px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 30px;
            font-weight: 600;
            color: #333;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .form-control {
            border-radius: 5px;
        }
        .alert-success {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Portfolio Item</h2>


    <form method="post">
        <div class="form-group">
            <label for="title">Title <span style="color:red;">*</span></label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Enter project title" required>
        </div>

        <div class="form-group">
            <label for="description">Description <span style="color:red;">*</span></label>
            <textarea class="form-control" id="description" name="description" rows="5" placeholder="Enter project description" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Add Portfolio</button>
    </form>

    <hr>
<h3>All Portfolio Items</h3>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th width="120">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['title']) ?></td>
            <td><?= htmlspecialchars($item['description']) ?></td>
            <td>
                <a href="edit_portfolio.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?delete=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>

</table>
</div>
</body>
</html>