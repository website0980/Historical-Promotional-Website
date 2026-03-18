<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Tagum City</title>
    <link rel="stylesheet" href="../assets/css/explore-style.css">
    <?php
    // Load data from JSON files
    $naturalWonders = json_decode(file_get_contents(__DIR__ . '/../assets/data/natural-wonders.json'), true) ?? [];
    $culturalSites = json_decode(file_get_contents(__DIR__ . '/../assets/data/cultural-sites.json'), true) ?? [];
    $festivals = json_decode(file_get_contents(__DIR__ . '/../assets/data/festivals.json'), true) ?? [];
    ?>
</head>
<body>
    <!-- Explore Section -->
    <section class="explore" id="explore">
        <h2>Explore Tagum City</h2>
        <div class="explore-grid">
            <!-- Cultural Sites Card -->            <div class="explore-card">                <div class="card-image">🏛️</div>                <h3>Cultural Sites</h3>
                <p>Experience the rich history and cultural heritage of our community.</p>
                <button class="learn-more-btn" data-card="cultural-sites">Learn More →</button>
            </div>

            <!-- Festivals Card -->
            <div class="explore-card">
                <div class="card-image">🎉</div>
                <h3>Festivals</h3>
                <p>Celebrate vibrant festivals and cultural events throughout the year.</p>
                <button class="learn-more-btn" data-card="festivals">Learn More →</button>
            </div>

            <!-- Local Cuisine Card -->
            <div class="explore-card">
                <div class="card-image">🍽️</div>
                <h3>Local Cuisine</h3>
                <p>Savor authentic dishes and culinary traditions of Tagum.</p>
                <button class="learn-more-btn" data-card="local-cuisine">Learn More →</button>
            </div>
        </div>
    </section>

    <!-- Sliding Sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar" id="sidebar">
        <button class="sidebar-close" id="closeSidebar">✕</button>
        
        <!-- Natural Wonders Content -->
        <div class="sidebar-content" data-content="natural-wonders">
            <h2>Natural Wonders</h2>
            <?php if (!empty($naturalWonders)): ?>
                <?php foreach ($naturalWonders as $wonder): ?>
                    <?php if (!empty($wonder['image'])): ?>
                        <img src="<?php echo htmlspecialchars($wonder['image']); ?>" alt="<?php echo htmlspecialchars($wonder['name']); ?>" class="sidebar-image">
                    <?php endif; ?>
                    <div class="content-text">
                        <h3><?php echo htmlspecialchars($wonder['name']); ?></h3>
                        <p><?php echo htmlspecialchars($wonder['description'] ?? ''); ?></p>
                        
                        <?php if (!empty($wonder['location'])): ?>
                            <h4>Location:</h4>
                            <p><?php echo htmlspecialchars($wonder['location']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($wonder['features'])): ?>
                            <h4>Features:</h4>
                            <p><?php echo htmlspecialchars($wonder['features']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($wonder['best_time'])): ?>
                            <h4>Best Time to Visit:</h4>
                            <p><?php echo htmlspecialchars($wonder['best_time']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <img src="../assets/images/natural-wonders.jpg" alt="Natural Wonders" class="sidebar-image">
                <div class="content-text">
                    <h3>Discover Breathtaking Landscapes</h3>
                    <p>Tagum City is blessed with some of the most pristine and breathtaking natural landscapes in the region.</p>
            <?php endif; ?>
            
            <a href="#" class="sidebar-cta">Book a Nature Tour</a>
        </div>

        <!-- Cultural Sites Content -->
        <div class="sidebar-content" data-content="cultural-sites">
            <h2>Cultural Sites</h2>
            <?php if (!empty($culturalSites)): ?>
                <?php foreach ($culturalSites as $site): ?>
                    <?php if (!empty($site['image'])): ?>
                        <img src="<?php echo htmlspecialchars($site['image']); ?>" alt="<?php echo htmlspecialchars($site['name']); ?>" class="sidebar-image">
                    <?php endif; ?>
                    <div class="content-text">
                        <h3><?php echo htmlspecialchars($site['name']); ?></h3>
                        <p><?php echo htmlspecialchars($site['description'] ?? ''); ?></p>
                        
                        <?php if (!empty($site['location'])): ?>
                            <h4>Location:</h4>
                            <p><?php echo htmlspecialchars($site['location']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($site['history'])): ?>
                            <h4>History:</h4>
                            <p><?php echo htmlspecialchars($site['history']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($site['highlights'])): ?>
                            <h4>Highlights:</h4>
                            <p><?php echo htmlspecialchars($site['highlights']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <img src="../assets/images/cultural-sites.jpg" alt="Cultural Sites" class="sidebar-image">
                <div class="content-text">
                    <h3>Experience Rich Heritage</h3>
                    <p>Tagum City has a vibrant cultural heritage influenced by indigenous traditions and colonial history.</p>
            <?php endif; ?>
            
            <a href="#" class="sidebar-cta">Plan a Cultural Tour</a>
        </div>

        <!-- Festivals Content -->
        <div class="sidebar-content" data-content="festivals">
            <h2>Festivals</h2>
            <?php if (!empty($festivals)): ?>
                <?php foreach ($festivals as $festival): ?>
                    <?php if (!empty($festival['image'])): ?>
                        <img src="<?php echo htmlspecialchars($festival['image']); ?>" alt="<?php echo htmlspecialchars($festival['name']); ?>" class="sidebar-image">
                    <?php endif; ?>
                    <div class="content-text">
                        <h3><?php echo htmlspecialchars($festival['name']); ?></h3>
                        <p><?php echo htmlspecialchars($festival['description'] ?? ''); ?></p>
                        
                        <?php if (!empty($festival['date'])): ?>
                            <h4>Date:</h4>
                            <p><?php echo htmlspecialchars($festival['date']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($festival['highlights'])): ?>
                            <h4>Highlights:</h4>
                            <p><?php echo htmlspecialchars($festival['highlights']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($festival['activities'])): ?>
                            <h4>Activities:</h4>
                            <p><?php echo htmlspecialchars($festival['activities']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <img src="../assets/images/festivals.jpg" alt="Festivals" class="sidebar-image">
                <div class="content-text">
                    <h3>Celebrate Our Culture</h3>
                    <p>Tagum City hosts vibrant festivals throughout the year that showcase the rich cultural heritage of our community.</p>
            <?php endif; ?>
            
            <a href="#" class="sidebar-cta">Join a Festival</a>
        </div>

        <!-- Local Cuisine Content -->
        <div class="sidebar-content" data-content="local-cuisine">
            <h2>Local Cuisine</h2>
            <img src="../assets/images/local-cuisine.jpg" alt="Local Cuisine" class="sidebar-image">
            <div class="content-text">
                <h3>Savor Authentic Flavors</h3>
                <p>Tagum's culinary scene is a delicious blend of traditional Filipino recipes, indigenous flavors, and modern cooking techniques. Discover the tastes that define our community.</p>
                
<h4>Food Categories:</h4>
                <?php
                $cuisineData = json_decode(file_get_contents('../assets/data/cuisine.json'), true);
                if ($cuisineData) {
                    foreach ($cuisineData as $category) {
                        echo '<div class="cuisine-category">';
                        echo '<h5>' . htmlspecialchars($category['category']) . '</h5>';
                        echo '<p>' . htmlspecialchars($category['description']) . '</p>';
                        echo '<button class="toggle-items-btn">View Details</button>';
                        echo '<ul class="items-list" style="display:none;">';
                        foreach ($category['items'] as $item) {
                            echo '<li><strong>' . htmlspecialchars($item['name']) . '</strong> - ' . htmlspecialchars($item['description']) . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No cuisine data available.</p>';
                }
                ?>


                <h4>Where to Eat:</h4>
                <ul>
                    <li>Traditional family-owned restaurants</li>
                    <li>Street food markets and night bazaars</li>
                    <li>Farm-to-table dining experiences</li>
                    <li>Local cooking classes and food tours</li>
                </ul>

                <h4>Food Festivals:</h4>
                <p>Join us for food festivals celebrating Tagum's culinary heritage. Sample dishes from local chefs and learn traditional cooking methods.</p>

                <a href="#" class="sidebar-cta">Book a Food Tour</a>
            </div>
        </div>
    </aside>

    <script src="../assets/js/explore-script.js"></script>
</body>
</html>
