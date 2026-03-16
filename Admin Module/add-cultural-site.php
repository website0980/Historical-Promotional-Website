<?php
// Add/Edit Cultural Site Page
require_once 'config.php';
requireAuth();

$culturalSites = loadCulturalSites();
$isEdit = false;
$site = [
    'name' => '',
    'description' => '',
    'image' => '',
    'location' => '',
    'history' => '',
    'highlights' => ''
];

$id = $_GET['edit'] ?? $_GET['id'] ?? null;
$errors = [];
$message = '';

if ($id !== null && isset($culturalSites[$id])) {
    $isEdit = true;
    $site = $culturalSites[$id];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'location' => $_POST['location'] ?? '',
        'history' => $_POST['history'] ?? '',
        'highlights' => $_POST['highlights'] ?? '',
        'image' => $_POST['image'] ?? ''
    ];
    
    // Validation
    if (empty($site['name'])) $errors[] = 'Cultural Site name is required';
    if (empty($site['description'])) $errors[] = 'Description is required';
    
    $site['image'] = '';
    
    // Save if no errors
    if (empty($errors)) {
        if ($isEdit) {
            $culturalSites[$id] = $site;
        } else {
            $culturalSites[] = $site;
        }
        
        saveCulturalSites($culturalSites);
        header('Location: dashboard.php?tab=cultural-sites&message=' . ($isEdit ? 'updated' : 'added'));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Cultural Site - Tagum Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-title">
                <a href="dashboard.php?tab=cultural-sites" class="back-link">← Dashboard</a>
                <h1><?php echo $isEdit ? 'Edit Cultural Site' : 'Add New Cultural Site'; ?></h1>
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
                        <label for="name">Cultural Site Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?php echo htmlspecialchars($site['name']); ?>" 
                            required
                            class="form-control"
                            placeholder="e.g., Tagum City Museum"
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
                            placeholder="Brief description of the cultural site"
                        ><?php echo htmlspecialchars($site['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input 
                            type="text" 
                            id="location" 
                            name="location" 
                            value="<?php echo htmlspecialchars($site['location']); ?>" 
                            class="form-control"
                            placeholder="e.g., City Center, Near City Hall"
                        >
                    </div>
                </div>

                <div class="form-section">
                    <h2>Details</h2>
                    
                    <div class="form-group">
                        <label for="history">History</label>
                        <textarea 
                            id="history" 
                            name="history" 
                            class="form-control form-textarea"
                            rows="4"
                            placeholder="Historical background of the site"
                        ><?php echo htmlspecialchars($site['history']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="highlights">Highlights</label>
                        <textarea 
                            id="highlights" 
                            name="highlights" 
                            class="form-control form-textarea"
                            rows="3"
                            placeholder="Key attractions and highlights"
                        ><?php echo htmlspecialchars($site['highlights']); ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Media</h2>
                    
                    <?php include 'media-picker.php'; ?> 

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $isEdit ? '✏️ Update Cultural Site' : '➕ Add Cultural Site'; ?>
                        </button>
                        <a href="dashboard.php?tab=cultural-sites" class="btn btn-secondary">Cancel</a>
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

