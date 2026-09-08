<?php
require 'config.php';   
require 'newsletter.php';

if (!isset($_GET['id'])) {
    die("Invalid ID");
}

$id = intval($_GET['id']);

/* -------- FETCH RECORD -------- */
$stmt = $conn->prepare("SELECT * FROM portfolio_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Record not found");
}

/* -------- UPDATE RECORD -------- */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];
    $desc  = $_POST['description'];

    $stmt = $conn->prepare("UPDATE portfolio_items SET title = ?, description = ? WHERE id = ?");
    $stmt->execute([$title, $desc, $id]);

    // ✅ EMAIL NOTIFICATION
    $updatedItem = ['title' => $title, 'description' => $desc];
    // notifySubscribers('Updated', $updatedItem);

    header("Location: add_portfolio.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Portfolio</title>

    <!-- Bootstrap 3.4.1 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .edit-container {
            max-width: 650px;
            margin: 60px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .edit-container h2 {
            margin-bottom: 25px;
            font-weight: 600;
            color: #333;
            text-align: center;
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

        .btn-default {
            font-weight: 500;
        }

        .form-control {
            border-radius: 6px;
            box-shadow: none;
        }

        .form-group label {
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="edit-container">
        <h2>Edit Portfolio Item</h2>

        <form method="post">

            <div class="form-group">
                <label>Project Title <span style="color:red;">*</span></label>
                <input type="text" 
                       name="title" 
                       class="form-control"
                       value="<?= htmlspecialchars($item['title']) ?>" 
                       placeholder="Enter project title"
                       required>
            </div>

            <div class="form-group">
                <label>Project Description <span style="color:red;">*</span></label>
                <textarea name="description" 
                          class="form-control" 
                          rows="5"
                          placeholder="Enter project description"
                          required><?= htmlspecialchars($item['description']) ?></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-ok"></span> Update Portfolio
                </button>

                <a href="add_portfolio.php" class="btn btn-default">
                    <span class="glyphicon glyphicon-arrow-left"></span> Back
                </a>
            </div>

        </form>
    </div>
</div>

</body>
</html>
