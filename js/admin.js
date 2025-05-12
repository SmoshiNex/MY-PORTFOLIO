// Admin Dashboard JavaScript - Fixed Version
document.addEventListener('DOMContentLoaded', () => {
    // Section switching functionality
    const navLinks = document.querySelectorAll('.nav-links li');
    const sections = document.querySelectorAll('.section');

    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            const sectionId = link.getAttribute('data-section');
            
            // Remove active class from all links and sections
            navLinks.forEach(l => l.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));
            
            // Add active class to clicked link and corresponding section
            link.classList.add('active');
            document.getElementById(sectionId).classList.add('active');
        });
    });

    // Modal functionality
    const addProjectBtn = document.getElementById('addButton');
    const addSkillBtn = document.getElementById('addSkills');
    const projectModal = document.getElementById('project-modal');
    const skillModal = document.getElementById('skill-modal');
    const cancelBtns = document.querySelectorAll('.close-modal, .cancel-btn');

    if (addProjectBtn) {
        addProjectBtn.addEventListener('click', () => {
            // Reset form when opening modal to add new project
            const form = document.getElementById('project-form');
            form.reset();
            form.setAttribute('data-mode', 'add');
            form.removeAttribute('data-id');
            document.querySelector('#project-modal h2').textContent = 'Add New Project';
            projectModal.classList.add('active');
        });
    }

    if (addSkillBtn) {
        addSkillBtn.addEventListener('click', () => {
            // Reset form when opening modal to add new skill
            const form = document.getElementById('skill-form');
            form.reset();
            form.setAttribute('data-mode', 'add');
            form.removeAttribute('data-id');
            document.querySelector('#skill-modal h2').textContent = 'Add New Skill';
            
            // Reset icon preview
            const iconPreview = document.querySelector('#skill-form .icon-preview');
            if (iconPreview) {
                iconPreview.className = 'icon-preview';
            }
            
            skillModal.classList.add('active');
        });
    }

    cancelBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            projectModal?.classList.remove('active');
            skillModal?.classList.remove('active');
        });
    });


// Profile form handling
const profileForm = document.getElementById('profile-form');
profileForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    console.log('Profile form submitted');
    
    // Disable submit button to prevent double submission
    const submitBtn = profileForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    // Show loading state
    showNotification('Updating profile...', 'info');
    
    const formData = new FormData(profileForm);
    
    // Add a flag to indicate this is an update operation
    formData.append('_method', 'PUT'); // Simulate a PUT request
    
    // Log form data for debugging
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    
    // Basic validation
    const name = formData.get('name');
    const title = formData.get('title');
    const about = formData.get('about');
    
    if (!name?.trim() || !title?.trim() || !about?.trim()) {
        showNotification('Please fill in all required fields', 'error');
        submitBtn.disabled = false;
        return;
    }
    
    try {
        console.log('Sending request to update profile...');
        const response = await fetch('../api/profile_api.php', {
            method: 'POST', // Change to POST
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response');
        }
        
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.status === 'success') {
            showNotification('Profile updated successfully', 'success');
            console.log('Profile updated successfully');
            
            // Update all profile fields with new data if available
            if (data.profile) {
                const profile = data.profile;
                console.log('New profile data:', profile);
                
                // Update form fields
                Object.keys(profile).forEach(field => {
                    const input = profileForm.elements[field];
                    if (input && field !== 'profile_picture') {
                        input.value = profile[field] || '';
                    }
                });
                
                // Update profile picture preview
                if (profile.profile_picture) {
                    let previewDiv = profileForm.querySelector('.profile-preview');
                    if (!previewDiv) {
                        previewDiv = document.createElement('div');
                        previewDiv.className = 'profile-preview';
                        const fileInput = profileForm.querySelector('[name="profile_picture"]');
                        fileInput.parentNode.insertBefore(previewDiv, fileInput.nextSibling);
                    }
                    previewDiv.innerHTML = `<img src="../${profile.profile_picture}?t=${new Date().getTime()}" alt="Profile Picture">`;
                }
                
                // Force reload main portfolio page
                try {
                    window.opener?.location.reload();
                } catch (e) {
                    console.log('Could not reload portfolio page:', e);
                }
            }
        } else {
            throw new Error(data.message || 'Unknown error occurred');
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        showNotification('Error updating profile: ' + error.message, 'error');
    } finally {
        submitBtn.disabled = false;
    }
});    // Skills form handling
    const skillForm = document.getElementById('skill-form');
skillForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(skillForm);
    const mode = skillForm.getAttribute('data-mode');
    const id = skillForm.getAttribute('data-id');

    let method = 'POST';
    let url = '../api/skill_api.php';

    if (mode === 'edit' && id) {
        method = 'POST'; // Change to POST but add _method=PUT
        formData.append('_method', 'PUT'); // Add method override
        formData.append('id', id);
        url = '../api/skill_api.php';
    }

    try {
        const response = await fetch(url, {
            method: method,
            body: formData
        });

        const data = await response.json();
        if (data.status === 'success') {
            showNotification(`Skill ${mode === 'edit' ? 'updated' : 'added'} successfully`, 'success');
            skillForm.reset();
            loadSkills();
            skillModal.classList.remove('active');
        } else {
            showNotification(data.message || 'An error occurred', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification(`Error ${mode === 'edit' ? 'updating' : 'adding'} skill`, 'error');
    }
});



    // Projects form handling
    const projectForm = document.getElementById('project-form');
    projectForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(projectForm);
        const mode = projectForm.getAttribute('data-mode');
        const id = projectForm.getAttribute('data-id');
        
        let method = 'POST';
        let url = '../api/project_api.php';
        
        // If editing existing project, use PUT method and include ID
        if (mode === 'edit' && id) {
            method = 'PUT';
            formData.append('id', id);
        }
        
        try {
            const response = await fetch(url, {
                method: method,
                body: formData
            });
            
            const data = await response.json();
            if (data.status === 'success') {
                showNotification(`Project ${mode === 'edit' ? 'updated' : 'added'} successfully`, 'success');
                projectForm.reset();
                loadProjects();
                projectModal.classList.remove('active');
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            showNotification(`Error ${mode === 'edit' ? 'updating' : 'adding'} project`, 'error');
        }
    });

    // Load existing data
    async function loadSkills() {
        try {
            const response = await fetch('../api/skill_api.php');
            const data = await response.json();
            
            const skillsGrid = document.querySelector('.skills-grid');
            if (skillsGrid && data.skills) {
                skillsGrid.innerHTML = data.skills.map(skill => `
                    <div class="skill-card">
                        <div class="card-actions">
                            <button class="edit-btn" onclick="editSkill(${skill.id})">Edit</button>
                            <button class="delete-btn" onclick="deleteSkill(${skill.id})">Delete</button>
                        </div>
                        <i class="${skill.icon}" title="${skill.name}"></i>
                        <h3>${skill.name}</h3>
                    </div>
                `).join('');
            }
        } catch (error) {
            showNotification('Error loading skills', 'error');
        }
    }

    async function loadProjects() {
        try {
            const response = await fetch('../api/project_api.php');
            const data = await response.json();
            
            const projectsGrid = document.querySelector('.projects-grid');
            if (projectsGrid && data.projects) {
                projectsGrid.innerHTML = data.projects.map(project => `
                    <div class="project-card">
                        <div class="card-actions">
                            <button class="edit-btn" onclick="editProject(${project.id})">Edit</button>
                            <button class="delete-btn" onclick="deleteProject(${project.id})">Delete</button>
                        </div>
                        <img src="${project.image}" alt="${project.title}">
                        <h3>${project.title}</h3>
                        <p>${project.description}</p>
                        <div class="tech-stack">
                            ${(project.technologies || '').split(',').map(tech => `<span class="tech-tag">${tech.trim()}</span>`).join('')}
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            showNotification('Error loading projects', 'error');
        }
    }

    // Edit handlers
    window.editSkill = async (id) => {
        try {
            const response = await fetch(`../api/skill_api.php?id=${id}`);
            const data = await response.json();
            
            if (data.status === 'success' && data.skill) {
                const form = document.getElementById('skill-form');
                form.reset(); // Clear previous data
                form.setAttribute('data-mode', 'edit');
                form.setAttribute('data-id', id.toString()); // Ensure ID is set as string
                
                // Update form values
                form.querySelector('[name="name"]').value = data.skill.name;
                form.querySelector('[name="icon"]').value = data.skill.icon;
                
                // Update icon preview
                updateIconPreview(data.skill.icon);
                
                // Update modal title
                document.querySelector('#skill-modal h2').textContent = 'Edit Skill';
                
                skillModal.classList.add('active');
            }
        } catch (error) {
            showNotification('Error loading skill data', 'error');
        }
    };

    window.editProject = async (id) => {
        try {
            const response = await fetch(`../api/project_api.php?id=${id}`);
            const data = await response.json();
            
            if (data.status === 'success' && data.project) {
                const form = document.getElementById('project-form');
                form.reset(); // Clear previous data
                form.setAttribute('data-mode', 'edit');
                form.setAttribute('data-id', id);
                
                // Update form values
                form.querySelector('[name="title"]').value = data.project.title;
                form.querySelector('[name="description"]').value = data.project.description;
                form.querySelector('[name="technologies"]').value = data.project.technologies;
                form.querySelector('[name="github_url"]').value = data.project.github_url;
                form.querySelector('[name="demo_url"]').value = data.project.demo_url;
                
                // Update modal title
                document.querySelector('#project-modal h2').textContent = 'Edit Project';
                
                projectModal.classList.add('active');
            }
        } catch (error) {
            showNotification('Error loading project data', 'error');
        }
    };

    // Delete handlers
    window.deleteSkill = async (id) => {
        if (confirm('Are you sure you want to delete this skill?')) {
            try {
                const response = await fetch(`../api/skill_api.php?id=${id}`, {
                    method: 'DELETE'
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    showNotification('Skill deleted successfully', 'success');
                    loadSkills();
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                showNotification('Error deleting skill', 'error');
            }
        }
    };

    window.deleteProject = async (id) => {
        if (confirm('Are you sure you want to delete this project?')) {
            try {
                const response = await fetch(`../api/project_api.php?id=${id}`, {
                    method: 'DELETE'
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    showNotification('Project deleted successfully', 'success');
                    loadProjects();
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                showNotification('Error deleting project', 'error');
            }
        }
    };

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notif => notif.remove());
        
        // Add new notification
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add notification styles if not already present
    function addNotificationStyles() {
        if (!document.getElementById('notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                .notification {
                    transform: translateX(-50%);
                    padding: 15px 25px;
                    border-radius: 4px;
                    color: white;
                    font-weight: 500;
                    z-index: 9999;
                    text-align: center;
                    min-width: 300px;
                    max-width: 600px;
                    margin: 0 auto;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
                
                .notification.success {
                    background-color: #4caf50;
                }
                
                .notification.error {
                    background-color: #f44336;
                }
                
                .notification.info {
                    background-color: #2196f3;
                }
                
                .notification.fade-out {
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }
                
                @keyframes slideDown {
                    0% {
                        transform: translate(-50%, -100%);
                        opacity: 0;
                    }
                    100% {
                        transform: translate(-50%, 0);
                        opacity: 1;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

    // Initialize notification styles
    document.addEventListener('DOMContentLoaded', () => {
        addNotificationStyles();
        // ... rest of the DOMContentLoaded code ...
    });

    // Icon preview functionality
    const iconInput = document.querySelector('#skill-form [name="icon"]');
    const iconPreview = document.querySelector('#skill-form .icon-preview');

    function updateIconPreview(iconClass) {
        if (iconPreview) {
            iconPreview.className = 'icon-preview ' + iconClass;
        }
    }

    if (iconInput) {
        iconInput.addEventListener('input', (e) => {
            updateIconPreview(e.target.value);
        });
    }    // Load profile data
    async function loadProfile() {
        try {
            const response = await fetch('../api/profile_api.php');
            const data = await response.json();
            
            if (data.status === 'success' && data.profile) {
                const profileForm = document.getElementById('profile-form');
                const profile = data.profile;
                
                // Populate form fields
                const fields = ['name', 'title', 'about', 'email', 'phone', 'location', 'github_url', 'linkedin_url'];
                fields.forEach(field => {
                    const input = profileForm.elements[field];
                    if (input) {
                        input.value = profile[field] || '';
                    }
                });
                
                // Show current profile picture if exists
                if (profile.profile_picture) {
                    const imgPreview = document.createElement('div');
                    imgPreview.className = 'profile-preview';
                    imgPreview.innerHTML = `<img src="../${profile.profile_picture}?t=${new Date().getTime()}" alt="Current profile picture">`;
                    
                    const pictureInput = profileForm.querySelector('[name="profile_picture"]');
                    // Remove existing preview if any
                    const existingPreview = pictureInput.parentNode.querySelector('.profile-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    pictureInput.parentNode.insertBefore(imgPreview, pictureInput.nextSibling);
                }
            }
        } catch (error) {
            showNotification('Error loading profile data', 'error');
        }
    }

    // Add logout handler
    const logoutBtn = document.querySelector('.logout');
    logoutBtn?.addEventListener('click', async () => {
        try {
            const response = await fetch('../api/account_api.php?action=logout', {
                method: 'GET'
            });
            
            const data = await response.json();
            if (data.status === 'success') {
                showNotification('Logout successful! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = '../portfolio.php';
                }, 1000);
            } else {
                showNotification('Error logging out', 'error');
            }
        } catch (error) {
            console.error('Logout error:', error);
            showNotification('Error logging out', 'error');
        }
    });

    // Initial load
    loadSkills();
    loadProjects();
    loadProfile();
});