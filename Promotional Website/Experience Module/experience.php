<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Experience Details - Tagum City'); ?></title>
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

    <!-- Main Content -->
    <main class="experience-single">
        <div class="container">
            <?php
            // Load experiences from JSON
            $experiencesFile = '../assets/data/experiences.json';
            $experiences = [];
            if (file_exists($experiencesFile)) {
                $json = file_get_contents($experiencesFile);
                $experiences = json_decode($json, true) ?? [];
            }

            // Get URL params
            $type = $_GET['type'] ?? '';
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            // Find matching experience
            $experience = null;
            foreach ($experiences as $exp) {
                if ((int)$exp['id'] === $id) {
                    $experience = $exp;
                    break;
                }
            }

            $pageTitle = $experience ? htmlspecialchars($experience['name']) : 'Experience Not Found';

            if ($experience):
            ?>
<article class="experience-detail active">
                    <header class="experience-header">
                        <h1><?php echo (isset($experience['featured']) && $experience['featured']) ? '⭐ ' : ''; ?><?php echo htmlspecialchars($experience['name']); ?></h1>
                        <div class="experience-meta">
                            <span class="exp-type"><?php echo htmlspecialchars($experience['type'] ?? 'Experience'); ?></span>
                            <?php if (isset($experience['date']) && $experience['date']): ?>
                                <span class="exp-date">📅 <?php echo htmlspecialchars($experience['date']); ?></span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <?php if (!empty($experience['image'])): ?>
                        <div class="experience-image">
                            <img src="<?php echo htmlspecialchars($experience['image']); ?>" alt="<?php echo htmlspecialchars($experience['name']); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="experience-content">
                        <div class="experience-description">
                            <?php echo nl2br(htmlspecialchars($experience['description'] ?? '')); ?>
                        </div>
                    </div>

                    <div class="experience-actions">
                        <a href="../index.php#experiences" class="smooth-scroll btn btn-secondary">← Back to Experiences</a>
                        <a href="../index.php#plan" class="smooth-scroll btn btn-primary">Plan Your Visit</a>
                    </div>
                </article>
            <?php else: ?>
                <div class="not-found">
                    <h1>Experience Not Found</h1>
                    <p>The experience you're looking for doesn't exist or has been removed.</p>
                    <a href="../index.php#experiences" class="btn btn-primary">← Back to Experiences</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2026 Tagum City. All rights reserved.</p>
        </div>
    </footer>

    <script src="../assets/js/experience-details.js"></script>
</body>
</html>
