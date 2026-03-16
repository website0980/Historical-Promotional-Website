<?php
// Admin Dashboard
require_once 'config.php';
requireAuth();

$destinations = loadDestinations();
$experiences = loadExperiences();
$cuisines = loadCuisine();
$naturalWonders = loadNaturalWonders();
$culturalSites = loadCulturalSites();
$festivals = loadFestivals();
$message = '';
$messageType = '';

// Get current tab
$currentTab = $_GET['tab'] ?? 'destinations';

// Handle destination delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && $currentTab === 'destinations') {
    $id = $_POST['id'] ?? null;
    
    if ($id !== null && isset($destinations[$id])) {
        // Delete image if exists
        if (!empty($destinations[$id]['image'])) {
            deleteImage($destinations[$id]['image']);
        }
        unset($destinations[$id]);
        // Re-index array
        $destinations = array_values($destinations);
        saveDestinations($destinations);
        $message = 'Destination deleted successfully!';
        $messageType = 'success';
    }
}

// Handle destination featured toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle-featured' && $currentTab === 'destinations') {
    $id = $_POST['id'] ?? null;
    
    if ($id !== null && isset($destinations[$id])) {
        $destinations[$id]['featured'] = !($destinations[$id]['featured'] ?? false);
        saveDestinations($destinations);
        $message = 'Featured status updated!';
        $messageType = 'success';
    }
}

// Handle experience delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete-experience') {
    $id = $_POST['id'] ?? null;
    
    if ($id !== null && isset($experiences[$id])) {
        // Delete image if exists
        if (!empty($experiences[$id]['image'])) {
            deleteExperienceImage(basename($experiences[$id]['image']));
        }
        unset($experiences[$id]);
        // Re-index array
        $experiences = array_values($experiences);
        saveExperiences($experiences);
        $message = 'Experience deleted successfully!';
        $messageType = 'success';
    }
}

// Handle experience featured toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle-featured-experience') {
    $id = $_POST['id'] ?? null;
    
    if ($id !== null && isset($experiences[$id])) {
        $experiences[$id]['featured'] = !($experiences[$id]['featured'] ?? false);
        saveExperiences($experiences);
        $message = 'Featured status updated!';
        $messageType = 'success';
    }
}

// Handle cuisine delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete-cuisine') {
    $id = $_POST['id'] ?? null;
    
    if ($id !== null && isset($cuisines[$id])) {
        // Delete category image if exists
        if (!empty($cuisines[$id]['image'])) {
            deleteCuisineImage(basename($cuisines[$id]['image']));
        }
        // Delete item images
        if (!empty($cuisines[$id]['items'])) {
            foreach ($cuisines[$id]['items'] as $item) {
                if (!empty($item['image'])) {
                    deleteCuisineImage(basename($item['image']));
                }
            }
        }
        unset($cuisines[$id]);
        // Re-index array
        $cuisines = array_values($cuisines);
        saveCuisine($cuisines);
        $message = 'Cuisine category deleted successfully!';
        $messageType = 'success';
    }
}

// Handle natural wonders delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete-natural-wonder') {
    $id = $_POST['id'] ?? null;
    
    if ($id !== null && isset($naturalWonders[$id])) {
        // Delete image if exists
        if (!empty($naturalWonders[$id]['image'])) {
            deleteNaturalWonderImage(basename($naturalWonders[$id]['image']));
        }
        unset($naturalWonders[$id]);
        // Re-index array
        $naturalWonders = array_values($naturalWonders);
        saveNaturalWonders($naturalWonders);
        $message = 'Natural Wonder deleted successfully!';
        $messageType = 'success';
    }
}

// Handle cultural sites delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete-cultural-site') {
    $id = $_POST['id'] ?? null;
    
    if ($id !== null && isset($culturalSites[$id])) {
        // Delete image if exists
        if (!empty($culturalSites[$id]['image'])) {
            deleteCulturalSiteImage(basename($culturalSites[$id]['image']));
        }
        unset($culturalSites[$id]);
        // Re-index array
        $culturalSites = array_values($culturalSites);
        saveCulturalSites($culturalSites);
        $message = 'Cultural Site deleted successfully!';
        $messageType = 'success';
    }
}

