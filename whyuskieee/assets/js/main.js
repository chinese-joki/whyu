/**
 * Whyuskieee Announcement - Main JavaScript
 * Neubrutalism Design System
 */

// ========================================
// SIDEBAR TOGGLE (Admin)
// ========================================
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
}

// Close sidebar when clicking outside
document.addEventListener('click', function(e) {
    const sidebar = document.querySelector('.sidebar');
    const menuToggle = document.querySelector('.menu-toggle');
    
    if (sidebar && menuToggle) {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    }
});

// ========================================
// MODAL SYSTEM
// ========================================
function openModal(id) {
    const card = document.querySelector(`.announcement-card:nth-child(${id})`) || 
                 document.querySelector(`[data-id="${id}"]`);
    
    if (!card) return;
    
    const modalData = card.querySelector('.modal-data');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalContent = document.getElementById('modalContent');
    
    if (modalData && modalOverlay && modalContent) {
        modalContent.innerHTML = modalData.innerHTML;
        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal() {
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay) {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close modal on overlay click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        closeModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// ========================================
// SEARCH & FILTER (User Page)
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.announcement-card');
    const noResults = document.getElementById('noResults');
    
    let currentFilter = 'all';
    let searchTerm = '';
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchTerm = this.value.toLowerCase().trim();
            
            if (clearSearch) {
                clearSearch.style.display = searchTerm ? 'block' : 'none';
            }
            
            filterCards();
        });
    }
    
    // Clear search
    if (clearSearch) {
        clearSearch.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                searchTerm = '';
                this.style.display = 'none';
                filterCards();
            }
        });
    }
    
    // Filter buttons
    filterButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            currentFilter = this.dataset.filter;
            filterCards();
        });
    });
    
    // Filter cards function
    function filterCards() {
        let visibleCount = 0;
        
        cards.forEach(function(card) {
            const title = card.dataset.title || '';
            const content = card.dataset.content || '';
            const status = card.dataset.status || '';
            
            const matchesSearch = searchTerm === '' || 
                                  title.includes(searchTerm) || 
                                  content.includes(searchTerm);
            
            const matchesFilter = currentFilter === 'all' || status === currentFilter;
            
            if (matchesSearch && matchesFilter) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }
    
    // Staggered animation for cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    cards.forEach(function(card) {
        card.style.animationPlayState = 'paused';
        observer.observe(card);
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });
    
    // Table row hover effect
    const tableRows = document.querySelectorAll('.data-table tbody tr');
    tableRows.forEach(function(row) {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
        });
        row.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
});

// ========================================
// UTILITY FUNCTIONS
// ========================================

// Format date
function formatDate(dateString) {
    const options = { day: 'numeric', month: 'short', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Smooth scroll
function smoothScroll(target) {
    const element = document.querySelector(target);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// ========================================
// FORM VALIDATION ENHANCEMENT
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(function(input) {
            // Add focus effect
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    });
});

// ========================================
// KEYBOARD NAVIGATION
// ========================================
document.addEventListener('keydown', function(e) {
    // Focus search with Ctrl/Cmd + K
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
        }
    }
});