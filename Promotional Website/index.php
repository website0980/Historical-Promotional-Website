<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagum City - Discover Natural Beauty</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
<img src="assets/images/City of Tagum Logo.png" alt="Tagum City" class="logo-img">
                <span class="logo-text">Tagum City</span>
            </div>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#explore" class="nav-link">Explore</a></li>
                <li><a href="#experiences" class="nav-link">Experiences</a></li>
                <li><a href="#plan" class="nav-link">Plan</a></li>
<li><a href="admin/login.php" class="nav-link admin-nav-link">Admin</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Carousel Section -->
    <section class="hero-carousel" id="home">
        <div class="carousel-container">
            <!-- Slide 1 -->
            <div class="carousel-slide active" style="background-image: url('assets/images/hero-1.jpg');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <p class="slide-tagline">Lush landscapes and serene waterways</p>
                    <h1 class="slide-title">Discover<br>Natural Beauty</h1>
                    <p class="slide-description">
                        Explore the pristine rivers, waterfalls, and verdant forests that make Tagum a paradise for nature lovers. Experience the tranquility and magnificent scenery that our city has to offer.
                    </p>
                    <div class="button-group">
                        <a href="#plan" class="btn btn-primary smooth-scroll">Explore Now</a>
                        <a href="#explore" class="btn btn-secondary smooth-scroll">Learn More</a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-slide" style="background-image: url('assets/images/hero-2.jpg');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <p class="slide-tagline">Cultural heritage meets modern charm</p>
                    <h1 class="slide-title">Experience<br>Local Culture</h1>
                    <p class="slide-description">
                        Immerse yourself in the vibrant traditions, local cuisine, and warm hospitality of Tagum City. Discover authentic experiences that celebrate our rich heritage.
                    </p>
                    <div class="button-group">
                        <a href="#plan" class="btn btn-primary smooth-scroll">Explore Now</a>
                        <a href="#explore" class="btn btn-secondary smooth-scroll">Learn More</a>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-slide" style="background-image: url('assets/images/hero-3.jpg');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <p class="slide-tagline">Adventure awaits at every corner</p>
                    <h1 class="slide-title">Thrilling<br>Adventures</h1>
                    <p class="slide-description">
                        From mountain trails to river expeditions, Tagum offers endless opportunities for adventure. Create unforgettable memories in our stunning natural landscape.
                    </p>
                    <div class="button-group">
                        <a href="#plan" class="btn btn-primary smooth-scroll">Explore Now</a>
                        <a href="#explore" class="btn btn-secondary smooth-scroll">Learn More</a>
                    </div>

                </div>
            </div>

            <!-- Navigation Arrows -->
            <button class="carousel-btn carousel-btn-prev" onclick="changeSlide(-1)">❮</button>
            <button class="carousel-btn carousel-btn-next" onclick="changeSlide(1)">❯</button>
        </div>

        <!-- Carousel Dots -->
        <div class="carousel-dots">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
    </section>

    <!-- Explore Section -->
    <section class="explore" id="explore">
        <h2>Explore Tagum City</h2>
        <div class="explore-grid">
            <!-- Natural Wonders Card -->
            <div class="explore-card">
                <div class="card-image">🏞️</div>
                <h3>Natural Wonders</h3>
                <p>Discover breathtaking landscapes and pristine natural attractions.</p>
                <a href="Explore module/explore.php?section=natural-wonders" class="card-link">Learn More →</a>
            </div>

            <!-- Cultural Sites Card -->
            <div class="explore-card">
                <div class="card-image">🏛️</div>
                <h3>Cultural Sites</h3>
                <p>Experience the rich history and cultural heritage of our community.</p>
                <a href="Explore module/explore.php?section=cultural-sites" class="card-link">Learn More →</a>
            </div>

            <!-- Local Cuisine Card -->
            <div class="explore-card">
                <div class="card-image">🍽️</div>
                <h3>Local Cuisine</h3>
                <p>Savor authentic dishes and culinary traditions of Tagum.</p>
                <a href="Explore module/explore.php?section=local-cuisine" class="card-link">Learn More →</a>
            </div>
            
            <!-- Festivals Card -->
            <div class="explore-card">
                <div class="card-image">🎉</div>
                <h3>Festivals</h3>
                <p>Join vibrant local festivals showcasing music, dance, and culture.</p>
                <a href="Explore module/explore.php?section=festivals" class="card-link">Learn More →</a>
            </div>
        </div>
    </section>

    <!-- Experiences Section -->
    <section class="experiences" id="experiences">
        <h2>Unforgettable Experiences</h2>
        <div class="experiences-grid">
            <?php
            $experiencesFile = 'assets/data/experiences.json';
            $experiences = [];
            if (file_exists($experiencesFile)) {
                $json = file_get_contents($experiencesFile);
                $experiences = json_decode($json, true) ?? [];
            }
            
            // Sort experiences: featured ones first
            usort($experiences, function($a, $b) {
                $aFeatured = isset($a['featured']) && $a['featured'] === true;
                $bFeatured = isset($b['featured']) && $b['featured'] === true;
                if ($aFeatured === $bFeatured) {
                    return 0;
                }
                return $aFeatured ? -1 : 1;
            });
            
            // Display experiences (limit to 8 for the grid)
            $displayExperiences = array_slice($experiences, 0, 8);
            
            foreach ($displayExperiences as $exp):
                $expType = $exp['type'] ?? 'experience';
            ?>
                <a href="Experience module/experience.php?id=<?php echo $exp['id']; ?>" class="experience-item">
                    <?php if (!empty($exp['image'])): ?>
                        <img src="<?php echo htmlspecialchars($exp['image']); ?>" alt="<?php echo htmlspecialchars($exp['name']); ?>">
                    <?php else: ?>
                        <img src="assets/images/experience-default.jpg" alt="<?php echo htmlspecialchars($exp['name']); ?>">
                    <?php endif; ?>
                    <h3><?php echo isset($exp['featured']) && $exp['featured'] === true ? '⭐ ' : ''; ?><?php echo htmlspecialchars($exp['name']); ?></h3>
                    <p><?php echo htmlspecialchars($exp['description']); ?></p>
                    <span class="experience-cta">View Details →</span>
                </a>
            <?php endforeach; ?>
            
            <?php if (empty($displayExperiences)): ?>
            <a href="Experience module/experience.php?type=river-tours" class="experience-item">
                <img src="assets/images/experience-1.jpg" alt="River Tours">
                <h3>River Tours</h3>
                <p>Navigate pristine waterways with expert guides.</p>
                <span class="experience-cta">View Details →</span>
            </a>
            <a href="Experience module/experience.php?type=mountain-hiking" class="experience-item">
                <img src="assets/images/experience-2.jpg" alt="Hiking">
                <h3>Mountain Hiking</h3>
                <p>Trek through lush forests and scenic trails.</p>
                <span class="experience-cta">View Details →</span>
            </a>
            <a href="Experience module/experience.php?type=cultural-events" class="experience-item">
                <img src="assets/images/experience-3.jpg" alt="Cultural Events">
                <h3>Cultural Events</h3>
                <p>Participate in local festivals and celebrations.</p>
                <span class="experience-cta">View Details →</span>
            </a>
            <a href="Experience module/experience.php?type=food-tours" class="experience-item">
                <img src="assets/images/experience-4.jpg" alt="Food Tours">
                <h3>Food Tours</h3>
                <p>Taste the flavors of authentic local cuisine.</p>
                <span class="experience-cta">View Details →</span>
            </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Featured Destinations Section -->
    <section class="featured" id="featured">
        <h2>Featured Destinations</h2>
        <p class="section-subtitle">Discover our top-picked attractions and experiences</p>
        <div class="featured-grid">
            <?php
            $destinationsFile = 'assets/data/destinations.json';
            $destinations = [];
            if (file_exists($destinationsFile)) {
                $json = file_get_contents($destinationsFile);
                $destinations = json_decode($json, true) ?? [];
            }
            
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
            
            foreach ($featuredDestinations as $dest):
                $icon = $typeIcons[$dest['type']] ?? '📍';
                $linkName = strtolower(str_replace(' ', '-', $dest['name']));
            ?>
                <a href="Plan module/destination.php?destination=<?php echo $linkName; ?>" class="featured-card">
                    <div class="featured-icon"><?php echo $icon; ?></div>
                    <h3><?php echo htmlspecialchars($dest['name']); ?></h3>
                    <p><?php echo htmlspecialchars($dest['description']); ?></p>
                    <span class="featured-cta">View Details →</span>
                </a>
            <?php endforeach; ?>
            
            <?php if (empty($featuredDestinations)): ?>
                <p style="text-align:center;grid-column:1/-1;">No featured destinations yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Planning Section -->
    <section class="planning" id="plan">
        <h2>Plan Your Visit</h2>
        <p class="section-subtitle">Explore our top tourist destinations with comprehensive guides, best travel times, packing lists, and visiting guidelines.</p>
        <div class="destination-grid">
            <?php
            $destinationsFile = 'assets/data/destinations.json';
            $destinations = [];
            if (file_exists($destinationsFile)) {
                $json = file_get_contents($destinationsFile);
                $destinations = json_decode($json, true) ?? [];
            }
            
            // Sort destinations: featured ones first
            usort($destinations, function($a, $b) {
                $aFeatured = isset($a['featured']) && $a['featured'] === true;
                $bFeatured = isset($b['featured']) && $b['featured'] === true;
                if ($aFeatured === $bFeatured) {
                    return 0;
                }
                return $aFeatured ? -1 : 1;
            });
            
            // Display destinations (limit to 8 for the grid)
            $displayDestinations = array_slice($destinations, 0, 8);
            
            // Get type icons
            $typeIcons = [
                'Natural Wonder' => '🏞️',
                'Adventure' => '⛰️',
                'Museum' => '🏛️',
                'Religious' => '⛪',
                'Festival' => '🎉',
                'Historical' => '📜',
                'Local Cuisine' => '🍽️'
            ];
            
            foreach ($displayDestinations as $dest):
                $icon = $typeIcons[$dest['type']] ?? '📍';
                $linkName = strtolower(str_replace(' ', '-', $dest['name']));
            ?>
                <a href="Plan module/destination.php?destination=<?php echo $linkName; ?>" class="destination-card">
                    <?php if (!empty($dest['image'])): ?>
                        <img src="<?php echo htmlspecialchars($dest['image']); ?>" alt="<?php echo htmlspecialchars($dest['name']); ?>" class="dest-card-image">
                    <?php else: ?>
                        <div class="destination-icon"><?php echo $icon; ?></div>
                    <?php endif; ?>
                    <h3><?php echo isset($dest['featured']) && $dest['featured'] === true ? '⭐ ' : ''; ?><?php echo htmlspecialchars($dest['name']); ?></h3>
                    <p><?php echo htmlspecialchars($dest['description']); ?></p>
                    <span class="destination-cta">View Details →</span>
                </a>
            <?php endforeach; ?>
            
            <?php if (empty($displayDestinations)): ?>
            <a href="Plan module/destination.php?destination=pumauna-waterfalls" class="destination-card">
                <div class="destination-icon">💧</div>
                <h3>Pumauna Waterfalls</h3>
                <p>Magnificent cascade with natural pools and scenic hiking trails.</p>
                <span class="destination-cta">View Details →</span>
            </a>
            <a href="Plan module/destination.php?destination=azuela-springs" class="destination-card">
                <div class="destination-icon">🌊</div>
                <h3>Azuela Springs</h3>
                <p>Crystal clear natural pools fed by underground springs.</p>
                <span class="destination-cta">View Details →</span>
            </a>
            <a href="Plan module/destination.php?destination=mt-kampalilis" class="destination-card">
                <div class="destination-icon">⛰️</div>
                <h3>Mt. Kampalilis</h3>
                <p>Challenge yourself with a scenic mountain trek to 1,240m peak.</p>
                <span class="destination-cta">View Details →</span>
            </a>
            <a href="Plan module/destination.php?destination=tagum-river" class="destination-card">
                <div class="destination-icon">🚣</div>
                <h3>Tagum River</h3>
                <p>Pristine waterway perfect for boating, fishing, and relaxation.</p>
                <span class="destination-cta">View Details →</span>
            </a>
            <a href="Plan module/destination.php?destination=tagum-city-museum" class="destination-card">
                <div class="destination-icon">🏛️</div>
                <h3>Tagum City Museum</h3>
                <p>Discover local history and cultural artifacts spanning centuries.</p>
                <span class="destination-cta">View Details →</span>
            </a>
            <a href="Plan module/destination.php?destination=san-fernando-church" class="destination-card">
                <div class="destination-icon">⛪</div>
                <h3>San Fernando Church</h3>
                <p>Historic colonial architecture and spiritual landmark of the city.</p>
                <span class="destination-cta">View Details →</span>
            </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Destination Guide Card -->

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

    <script src="assets/js/script.js"></script>
</body>
</html>
