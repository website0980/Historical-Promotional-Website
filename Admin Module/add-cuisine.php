<?php
// Add/Edit Cuisine Category Page
require_once 'config.php';
requireAuth();

$cuisines = loadCuisine();
$isEdit = false;
$categoryIndex = $_GET['id'] ?? null;
$errors = [];
$message = '';

$category = [
    'category' => '',
    'description' => '',
    'image' => '',
    'items' => []
];

// If editing, load the category
if ($categoryIndex !== null && isset($cuisines[$categoryIndex])) {
    $isEdit = true;
    $category = $cuisines[$categoryIndex];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = [
        'category' => $_POST['category'] ?? '',
        'description' => $_POST['description'] ?? '',
        'image' => $_POST['image'] ?? '',
        'items' => []
    ];
    
    // Validation
    if (empty($category['category'])) $errors[] = 'Category name is required';
    if (empty($category['description'])) $errors[] = 'Description is required';
    
    // Process items
    $itemNames = $_POST['item_name'] ?? [];
    $itemDescriptions = $_POST['item_description'] ?? [];
    $itemImages = $_POST['item_image'] ?? [];
    
    for ($i = 0; $i < count($itemNames); $i++) {
        if (!empty($itemNames[$i])) {
            $item = [
                'name' => $itemNames[$i],
                'description' => $itemDescriptions[$i] ?? '',
                'image' => $itemImages[$i] ?? ''
            ];
            
            $category['items'][] = $item;
        }
    }
    
    // Save if no errors
    if (empty($errors)) {
        if ($isEdit) {
            $cuisines[$categoryIndex] = $category;
        } else {
            $cuisines[] = $category;
        }
        
        saveCuisine($cuisines);
        header('Location: dashboard.php?tab=cuisine&message=' . ($isEdit ? 'updated' : 'added'));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Cuisine Category - Tagum Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-title">
                <a href="dashboard.php?tab=cuisine" class="back-link">← Dashboard</a>
                <h1><?php echo $isEdit ? 'Edit' : 'Add New'; ?> Cuisine Category</h1>
            </div>
            <div class="admin-nav">
                <a href="logout.php" class="btn btn-primary tab-btn logout-btn">Logout</a>
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
                    <h2>Category Information</h2>
                    
                    <div class="form-group">
                        <label for="category">Category Name *</label>
                        <input 
                            type="text" 
                            id="category" 
                            name="category" 
                            value="<?php echo htmlspecialchars($category['category']); ?>" 
                            required
                            class="form-control"
                            placeholder="e.g., Appetizers, Main Courses, Desserts"
                        >
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            required
                            class="form-control form-textarea"
                            rows="3"
                            placeholder="Brief description of this category"
                        ><?php echo htmlspecialchars($category['description']); ?></textarea>
                    </div>

                <div class="form-section">
                    <h2>Dishes in this Category</h2>
                    <p class="form-hint">Add dishes to this category. Leave empty to create an empty category.</p>
                    
                    <div id="items-container">
                        <?php if (!empty($category['items'])): ?>
                            <?php foreach ($category['items'] as $index => $item): ?>
                                <div class="item-entry">
                                    <div class="form-group">
                                        <label>Dish Name</label>
                                        <input 
                                            type="text" 
                                            name="item_name[]" 
                                            value="<?php echo htmlspecialchars($item['name']); ?>" 
                                            class="form-control"
                                            placeholder="e.g., Sinulog na Isda"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea 
                                            name="item_description[]" 
                                            class="form-control form-textarea"
                                            rows="2"
                                            placeholder="Brief description"
                                        ><?php echo htmlspecialchars($item['description']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Image URL (or upload below)</label>
                                        <input 
                                            type="hidden" 
                                            name="item_image[]" 
                                            value=""
                                        >
                                    </div>
                                    <button type="button" class="btn btn-small btn-delete remove-item">Remove Dish</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" id="add-item-btn" class="btn btn-secondary">+ Add Dish</button>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $isEdit ? '✏️ Update Category' : '➕ Add Category'; ?>
                    </button>
                    <a href="dashboard.php?tab=cuisine" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="admin-footer">
        <p>&copy; 2026 Tagum City Admin. All rights reserved.</p>
    </footer>

    <script>
        // Add item functionality
        document.getElementById('add-item-btn').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const itemDiv = document.createElement('div');
            itemDiv.className = 'item-entry';
    itemDiv.innerHTML = `
                <div class="form-group">
                    <label>Dish Name</label>
                    <input type="text" name="item_name[]" class="form-control" placeholder="e.g., Sinulog na Isda">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="item_description[]" class="form-control form-textarea" rows="2" placeholder="Brief description"></textarea>
                </div>
                <div class="form-group">
                    <label>Dish Image (optional)</label>
                    <?php include 'media-picker.php'; ?>
                </div>
                <button type="button" class="btn btn-small btn-delete remove-item">Remove Dish</button>
            `;
            container.appendChild(itemDiv);
            
            // Add remove event listener
            itemDiv.querySelector('.remove-item').addEventListener('click', function() {
                container.removeChild(itemDiv);
            });
        });

        // Remove item functionality
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
    </script>
</body>
</html>
