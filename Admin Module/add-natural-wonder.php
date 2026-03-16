<?php
// Add/Edit Natural Wonder Page
require_once 'config.php';
requireAuth();

$naturalWonders = loadNaturalWonders();
$isEdit = false;
$wonder = [
    'name' => '',
    'description' => '',
    'image' => '',
    'location' => '',
    'features' => '',
    'best_time' => ''
];

$id = $_GET['edit'] ?? $_GET['id'] ?? null;
$errors = [];
$message = '';

if ($id !== null && isset($naturalWonders[$id])) {
    $isEdit = true;
    $wonder = $naturalWonders[$id];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wonder = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'location' => $_POST['location'] ?? '',
        'features' => $_POST['features'] ?? '',
        'best_time' => $_POST['best_time'] ?? '',
        'image' => $_POST['image'] ?? ''
    ];
    
    // Validation
    if (empty($wonder['name'])) $errors[] = 'Natural Wonder name is required';
    if (empty($wonder['description'])) $errors[] = 'Description is required';
    
    $wonder['image'] = '';
    
    // Save if no errors
    if (empty($errors)) {
        if ($isEdit) {
            $naturalWonders[$id] = $wonder;
        } else {
            $naturalWonders[] = $wonder;
        }
        
        saveNaturalWonders($naturalWonders);
        header('Location: dashboard.php?tab=natural-wonders&message=' . ($isEdit ? 'updated' : 'added'));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Natural Wonder - Tagum Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-title">
                <a href="dashboard.php?tab=natural-wonders" class="back-link">← Dashboard</a>
                <h1><?php echo $isEdit ? 'Edit Natural Wonder' : 'Add New Natural Wonder'; ?></h1>
            </div>
            <div class="admin-nav">
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-container">
            <!-- Errors -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" enctype="multipart/form-data" class="destination-form">
                <div class="form-section">
                    <h2>Basic Information</h2>
                    
                    <div class="form-group">
                        <label for="name">Natural Wonder Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?php echo htmlspecialchars($wonder['name']); ?>" 
                            required
                            class="form-control"
                            placeholder="e.g., Pumauna Waterfalls"
                        >
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            required
                            class="form-control form-textarea"
                            rows="4"
                            placeholder="Brief description of the natural wonder"
                        ><?php echo htmlspecialchars($wonder['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input 
                            type="text" 
                            id="location" 
                            name="location" 
                            value="<?php echo htmlspecialchars($wonder['location']); ?>" 
                            class="form-control"
                            placeholder="e.g., 30km north of Tagum City"
                        >
                    </div>
                </div>

                <div class="form-section">
                    <h2>Details</h2>
                    
                    <div class="form-group">
                        <label for="features">Main Features</label>
                        <textarea 
                            id="features" 
                            name="features" 
                            class="form-control form-textarea"
                            rows="3"
                            placeholder="Separate features with line breaks"
                        ><?php echo htmlspecialchars($wonder['features']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="best_time">Best Time to Visit</label>
                        <input 
                            type="text" 
                            id="best_time" 
                            name="best_time" 
                            value="<?php echo htmlspecialchars($wonder['best_time']); ?>" 
                            class="form-control"
                            placeholder="e.g., December to May"
                        >
                    </div>
                </div>

                <div class="form-section">
                    <h2>Media</h2>
                    
                    <?php include 'media-picker.php'; ?> 

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $isEdit ? '✏️ Update Natural Wonder' : '➕ Add Natural Wonder'; ?>
                        </button>
                        <a href="dashboard.php?tab=natural-wonders" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="admin-footer">
        <p>&copy; 2026 Tagum City Admin. All rights reserved.</p>
    </footer>

    <script src="../assets/js/admin.js"></script>
</body>
</html>

