// Get all experience detail sections
const experienceDetails = document.querySelectorAll('.experience-detail');
const breadcrumbTitle = document.getElementById('breadcrumb-title');

// Map of experience types to display titles
const experienceTitles = {
    'river-tours': 'River Tours',
    'mountain-hiking': 'Mountain Hiking',
    'cultural-events': 'Cultural Events',
    'food-tours': 'Food Tours'
};

// Function to show a specific experience
function showExperience(experienceType) {
    // Hide all experiences
    experienceDetails.forEach(exp => {
        exp.classList.remove('active');
    });

    // Show selected experience
    const selectedExperience = document.getElementById(experienceType);
    if (selectedExperience) {
        selectedExperience.classList.add('active');
    }

    // Update breadcrumb title
    if (breadcrumbTitle && experienceTitles[experienceType]) {
        breadcrumbTitle.textContent = experienceTitles[experienceType];
    }

    // Scroll to top smoothly
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Get experience type from URL parameter on page load
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const experienceType = urlParams.get('type');
    
    if (experienceType && Object.keys(experienceTitles).includes(experienceType)) {
        showExperience(experienceType);
    } else {
        // Show river-tours by default
        showExperience('river-tours');
    }
});

// Smooth scroll for navigation links
document.querySelectorAll('a[href*="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (!href.includes('..')) {
            e.preventDefault();
        }
    });
});

// Hide images that fail to load to remove broken picture icons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('img').forEach(img => {
        if (!img.getAttribute('src')) {
            img.style.display = 'none';
            return;
        }
        img.addEventListener('error', function() {
            this.style.display = 'none';
        });
    });
});
