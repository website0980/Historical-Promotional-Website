<?php
// Add/Edit Cuisine Category Page
require_once 'config.php';
requireAuth();

$cuisines = loadCuisine();
$isEdit = false;
$categoryIndex = $_GET['id'] ?? $_POST['id'] ?? null;
if ($categoryIndex !== null && $categoryIndex !== '') {
    $categoryIndex = (int) $categoryIndex;
}
$errors = [];
$message = '';

$category = [
    'category' => '',
    'description' => '',
    'image' => '',
    'items' => []
];

// If editing, load the category
if ($categoryIndex !== null && $categoryIndex !== '' && isset($cuisines[$categoryIndex])) {
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

    // Validate category image file if uploaded (do not save/delete until validation passes)
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $validation = validateImageUpload($_FILES['image_file']);
        if (!$validation['success']) {
            $errors[] = $validation['error'];
        }
    }

    // Validation
    if (empty($category['category'])) $errors[] = 'Category name is required';
    if (empty($category['description'])) $errors[] = 'Description is required';
    
    // Process items - text path + file upload
    $itemNames = $_POST['item_name'] ?? [];
    $itemDescriptions = $_POST['item_description'] ?? [];
    $itemImagePaths = $_POST['item_image'] ?? [];
    $itemImageFiles = $_FILES['item_image_file'] ?? [];
    
    $numItems = max(count($itemNames), count($itemImagePaths));
    
    for ($i = 0; $i < $numItems; $i++) {
        if (!empty($itemNames[$i])) {
            $imagePath = trim($itemImagePaths[$i] ?? '');
            
            // Check if file upload
            if (isset($itemImageFiles['error'][$i]) && $itemImageFiles['error'][$i] === UPLOAD_ERR_OK) {
                $fileValidation = validateImageUpload([
                    'name' => $itemImageFiles['name'][$i],
                    'tmp_name' => $itemImageFiles['tmp_name'][$i],
                    'error' => $itemImageFiles['error'][$i],
                    'size' => $itemImageFiles['size'][$i]
                ]);
                if ($fileValidation['success']) {
                    $uploadResult = saveCuisineImage([
                        'name' => $itemImageFiles['name'][$i],
                        'tmp_name' => $itemImageFiles['tmp_name'][$i],
                        'error' => $itemImageFiles['error'][$i],
                        'size' => $itemImageFiles['size'][$i]
                    ]);
                    if ($uploadResult['success']) {
                        $imagePath = $uploadResult['path'];
                    } else {
                        $errors[] = 'Dish image upload failed';
                    }
                } else {
                    $errors[] = 'Dish image invalid';
                }
            }
            
            $item = [
                'name' => $itemNames[$i],
                'description' => $itemDescriptions[$i] ?? '',
                'image' => $imagePath
            ];
            $category['items'][] = $item;
        }
    }
    
    // Save if no errors
    if (empty($errors)) {
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $result = saveCuisineImage($_FILES['image_file']);
            if ($result['success']) {
                if (!empty($category['image'])) {
                    deleteCuisineImage($category['image']);
                }
                $category['image'] = $result['path'];
            } else {
                $errors[] = $result['error'] ?? 'Category image upload failed';
            }
        }
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
                <?php if ($isEdit && $categoryIndex !== null && $categoryIndex !== ''): ?>
                <input type="hidden" name="id" value="<?php echo (int) $categoryIndex; ?>">
                <?php endif; ?>
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
                        <h2>Category Image</h2>
                        <?php $image = $category['image'] ?? ''; ?>
                        <?php include 'media-picker.php'; ?>
                    </div>

                <div class="form-section">
                    <h2>Dishes in this Category</h2>
                    <p class="form-hint">Add dishes. Click "Upload Image" to select file - saves on Submit.</p>
                    
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
                                        <label>Dish Image</label>
                                        <?php $itemImage = $item['image'] ?? ''; ?>
                                        <input type="hidden" name="item_image[]" value="<?php echo htmlspecialchars($itemImage); ?>">
                                        <button type="button" class="btn btn-primary upload-dish-image">Upload Image</button>
                                        <input type="file" class="dish-image-upload" name="item_image_file[]" accept="image/*" style="display:none;">
                                        <div class="dish-image-preview"></div>
                                        <?php if (!empty($itemImage)): ?>
                                            <div class="current-item-image">
                                                <img src="<?php echo htmlspecialchars($itemImage); ?>" alt="Current" style="max-width:100px;">
                                                <small><?php echo basename($itemImage); ?></small>
                                            </div>
                                        <?php endif; ?>
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
                    <label>Dish Image</label>
                    <input type="hidden" name="item_image[]" value="">
                    <button type="button" class="btn btn-primary upload-dish-image">Upload Image</button>
                    <input type="file" class="dish-image-upload" name="item_image_file[]" accept="image/*" style="display:none;">
                    <div class="dish-image-preview"></div>
                </div>
                <button type="button" class="btn btn-small btn-delete remove-item">Remove Dish</button>
            `;
            container.appendChild(itemDiv);
            
            // Bind new item
            bindDishUpload(itemDiv);
            itemDiv.querySelector('.remove-item').addEventListener('click', function() {
                container.removeChild(itemDiv);
            });
        });

        function bindDishUpload(parent) {
            parent.querySelector('.upload-dish-image').onclick = function() {
                this.nextElementSibling.click();
            };
            parent.querySelector('.dish-image-upload').onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        parent.querySelector('.dish-image-preview').innerHTML = '<img src="' + ev.target.result + '" style="max-width:100px;">';
                    };
                    reader.readAsDataURL(file);
                }
            };
        }

        // Bind existing items
        document.querySelectorAll('.item-entry').forEach(bindDishUpload);

        // Remove item functionality
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
    </script>
</body>
</html>
