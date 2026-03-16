<?php
require_once 'config.php';
requireAuth();

$pdo = $config['pdo']; // Assume PDO connection in config

$id = $_GET['id'] ?? $_POST['id'] ?? 0;
$destination = [];
$current_image = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
    $stmt->execute([$id]);
    $destination = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_image = $destination['image'] ?? '';
}

$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        // add other fields
        'image' => $current_image
    ];

    // Handle image upload (optional)
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $file_ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed)) {
            $new_filename = uniqid() . '.' . $file_ext;
            $file_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $file_path)) {
                // Delete old image if exists
                if ($current_image && file_exists(__DIR__ . '/../../' . $current_image)) {
                    unlink(__DIR__ . '/../../' . $current_image);
                }
                $data['image'] = 'uploads/' . $new_filename;
            } else {
                $errors[] = 'Upload failed';
            }
        } else {
            $errors[] = 'Invalid file type';
        }
    }

    // Update database
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE destinations SET name=?, description=?, image=? WHERE id=?");
        $stmt->execute([$data['name'], $data['description'], $data['image'], $id]);
        $message = 'Updated successfully';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Destination</title>
</head>
<body>
    <?php if ($message): ?>
        <p style="color:green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($destination['name'] ?? ''); ?>"><br>
        
        <label>Description:</label>
        <textarea name="description"><?php echo htmlspecialchars($destination['description'] ?? ''); ?></textarea><br>

        <?php if ($current_image): ?>
            <div>
                <img src="../<?php echo htmlspecialchars($current_image); ?>" style="max-width:200px;">
                <p>Current: <?php echo basename($current_image); ?></p>
            </div>
        <?php endif; ?>

        <label>New Image (optional):</label>
        <input type="file" name="image_file" accept="image/*"><br>
        <small>Leave empty to keep current image</small><br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
