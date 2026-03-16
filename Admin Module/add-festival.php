<?php
// Add/Edit Festival Page
require_once 'config.php';
requireAuth();

$festivals = loadFestivals();
$isEdit = false;
$festival = [
    'name' => '',
    'description' => '',
    'image' => '',
    'date' => '',
    'highlights' => '',
    'activities' => ''
];

$id = $_GET['edit'] ?? $_GET['id'] ?? null;
$errors = [];
$message = '';

if ($id !== null && isset($festivals[$id])) {
    $isEdit = true;
    $festival = $festivals[$id];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $festival = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'date' => $_POST['date'] ?? '',
        'highlights' => $_POST['highlights'] ?? '',
        'activities' => $_POST['activities'] ?? '',
        'image' => $_POST['image'] ?? ''
    ];
    
    // Validation
    if (empty($festival['name'])) $errors[] = 'Festival name is required';
    if (empty($festival['description'])) $errors[] = 'Description is required';
    
    $festival['image'] = '';
    
    // Save if no errors
    if (empty($errors)) {
        if ($isEdit) {
            $festivals[$id] = $festival;
        } else {
            $festivals[] = $festival;
        }
        
        saveFestivals($festivals);
        header('Location: dashboard.php?tab=festivals&message=' . ($isEdit ? 'updated' : 'added'));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Festival - Tagum Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-title">
                <a href="dashboard.php?tab=festivals" class="back-link">← Dashboard</a>
                <h1><?php echo $isEdit ? 'Edit Festival' : 'Add New Festival'; ?></h1>
            </div>
            <div class="admin-nav">
                <a href="logout.php" class="btn btn-primary tab-btn logout-btn">Logout</a>
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
                        <label for="name">Festival Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?php echo htmlspecialchars($festival['name']); ?>" 
                            required
                            class="form-control"
                            placeholder="e.g., Kadayawan Festival"
                        >
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input 
                                type="text" 
                                id="date" 
                                name="date" 
                                value="<?php echo htmlspecialchars($festival['date']); ?>" 
                                class="form-control"
                                placeholder="e.g., August, July 18, February"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            required
                            class="form-control form-textarea"
                            rows="4"
                            placeholder="Brief description of the festival"
                        ><?php echo htmlspecialchars($festival['description']); ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Details</h2>
                    
                    <div class="form-group">
                        <label for="highlights">Highlights</label>
                        <textarea 
                            id="highlights" 
                            name="highlights" 
                            class="form-control form-textarea"
                            rows="3"
                            placeholder="Key highlights of the festival"
                        ><?php echo htmlspecialchars($festival['highlights']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="activities">Activities</label>
                        <textarea 
                            id="activities" 
                            name="activities" 
                            class="form-control form-textarea"
                            rows="3"
                            placeholder="Activities and events during the festival"
                        ><?php echo htmlspecialchars($festival['activities']); ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Media</h2>
                    
                    <?php include 'media-picker.php'; ?> 

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $isEdit ? '✏️ Update Festival' : '➕ Add Festival'; ?>
                        </button>
                        <a href="dashboard.php?tab=festivals" class="btn btn-secondary">Cancel</a>
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

