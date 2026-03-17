// Get all tab buttons
const tabBtns = document.querySelectorAll('.tab-btn');
const sectionContents = document.querySelectorAll('.section-content');

// Add click event to each tab button
tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const section = btn.getAttribute('data-section');
        showSection(section);
    });
});

function showSection(sectionId) {
    // Hide all sections
    sectionContents.forEach(content => {
        content.classList.remove('active');
    });

    // Remove active class from all tabs
    tabBtns.forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected section
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.classList.add('active');
    }

    // Activate corresponding tab
    const activeTab = document.querySelector(`[data-section="${sectionId}"]`);
    if (activeTab) {
        activeTab.classList.add('active');
    }

    // Scroll to top smoothly
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Get section from URL parameter on page load
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section');
    
    if (section) {
        showSection(section);
    }
});

// Smooth scroll for breadcrumb and navigation links
document.querySelectorAll('a[href*="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (!href.includes('..')) {
            e.preventDefault();
        }
    });
});

// Cuisine toggle functionality
const toggleButtons = document.querySelectorAll('.toggle-items-btn');

toggleButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const itemsList = btn.nextElementSibling;
        
        if (itemsList.classList.contains('show')) {
            itemsList.classList.remove('show');
            btn.textContent = 'View Dishes';
        } else {
            itemsList.classList.add('show');
            btn.textContent = 'Hide Dishes';
        }
    });
});
