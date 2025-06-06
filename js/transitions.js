document.addEventListener("DOMContentLoaded", () => {
    // Scroll reveal functionality with improved options
    const observerOptions = {
        root: null,
        rootMargin: '-50px',
        threshold: 0.15
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add active class with a slight delay for cascading effect
                setTimeout(() => {
                    entry.target.classList.remove('inactive');
                    entry.target.classList.add('active');
                }, entry.target.dataset.delay || 0);
            } else {
                // Add inactive class when scrolling away
                entry.target.classList.add('inactive');
                entry.target.classList.remove('active');
            }
        });
    }, observerOptions);

    // Observe all reveal elements
    document.querySelectorAll('.reveal').forEach(element => {
        observer.observe(element);
    });

    // Close navigation when clicking links
    document.querySelectorAll(".link").forEach((link) => {
        link.addEventListener("click", () => {
            links.classList.remove("nav-open");
            navToggle.classList.remove("nav-open");
            body.classList.remove("nav-open");

            // Add active class when clicking links
            document.querySelectorAll(".link").forEach(l => l.classList.remove("active"));
            link.classList.add("active");
        });
    });

    // Close navigation when clicking outside
    document.addEventListener("click", (e) => {
        if (
            !e.target.closest(".navbar") &&
            links.classList.contains("nav-open")
        ) {
            links.classList.remove("nav-open");
            navToggle.classList.remove("nav-open");
            body.classList.remove("nav-open");
        }
    });

    function updateActiveLink() {
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        let current = "";

        // Check if we're at the bottom of the page for contact section
        if (Math.ceil(scrollY + windowHeight) >= documentHeight - 10) {
            current = "contact";
        } else {
            const sections = document.querySelectorAll("section, footer");
            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (scrollY >= sectionTop - sectionHeight / 3) {
                    current = section.getAttribute("id") || "";
                }
            });
        }

        // Update active classes
        document.querySelectorAll(".link").forEach((link) => {
            link.classList.remove("active");
            const href = link.getAttribute("href").slice(1);
            if (href === current) {
                link.classList.add("active");
            }
        });

        // Special case for home link
        if (scrollY < 100) {
            document.querySelectorAll(".link").forEach((link) => {
                link.classList.remove("active");
                if (link.getAttribute("href") === "#") {
                    link.classList.add("active");
                }
            });
        }
    }

    // Update active link on scroll
    window.addEventListener("scroll", updateActiveLink);

    // Update active link on page load
    updateActiveLink();

    // Handle contact link click specifically
    document.querySelector('a[href="#contact"]')?.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: document.documentElement.scrollHeight,
            behavior: 'smooth'
        });
        document.querySelectorAll(".link").forEach(link => link.classList.remove("active"));
        e.target.classList.add("active");
    });

    // Modal UI handling
    const modal = document.getElementById('loginModal');
    const loginBtn = document.querySelector('.login-btn');
    const closeBtn = document.querySelector('.close-modal');

    // Open modal
    loginBtn?.addEventListener('click', () => {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });

    // Close modal
    const closeModal = () => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    closeBtn?.addEventListener('click', closeModal);

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Notification handling
    window.showNotification = (message, type = 'success') => {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    };
});