// Handle festivals delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete-festival') {
    $id = $_POST['id'] ?? null;
    
    if ($id !== null && isset($festivals[$id])) {
        // Delete image if exists
        if (!empty($festivals[$id]['image'])) {
            deleteFestivalImage(basename($festivals[$id]['image']));
        }
        unset($festivals[$id]);
        // Re-index array
        $festivals = array_values($festivals);
        saveFestivals($festivals);
        $message = 'Festival deleted successfully!';
        $messageType = 'success';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tagum City</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-title">
                <img src="../assets/images/City of Tagum Logo.png" alt="Tagum City Logo" class="admin-logo">
                <span>Tagum Admin Dashboard</span>
            </div>
            <div class="admin-nav">
                <span class="admin-user">Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
                <a href="logout.php" class="btn btn-primary tab-btn logout-btn">Logout</a>
            </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-container">
            <!-- Messages -->
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Tab Navigation Buttons -->
            <div class="tab-buttons">
                <a href="?tab=destinations" class="btn btn-primary tab-btn <?php echo $currentTab === 'destinations' ? 'active' : ''; ?>">Destinations</a>
                <a href="?tab=experiences" class="btn btn-primary tab-btn <?php echo $currentTab === 'experiences' ? 'active' : ''; ?>">Experiences</a>
                <a href="?tab=cuisine" class="btn btn-primary tab-btn <?php echo $currentTab === 'cuisine' ? 'active' : ''; ?>">Cuisine</a>
                <a href="?tab=natural-wonders" class="btn btn-primary tab-btn <?php echo $currentTab === 'natural-wonders' ? 'active' : ''; ?>">Natural Wonders</a>
                <a href="?tab=cultural-sites" class="btn btn-primary tab-btn <?php echo $currentTab === 'cultural-sites' ? 'active' : ''; ?>">Cultural Sites</a>
                <a href="?tab=festivals" class="btn btn-primary tab-btn <?php echo $currentTab === 'festivals' ? 'active' : ''; ?>">Festivals</a>
            </div>

            <!-- Destinations Section -->
            <?php if ($currentTab === 'destinations'): ?>
                <div class="dashboard-header">
                    <h2>Manage Destinations</h2>
                    <a href="add-destination.php" class="btn btn-primary">+ Add New Destination</a>
                </div>

                <div class="table-responsive">
                    <?php if (empty($destinations)): ?>
                        <div class="empty-state">
                            <p>📭 No destinations found</p>
                            <a href="add-destination.php" class="btn btn-primary">Add your first destination</a>
                        </div>
                    <?php else: ?>
                        <table class="destinations-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Featured</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($destinations as $index => $destination): ?>
                                    <tr>
                                        <td class="table-image">
                                            <?php if (!empty($destination['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($destination['image']); ?>" alt="<?php echo htmlspecialchars($destination['name']); ?>">
                                            <?php else: ?>
                                                <span class="no-image">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($destination['name']); ?></strong>
                                        </td>
                                        <td>
                                            <span class="type-badge"><?php echo htmlspecialchars($destination['type'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="toggle-featured">
                                                <input type="hidden" name="id" value="<?php echo $index; ?>">
                                                <button type="submit" class="featured-btn <?php echo ($destination['featured'] ?? false) ? 'active' : ''; ?>">
                                                    <span>⭐</span> <?php echo ($destination['featured'] ?? false) ? 'Featured' : 'Not Featured'; ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="add-destination.php?id=<?php echo $index; ?>" class="btn btn-small btn-edit">Edit</a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this destination?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $index; ?>">
                                                <button type="submit" class="btn btn-small btn-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($destinations); ?></div>
                        <div class="stat-label">Total Destinations</div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count(array_filter($destinations, fn($d) => $d['featured'] ?? false)); ?></div>
                        <div class="stat-label">Featured Destinations</div>
                </div>
            <?php endif; ?>

            <!-- Experiences Section -->
            <?php if ($currentTab === 'experiences'): ?>
                <div class="dashboard-header">
                    <h2>Manage Experiences</h2>
                    <a href="add-experience.php" class="btn btn-primary">+ Add New Experience</a>
                </div>

                <div class="table-responsive">
                    <?php if (empty($experiences)): ?>
                        <div class="empty-state">
                            <p>📭 No experiences found</p>
                            <a href="add-experience.php" class="btn btn-primary">Add your first experience</a>
                        </div>
                    <?php else: ?>
                        <table class="destinations-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Featured</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($experiences as $index => $experience): ?>
                                    <tr>
                                        <td class="table-image">
                                            <?php if (!empty($experience['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($experience['image']); ?>" alt="<?php echo htmlspecialchars($experience['name']); ?>">
                                            <?php else: ?>
                                                <span class="no-image">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($experience['name']); ?></strong>
                                        </td>
                                        <td>
                                            <span class="type-badge"><?php echo htmlspecialchars($experience['type'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($experience['date'] ?? 'N/A'); ?>
                                        </td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="toggle-featured-experience">
                                                <input type="hidden" name="id" value="<?php echo $index; ?>">
                                                <button type="submit" class="featured-btn <?php echo ($experience['featured'] ?? false) ? 'active' : ''; ?>">
                                                    <span>⭐</span> <?php echo ($experience['featured'] ?? false) ? 'Featured' : 'Not Featured'; ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="add-experience.php?id=<?php echo $index; ?>" class="btn btn-small btn-edit">Edit</a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this experience?');">
                                                <input type="hidden" name="action" value="delete-experience">
                                                <input type="hidden" name="id" value="<?php echo $index; ?>">
                                                <button type="submit" class="btn btn-small btn-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($experiences); ?></div>
                        <div class="stat-label">Total Experiences</div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count(array_filter($experiences, fn($e) => $e['featured'] ?? false)); ?></div>
                        <div class="stat-label">Featured Experiences</div>
                </div>
            <?php endif; ?>

            <!-- Cuisine Section -->
            <?php if ($currentTab === 'cuisine'): ?>
                <div class="dashboard-header">
                    <h2>Manage Cuisine Categories</h2>
                    <a href="add-cuisine.php" class="btn btn-primary">+ Add New Category</a>
                </div>

                <div class="table-responsive">
                    <?php if (empty($cuisines)): ?>
                        <div class="empty-state">
                            <p>🍽️ No cuisine categories found</p>
                            <a href="add-cuisine.php" class="btn btn-primary">Add your first category</a>
                        </div>
                    <?php else: ?>
                        <table class="destinations-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Dishes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cuisines as $index => $cuisine): ?>
                                    <tr>
                                        <td class="table-image">
                                            <?php if (!empty($cuisine['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($cuisine['image']); ?>" alt="<?php echo htmlspecialchars($cuisine['category']); ?>">
                                            <?php else: ?>
                                                <span class="no-image">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($cuisine['category']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars(substr($cuisine['description'] ?? '', 0, 100)); ?><?php echo strlen($cuisine['description'] ?? '') > 100 ? '...' : ''; ?>
                                        </td>
                                        <td>
                                            <span class="type-badge"><?php echo count($cuisine['items'] ?? []); ?> dishes</span>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="add-cuisine.php?id=<?php echo $index; ?>" class="btn btn-small btn-edit">Edit</a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                <input type="hidden" name="action" value="delete-cuisine">
                                                <input type="hidden" name="id" value="<?php echo $index; ?>">
                                                <button type="submit" class="btn btn-small btn-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($cuisines); ?></div>
                        <div class="stat-label">Total Categories</div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo array_sum(array_map('count', array_column($cuisines, 'items'))); ?></div>
                        <div class="stat-label">Total Dishes</div>
                </div>
            <?php endif; ?>

            <!-- Natural Wonders Section -->
            <?php if ($currentTab === 'natural-wonders'): ?>
                <div class="dashboard-header">
                    <h2>Manage Natural Wonders</h2>
                    <a href="add-natural-wonder.php" class="btn btn-primary">+ Add New Natural Wonder</a>
                </div>

                <div class="table-responsive">
                    <?php if (empty($naturalWonders)): ?>
                        <div class="empty-state">
                            <p>🏞️ No natural wonders found</p>
                            <a href="add-natural-wonder.php" class="btn btn-primary">Add your first natural wonder</a>
                        </div>
                    <?php else: ?>
                        <table class="destinations-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Best Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($naturalWonders as $index => $wonder): ?>
                                    <tr>
                                        <td class="table-image">
                                            <?php if (!empty($wonder['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($wonder['image']); ?>" alt="<?php echo htmlspecialchars($wonder['name']); ?>">
                                            <?php else: ?>
                                                <span class="no-image">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($wonder['name']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($wonder['location'] ?? 'N/A'); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($wonder['best_time'] ?? 'N/A'); ?>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="add-natural-wonder.php?edit=<?php echo $index; ?>" class="btn btn-small btn-edit">Edit</a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this natural wonder?');">
                                                <input type="hidden" name="action" value="delete-natural-wonder">
                                                <input type="hidden" name="id" value="<?php echo $index; ?>">
                                                <button type="submit" class="btn btn-small btn-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($naturalWonders); ?></div>
                        <div class="stat-label">Total Natural Wonders</div>
                </div>
            <?php endif; ?>

            <!-- Cultural Sites Section -->
            <?php if ($currentTab === 'cultural-sites'): ?>
                <div class="dashboard-header">
                    <h2>Manage Cultural Sites</h2>
                    <a href="add-cultural-site.php" class="btn btn-primary">+ Add New Cultural Site</a>
                </div>

                <div class="table-responsive">
                    <?php if (empty($culturalSites)): ?>
                        <div class="empty-state">
                            <p>🏛️ No cultural sites found</p>
                            <a href="add-cultural-site.php" class="btn btn-primary">Add your first cultural site</a>
                        </div>
                    <?php else: ?>
                        <table class="destinations-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>History</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($culturalSites as $index => $site): ?>
                                    <tr>
                                        <td class="table-image">
                                            <?php if (!empty($site['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($site['image']); ?>" alt="<?php echo htmlspecialchars($site['name']); ?>">
                                            <?php else: ?>
                                                <span class="no-image">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($site['name']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($site['location'] ?? 'N/A'); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars(substr($site['history'] ?? '', 0, 50)); ?><?php echo strlen($site['history'] ?? '') > 50 ? '...' : ''; ?>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="add-cultural-site.php?edit=<?php echo $index; ?>" class="btn btn-small btn-edit">Edit</a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this cultural site?');">
                                                <input type="hidden" name="action" value="delete-cultural-site">
                                                <input type="hidden" name="id" value="<?php echo $index; ?>">
                                                <button type="submit" class="btn btn-small btn-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($culturalSites); ?></div>
                        <div class="stat-label">Total Cultural Sites</div>
                </div>
            <?php endif; ?>

            <!-- Festivals Section -->
            <?php if ($currentTab === 'festivals'): ?>
                <div class="dashboard-header">
                    <h2>Manage Festivals</h2>
                    <a href="add-festival.php" class="btn btn-primary">+ Add New Festival</a>
                </div>

                <div class="table-responsive">
                    <?php if (empty($festivals)): ?>
                        <div class="empty-state">
                            <p>🎉 No festivals found</p>
                            <a href="add-festival.php" class="btn btn-primary">Add your first festival</a>
                        </div>
                    <?php else: ?>
                        <table class="destinations-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Highlights</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($festivals as $index => $festival): ?>
                                    <tr>
                                        <td class="table-image">
                                            <?php if (!empty($festival['image'])): ?>
                                                <img src="<?php echo htmlspecialchars($festival['image']); ?>" alt="<?php echo htmlspecialchars($festival['name']); ?>">
                                            <?php else: ?>
                                                <span class="no-image">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($festival['name']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($festival['date'] ?? 'N/A'); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars(substr($festival['highlights'] ?? '', 0, 50)); ?><?php echo strlen($festival['highlights'] ?? '') > 50 ? '...' : ''; ?>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="add-festival.php?edit=<?php echo $index; ?>" class="btn btn-small btn-edit">Edit</a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this festival?');">
                                                <input type="hidden" name="action" value="delete-festival">
                                                <input type="hidden" name="id" value="<?php echo $index; ?>">
                                                <button type="submit" class="btn btn-small btn-delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($festivals); ?></div>
                        <div class="stat-label">Total Festivals</div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="admin-footer">
        <p>&copy; 2026 Tagum City Admin. All rights reserved.</p>
    </footer>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
