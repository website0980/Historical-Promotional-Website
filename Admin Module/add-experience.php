<?php
// Add/Edit Experience Page
require_once 'config.php';
requireAuth();

$experiences = loadExperiences();
$isEdit = false;
$experience = [
    'id' => '',
    'name' => '',
    'type' => '',
    'description' => '',
    'image' => '',
    'date' => '',
    'featured' => false
];

$id = $_GET['id'] ?? null;
$errors = [];
$message = '';

if ($id !== null && isset($experiences[$id])) {
    $isEdit = true;
    $experience = $experiences[$id];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $experience = [
        'id' => $_POST['id'] ?? time(),
        'name' => $_POST['name'] ?? '',
        'type' => $_POST['type'] ?? '',
        'description' => $_POST['description'] ?? '',
        'date' => $_POST['date'] ?? '',
        'image' => $_POST['image'] ?? '',
        'featured' => isset($_POST['featured']) ? true : false
    ];
    
    // Validation
    if (empty($experience['name'])) $errors[] = 'Experience name is required';
    if (empty($experience['type'])) $errors[] = 'Experience type is required';
    if (empty($experience['description'])) $errors[] = 'Description is required';
    if (empty($experience['date'])) $errors[] = 'Date is required';
    
    $experience['image'] = '';
    
    // Save if no errors
    if (empty($errors)) {
        if ($isEdit) {
            $experiences[$id] = $experience;
        } else {
            $experiences[] = $experience;
        }
        
        saveExperiences($experiences);
        header('Location: dashboard.php?tab=experiences&message=' . ($isEdit ? 'updated' : 'added'));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Experience - Tagum Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-title">
                <a href="dashboard.php?tab=experiences" class="back-link">← Dashboard</a>
                <h1><?php echo $isEdit ? 'Edit Experience' : 'Add New Experience'; ?></h1>
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
                        <label for="name">Experience Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?php echo htmlspecialchars($experience['name']); ?>" 
                            required
                            class="form-control"
                            placeholder="e.g., River Tours"
                        >
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="type">Experience Type *</label>
                            <select id="type" name="type" required class="form-control">
                                <option value="">-- Select Type --</option>
                                <option value="river-tours" <?php echo $experience['type'] === 'river-tours' ? 'selected' : ''; ?>>River Tours</option>
                                <option value="mountain-hiking" <?php echo $experience['type'] === 'mountain-hiking' ? 'selected' : ''; ?>>Mountain Hiking</option>
                                <option value="cultural-events" <?php echo $experience['type'] === 'cultural-events' ? 'selected' : ''; ?>>Cultural Events</option>
                                <option value="food-tours" <?php echo $experience['type'] === 'food-tours' ? 'selected' : ''; ?>>Food Tours</option>
                                <option value="beach-adventures" <?php echo $experience['type'] === 'beach-adventures' ? 'selected' : ''; ?>>Beach Adventures</option>
                                <option value="wildlife" <?php echo $experience['type'] === 'wildlife' ? 'selected' : ''; ?>>Wildlife Encounters</option>
                                <option value="wellness" <?php echo $experience['type'] === 'wellness' ? 'selected' : ''; ?>>Wellness & Spa</option>
                                <option value="nightlife" <?php echo $experience['type'] === 'nightlife' ? 'selected' : ''; ?>>Nightlife</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date">Date *</label>
                            <input 
                                type="date" 
                                id="date" 
                                name="date" 
                                value="<?php echo htmlspecialchars($experience['date'] ?? ''); ?>" 
                                required
                                class="form-control"
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
                            placeholder="Brief description of the experience"
                        ><?php echo htmlspecialchars($experience['description']); ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Media & Settings</h2>
                    
                    <?php include 'media-picker.php'; ?> 

                    <div class="form-group checkbox">
                        <input 
                            type="checkbox" 
                            id="featured" 
                            name="featured" 
                            <?php echo ($experience['featured'] ?? false) ? 'checked' : ''; ?>
                        >
                        <label for="featured">Mark as Featured Experience</label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $isEdit ? '✏️ Update Experience' : '➕ Add Experience'; ?>
                        </button>
                        <a href="dashboard.php?tab=experiences" class="btn btn-secondary">Cancel</a>
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

