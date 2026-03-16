<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experience Details - Tagum City</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/experience-details.css">
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
            </ul>
        </div>
    </nav>

    <!-- Experience Details Section -->
    <section class="experience-details">
        <div class="details-container">
            <!-- Breadcrumb Navigation -->
            <div class="breadcrumb">
                <a href="../index.php">Home</a> / <a href="../index.php#experiences">Experiences</a> / <span id="breadcrumb-title">Featured Experiences</span>
            </div>

            <!-- Featured Experiences -->
            <div class="experience-detail active" id="featured-experiences">
<?php
$destinations = json_decode(file_get_contents('../assets/data/destinations.json'), true);
$experiences = json_decode(file_get_contents('../assets/data/experiences.json'), true);
                
                // Filter featured destinations
                $featuredDestinations = array_filter($destinations, function($dest) {
                    return isset($dest['featured']) && $dest['featured'] === true;
                });
                
                // Get type icons
                $typeIcons = [
                    'Natural Wonder' => '🏞️',
                    'Adventure' => '⛰️',
                    'Museum' => '🏛️',
                    'Religious' => '⛪',
                    'Festival' => '🎉'
                ];
                
                if (!empty($featuredDestinations)):
                ?>
                <h1>⭐ Featured Experiences</h1>
                <p class="featured-intro">Discover our top-picked experiences that showcase the best of Tagum City. These featured destinations are highly recommended by visitors and offer unforgettable memories.</p>
                
                <div class="featured-experiences-grid">
                    <?php foreach ($featuredDestinations as $dest): 
                        $icon = $typeIcons[$dest['type']] ?? '📍';
                        $linkName = strtolower(str_replace(' ', '-', $dest['name']));
                    ?>
                        <a href="../Plan module/destination.php?destination=<?php echo $linkName; ?>" class="featured-experience-card">
                            <div class="featured-exp-icon"><?php echo $icon; ?></div>
                            <h3><?php echo htmlspecialchars($dest['name']); ?></h3>
                            <p><?php echo htmlspecialchars($dest['description']); ?></p>
                            <span class="featured-exp-cta">View Details →</span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <h1>⭐ Featured Experiences</h1>
                <p>No featured experiences at the moment. Check back soon!</p>
                <?php endif; ?>
            </div>

            <!-- River Tours -->
            <div class="experience-detail" id="river-tours">
                <?php
                $destinations = json_decode(file_get_contents('../assets/data/destinations.json'), true);
                foreach ($destinations as $destination) {
                    if (strpos(strtolower($destination['name']), 'river') !== false) {
                        echo '<h1>' . htmlspecialchars($destination['name']) . '</h1>';
                        echo '<img src="../assets/images/experience-1.jpg" alt="' . htmlspecialchars($destination['name']) . '" class="detail-image">';
                        echo '<div class="detail-content">';
                        echo '<h2>Navigate Pristine Waterways</h2>';
                        echo '<p>' . htmlspecialchars($destination['description']) . '</p>';
                        echo '<h3>Features:</h3>';
                        echo '<ul>';
                        foreach (explode("\n", $destination['features']) as $feature) {
                            echo '<li>' . htmlspecialchars($feature) . '</li>';
                        }
                        echo '</ul>';
                        echo '<h3>Facilities:</h3>';
                        echo '<ul>';
                        foreach (explode("\n", $destination['facilities']) as $facility) {
                            echo '<li>' . htmlspecialchars($facility) . '</li>';
                        }
                        echo '</ul>';
                        echo '<h3>Best Time:</h3>';
                        echo '<p>' . htmlspecialchars($destination['best_time']) . '</p>';
                        echo '<h3>Visiting Rules:</h3>';
                        echo '<ul>';
                        foreach (explode("\n", $destination['visiting_rules']) as $rule) {
                            echo '<li>' . htmlspecialchars($rule) . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <!-- Mountain Hiking -->
            <div class="experience-detail" id="mountain-hiking">
                <?php
                foreach ($destinations as $destination) {
                    if (strpos(strtolower($destination['name']), 'kampalilis') !== false) {
                        echo '<h1>' . htmlspecialchars($destination['name']) . '</h1>';
                        echo '<img src="../assets/images/experience-2.jpg" alt="' . htmlspecialchars($destination['name']) . '" class="detail-image">';
                        echo '<div class="detail-content">';
                        echo '<h2>Trek Through Lush Forests</h2>';
                        echo '<p>' . htmlspecialchars($destination['description']) . '</p>';
                        echo '<h3>Features:</h3>';
                        echo '<ul>';
                        foreach (explode("\n", $destination['features']) as $feature) {
                            echo '<li>' . htmlspecialchars($feature) . '</li>';
                        }
                        echo '</ul>';
                        echo '<h3>Facilities:</h3>';
                        echo '<ul>';
                        foreach (explode("\n", $destination['facilities']) as $facility) {
                            echo '<li>' . htmlspecialchars($facility) . '</li>';
                        }
                        echo '</ul>';
                        echo '<h3>Best Time:</h3>';
                        echo '<p>' . htmlspecialchars($destination['best_time']) . '</p>';
                        echo '<h3>Visiting Rules:</h3>';
                        echo '<ul>';
                        foreach (explode("\n", $destination['visiting_rules']) as $rule) {
                            echo '<li>' . htmlspecialchars($rule) . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <!-- Cultural Events -->
            <div class="experience-detail" id="cultural-events">
                <h1>Cultural Events</h1>
                <img src="../assets/images/experience-3.jpg" alt="Cultural Events" class="detail-image">
                <div class="detail-content">
                    <h2>Celebrate Local Traditions</h2>
                    <p>Immerse yourself in the vibrant cultural celebrations of Tagum City. Participate in traditional festivals, witness authentic performances, and connect with the warm and hospitable local community.</p>
                    
                    <h3>Cultural Experiences:</h3>
                    <ul>
                        <li>Traditional dance and music performances</li>
                        <li>Indigenous craft demonstrations</li>
                        <li>Historical walking tours</li>
                        <li>Community gatherings and celebrations</li>
                        <li>Hands-on workshops with local artisans</li>
                    </ul>

                    <h3>Major Festivals:</h3>
                    <p><strong>Buwan ng Wika Festival:</strong> August - Celebration of Filipino language and culture</p>
                    <p><strong>Christmas Celebrations:</strong> December - Grand parades and holiday festivities</p>
                    <p><strong>Local Fiesta:</strong> Various dates - Community celebrations with food, music, and dancing</p>

                    <h3>Activities Include:</h3>
                    <ul>
                        <li>Participate in festival parades</li>
                        <li>Learn traditional weaving techniques</li>
                        <li>Taste indigenous cuisines</li>
                        <li>Watch traditional performances</li>
                        <li>Interact with local artisans and community leaders</li>
                    </ul>

                    <h3>Schedule & Accessibility:</h3>
                    <p><strong>Frequency:</strong> Throughout the year, seasonal festivals</p>
                    <p><strong>Group Size:</strong> Any size welcome</p>
                    <p><strong>Accessibility:</strong> Family-friendly, wheelchair accessible venues</p>

                    <h3>Cultural Tips:</h3>
                    <ul>
                        <li>Dress respectfully for cultural events</li>
                        <li>Ask permission before photographing people</li>
                        <li>Support local vendors and artisans</li>
                        <li>Learn basic Filipino phrases</li>
                    </ul>
                </div>
            </div>

            <!-- Food Tours -->
            <div class="experience-detail" id="food-tours">
                <?php
                foreach ($destinations as $destination) {
                    if (strpos(strtolower($destination['name']), 'food') !== false) {
                        echo '<h1>' . htmlspecialchars($destination['name']) . '</h1>';
                        echo '<img src="../assets/images/experience-4.jpg" alt="' . htmlspecialchars($destination['name']) . '" class="detail-image">';
                        echo '<div class="detail-content">';
                        echo '<h2>Taste Authentic Local Flavors</h2>';
                        echo '<p>' . htmlspecialchars($destination['description']) . '</p>';
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <!-- Dynamically Loaded Experiences from Admin -->
            <?php if (!empty($experiences)): ?>
                <?php 
                // Sort experiences: featured ones first, then by date
                usort($experiences, function($a, $b) {
                    $aFeatured = isset($a['featured']) && $a['featured'] === true;
                    $bFeatured = isset($b['featured']) && $b['featured'] === true;
                    if ($aFeatured === $bFeatured) {
                        return 0;
                    }
                    return $aFeatured ? -1 : 1;
                });
                ?>
                <?php foreach ($experiences as $exp): ?>
                    <?php 
                    $expType = strtolower(str_replace(' ', '-', $exp['type'] ?? 'experience'));
                    ?>
                    <div class="experience-detail" id="<?php echo htmlspecialchars($expType); ?>-<?php echo $exp['id']; ?>">
                        <h1><?php echo isset($exp['featured']) && $exp['featured'] === true ? '⭐ ' : ''; ?><?php echo htmlspecialchars($exp['name']); ?></h1>
                        <?php if (!empty($exp['image'])): ?>
                            <img src="<?php echo htmlspecialchars($exp['image']); ?>" alt="<?php echo htmlspecialchars($exp['name']); ?>" class="detail-image">
                        <?php else: ?>
                            <img src="../assets/images/experience-default.jpg" alt="<?php echo htmlspecialchars($exp['name']); ?>" class="detail-image">
                        <?php endif; ?>
                        <div class="detail-content">
                            <p class="exp-date">📅 Date: <?php echo htmlspecialchars($exp['date'] ?? 'TBA'); ?></p>
                            <p class="exp-type">Type: <?php echo htmlspecialchars($exp['type'] ?? 'General'); ?></p>
                            <p><?php echo htmlspecialchars($exp['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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

    <script src="../assets/js/experience-details.js"></script>
</body>
</html>
