<?php
// Start session at the very beginning
session_start();

// Load destinations from JSON file
$destinationsFile = dirname(__DIR__) . '/assets/data/destinations.json';
$destinations = [];

if (file_exists($destinationsFile)) {
    $json = file_get_contents($destinationsFile);
    $destinations = json_decode($json, true) ?? [];
}

// Get destination from URL parameter
$destinationParam = $_GET['destination'] ?? null;
$selectedDestination = null;
$selectedIndex = 0;

// Find the selected destination
if ($destinationParam !== null && !empty($destinations)) {
    foreach ($destinations as $index => $dest) {
        // Match by name (URL-friendly format)
        $friendlyName = strtolower(str_replace(' ', '-', $dest['name']));
        if ($friendlyName === strtolower($destinationParam)) {
            $selectedDestination = $dest;
            $selectedIndex = $index;
            break;
        }
    }
}

// Default to first destination if not found
if ($selectedDestination === null && !empty($destinations)) {
    $selectedDestination = $destinations[0];
}

// If no destinations exist, show empty state
if (empty($destinations)) {
    $selectedDestination = [
        'name' => 'No Destinations',
        'description' => 'No destinations have been added yet. Please check back soon!',
        'location' => '',
        'accessibility' => '',
        'features' => '',
        'facilities' => '',
        'entrance_fee' => '',
        'contact' => '',
        'best_time' => '',
        'what_to_pack' => '',
        'visiting_rules' => '',
        'image' => '',
        'featured' => false
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($selectedDestination['name'] ?? 'Destination'); ?> - Tagum City</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/plan-details.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
<img src="../assets/images/City of Tagum Logo.png" alt="Tagum City" class="logo-img">
                <span class="logo-text">Tagum City</span>
            </div>
            <ul class="nav-menu">
                <li><a href="../index.php#home" class="nav-link">Home</a></li>
                <li><a href="../index.php#explore" class="nav-link">Explore</a></li>
                <li><a href="../index.php#experiences" class="nav-link">Experiences</a></li>
                <li><a href="../index.php#plan" class="nav-link">Plan</a></li>
                <li><a href="../Admin module/login.php" class="nav-link admin-nav-link">Admin</a></li>
            </ul>
        </div>
    </nav>

    <!-- Plan Details Section -->
    <section class="plan-details">
        <div class="plan-container">
            <!-- Breadcrumb Navigation -->
            <div class="breadcrumb">
                <a href="../index.php">Home</a> / <a href="../index.php#plan">Plan</a> / <span id="breadcrumb-title"><?php echo htmlspecialchars($selectedDestination['name'] ?? 'Destination'); ?></span>
            </div>

            <!-- Destination Selector -->
            <?php if (!empty($destinations)): ?>
                <div class="destination-selector">
                    <label for="destination-select">Choose a destination:</label>
                    <select id="destination-select" onchange="navigateToDestination(this.value)">
                        <option value="">All Destinations</option>
                        <optgroup label="⭐ Featured">
                        <?php foreach ($destinations as $dest): ?>
                            <?php if (isset($dest['featured']) && $dest['featured'] === true): ?>
                                <option value="<?php echo strtolower(str_replace(' ', '-', $dest['name'])); ?>" <?php echo ($dest['name'] === $selectedDestination['name']) ? 'selected' : ''; ?>>
                                    ⭐ <?php echo htmlspecialchars($dest['name']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="All Destinations">
                        <?php foreach ($destinations as $dest): ?>
                            <option value="<?php echo strtolower(str_replace(' ', '-', $dest['name'])); ?>" <?php echo ($dest['name'] === $selectedDestination['name']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dest['name']); ?>
                            </option>
                        <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Destination Detail -->
            <div class="destination-detail active">
                <h1><?php echo htmlspecialchars($selectedDestination['name']); ?></h1>
                <?php
                if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true):
                ?>
                <?php endif; ?>
                
                <?php if (!empty($selectedDestination['image'])): ?>
                    <img src="<?php echo htmlspecialchars($selectedDestination['image']); ?>" alt="<?php echo htmlspecialchars($selectedDestination['name']); ?>" class="destination-image">
                <?php endif; ?>
                
                <div class="destination-content">
                    <?php if (!empty($selectedDestination['description'])): ?>
                        <h2><?php echo htmlspecialchars($selectedDestination['description']); ?></h2>
                    <?php endif; ?>

                    <?php if (!empty($selectedDestination['location']) || !empty($selectedDestination['accessibility']) || !empty($selectedDestination['features']) || !empty($selectedDestination['facilities']) || !empty($selectedDestination['contact'])): ?>
                        <h3>📖 Destination Guide</h3>
                        <?php if (!empty($selectedDestination['location'])): ?>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($selectedDestination['location']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($selectedDestination['accessibility'])): ?>
                            <p><strong>Accessibility:</strong> <?php echo htmlspecialchars($selectedDestination['accessibility']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($selectedDestination['features'])): ?>
                            <p><strong>Main Features:</strong></p>
                            <ul>
                                <?php foreach (explode("\n", $selectedDestination['features']) as $feature): ?>
                                    <?php if (trim($feature)): ?>
                                        <li><?php echo htmlspecialchars(trim($feature)); ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <?php if (!empty($selectedDestination['facilities'])): ?>
                            <p><strong>Facilities:</strong></p>
                            <ul>
                                <?php foreach (explode("\n", $selectedDestination['facilities']) as $facility): ?>
                                    <?php if (trim($facility)): ?>
                                        <li><?php echo htmlspecialchars(trim($facility)); ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <?php if (!empty($selectedDestination['entrance_fee'])): ?>
                            <p><strong>Entrance Fee:</strong> <?php echo htmlspecialchars($selectedDestination['entrance_fee']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($selectedDestination['contact'])): ?>
                            <p><strong>Contact:</strong> <?php echo htmlspecialchars($selectedDestination['contact']); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2026 Tagum City. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact Us</a>
            </div>
        </div>
    </footer>

    <script>
        function navigateToDestination(destinationName) {
            window.location.href = '?destination=' + encodeURIComponent(destinationName);
        }
    </script>
</body>
</html>
