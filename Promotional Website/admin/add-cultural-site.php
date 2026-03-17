<?php
// Add/Edit Cultural Site Page
require_once 'config.php';
requireAuth();

$culturalSites = loadCulturalSites();
$isEdit = false;
$siteIndex = $_GET['id'] ?? $_POST['id'] ?? null;
if ($siteIndex !== null && $siteIndex !== '') {
    $siteIndex = (int) $siteIndex;
}
$errors = [];
$message = '';

$site = [
    'name' => '',
    'location' => '',
    'history' => '',
    'highlights' => '',
    'image' => '',
    'features' => []
];

// If editing, load the site
if ($siteIndex !== null && $siteIndex !== '' && isset($culturalSites[$siteIndex])) {
    $isEdit = true;
    $site = $culturalSites[$siteIndex];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site = [
        'name' => $_POST['name'] ?? '',
        'location' => $_POST['location'] ?? '',
        'history' => $_POST['history'] ?? '',
        'highlights' => $_POST['highlights'] ?? '',
        'image' => $_POST['image'] ?? '',
        'features' => []
    ];

    // Validate category image file if uploaded (do not save/delete until validation passes)
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $validation = validateImageUpload($_FILES['image_file']);
        if (!$validation['success']) {
            $errors[] = $validation['error'];
        }
    }

    // Validation
    if (empty($site['name'])) $errors[] = 'Site name is required';
    
    // Process features
    $featureNames = $_POST['feature_name'] ?? [];
    $featureDescriptions = $_POST['feature_description'] ?? [];
    $featureImages = $_POST['feature_image'] ?? [];
    
    for ($i = 0; $i < count($featureNames); $i++) {
        if (!empty($featureNames[$i])) {
            $feature = [
                'name' => $featureNames[$i],
                'description' => $featureDescriptions[$i] ?? '',
                'image' => $featureImages[$i] ?? ''
            ];
            
            $site['features'][] = $feature;
        }
    }
    
    // Save if no errors (file operations only after validation)
    if (empty($errors)) {
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $result = saveCulturalSiteImage($_FILES['image_file']);
            if ($result['success']) {
                if (!empty($site['image'])) {
                    deleteCulturalSiteImage($site['image']);
                }
                $site['image'] = $result['path'];
            } else {
                $errors[] = $result['error'] ?? 'Image upload failed';
            }
        }
        if (empty($errors)) {
            if ($isEdit) {
                $culturalSites[$siteIndex] = $site;
            } else {
                $culturalSites[] = $site;
            }
            
            saveCulturalSites($culturalSites);
            header('Location: dashboard.php?tab=cultural-sites&message=' . ($isEdit ? 'updated' : 'added'));
            exit();
        }
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
                <h1><?php echo $isEdit ? 'Edit' : 'Add New'; ?> Cultural Site</h1>
            </div>
            <div class="admin-nav">
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
                <?php if ($isEdit && $siteIndex !== null && $siteIndex !== ''): ?>
                <input type="hidden" name="id" value="<?php echo (int) $siteIndex; ?>">
                <?php endif; ?>
                <div class="form-section">
                    <h2>Site Information</h2>
                    
                    <div class="form-group">
                        <label for="name">Site Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?php echo htmlspecialchars($site['name']); ?>" 
                            required
                            class="form-control"
                            placeholder="e.g., Tagum City Museum, Heritage Church"
                        >
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input 
                            type="text" 
                            id="location" 
                            name="location" 
                            value="<?php echo htmlspecialchars($site['location'] ?? ''); ?>" 
                            class="form-control"
                            placeholder="e.g., City Center, Near City Hall"
                        >
                    </div>
                    <div class="form-group">
                        <label for="history">History</label>
                        <textarea 
                            id="history" 
                            name="history" 
                            class="form-control form-textarea"
                            rows="4"
                            placeholder="Historical background of the site"
                        ><?php echo htmlspecialchars($site['history'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="highlights">Highlights</label>
                        <textarea 
                            id="highlights" 
                            name="highlights" 
                            class="form-control form-textarea"
                            rows="3"
                            placeholder="Key attractions and highlights"
                        ><?php echo htmlspecialchars($site['highlights'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-section">
                        <h2>Site Image</h2>
                        <?php $image = $site['image'] ?? ''; ?>
                        <?php include 'media-picker.php'; ?>
                    </div>

                <div class="form-section">
                    <h2>Features/Highlights in this Cultural Site</h2>
                    <p class="form-hint">Add key features or highlights to this cultural site. Leave empty to create an empty site.</p>
                    
                    <div id="features-container">
                        <?php if (!empty($site['features'])): ?>
                            <?php foreach ($site['features'] as $index => $feature): ?>
                                <div class="item-entry">
                                    <div class="form-group">
                                        <label>Feature Name</label>
                                        <input 
                                            type="text" 
                                            name="feature_name[]" 
                                            value="<?php echo htmlspecialchars($feature['name']); ?>" 
                                            class="form-control"
                                            placeholder="e.g., Ancient Artifacts Display"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea 
                                            name="feature_description[]" 
                                            class="form-control form-textarea"
                                            rows="2"
                                            placeholder="Brief description"
                                        ><?php echo htmlspecialchars($feature['description']); ?></textarea>
                                    </div>
    <div class="form-group">
                                        <?php $featureImage = $feature['image'] ?? ''; ?>
                                        <?php include 'media-picker-item.php'; ?>
                                    </div>
                                    <button type="button" class="btn btn-small btn-delete remove-feature">Remove Feature</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" id="add-feature-btn" class="btn btn-secondary">+ Add Feature</button>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        ✏️ Save Changes
                    </button>
                    <a href="dashboard.php?tab=cultural-sites" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="admin-footer">
        <p>&copy; 2026 Tagum City Admin. All rights reserved.</p>
    </footer>

    <script>
        // Add feature functionality
        document.getElementById('add-feature-btn').addEventListener('click', function() {
            const container = document.getElementById('features-container');
            const featureDiv = document.createElement('div');
            featureDiv.className = 'item-entry';
    featureDiv.innerHTML = `
                <div class="form-group">
                    <label>Feature Name</label>
                    <input type="text" name="feature_name[]" class="form-control" placeholder="e.g., Ancient Artifacts Display">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="feature_description[]" class="form-control form-textarea" rows="2" placeholder="Brief description"></textarea>
                </div>
                <div class="form-group media-picker-item">
                    <label>Image</label>
                    <input type="hidden" name="feature_image[]" value="">
                    <p>Upload image for this feature.</p>
                    <button type="button" class="btn btn-primary" onclick="this.nextElementSibling.click();">Upload Image</button>
                    <input type="file" class="item-image-file" name="feature_image_file[]" accept="image/*" style="display:none;">
                    <div class="item-image-preview"></div>
                    <small>Optional</small>
                </div>
                <button type="button" class="btn btn-small btn-delete remove-feature">Remove Feature</button>
            `;
            container.appendChild(featureDiv);
            
            // Add remove event listener
            featureDiv.querySelector('.remove-feature').addEventListener('click', function() {
                container.removeChild(featureDiv);
            });
        });

        // Remove feature functionality
        document.querySelectorAll('.remove-feature').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
    </script>
</body>
</html>
