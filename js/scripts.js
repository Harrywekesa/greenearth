// Smooth scrolling to sections
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    section.scrollIntoView({ behavior: 'smooth' });
}

// Auto-scroll testimonial slider
document.addEventListener('DOMContentLoaded', () => {
    const testimonialSlider = document.querySelector('.testimonial-slider');
    let currentIndex = 0;
    const testimonials = document.querySelectorAll('.testimonial');

    function showNextTestimonial() {
        currentIndex = (currentIndex + 1) % testimonials.length;
        testimonialSlider.style.transform = `translateX(-${currentIndex * 320}px)`;
    }

    setInterval(showNextTestimonial, 5000); // Change every 5 seconds
});

// Apply Filters
function applyFilters() {
    const region = document.getElementById('region').value;
    const price = document.getElementById('price').value;

    // Example: Reload the page with query parameters
    window.location.href = `marketplace.php?region=${region}&price=${price}`;
}

// Load Next Page
function loadNextPage() {
    // Example: Fetch next set of seedlings via AJAX or reload with offset
    alert('Loading next page...');
}

// Load Previous Page
function loadPreviousPage() {
    // Example: Fetch previous set of seedlings via AJAX or reload with offset
    alert('Loading previous page...');
}

// Get modal elements
const modal = document.getElementById('success-modal');
const modalContent = document.getElementById('success-message');
const closeBtn = document.getElementsByClassName('close')[0];

// Open modal with message
function showModal(message) {
    modalContent.textContent = message;
    modal.style.display = 'block';
}

// Close modal
closeBtn.onclick = function () {
    modal.style.display = 'none';
};

// Close modal when clicking outside
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};

function validatePassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (password.length < 8) {
        alert('Password must be at least 8 characters long.');
        return false;
    }

    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return false;
    }

    return true;
}

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validateForm() {
    const email = document.getElementById('email').value;
    if (!validateEmail(email)) {
        alert('Please enter a valid email address.');
        return false;
    }
    return true;
}

