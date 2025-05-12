// Admin Dashboard JavaScript
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
    const addProjectBtn = document.querySelector('.projects .add-btn');
    const addSkillBtn = document.querySelector('.skills .add-btn');
    const projectModal = document.getElementById('project-modal');
    const skillModal = document.getElementById('skill-modal');
    const cancelBtns = document.querySelectorAll('.cancel-btn');

    addProjectBtn?.addEventListener('click', () => {
        projectModal.classList.add('active');
    });

    addSkillBtn?.addEventListener('click', () => {
        skillModal.classList.add('active');
    });

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
        const formData = new FormData(profileForm);
        
        try {
            const response = await fetch('api/update_profile.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.status === 'success') {
                showNotification('Profile updated successfully', 'success');
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            showNotification('Error updating profile', 'error');
        }
    });

    // Skills form handling
    const skillForm = document.getElementById('skill-form');
    skillForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(skillForm);
        
        try {
            const response = await fetch('api/add_skill.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.status === 'success') {
                showNotification('Skill added successfully', 'success');
                skillForm.reset();
                loadSkills();
                skillModal.classList.remove('active');
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            showNotification('Error adding skill', 'error');
        }
    });

    // Projects form handling
    const projectForm = document.getElementById('project-form');
    projectForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(projectForm);
        
        try {
            const response = await fetch('api/add_project.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.status === 'success') {
                showNotification('Project added successfully', 'success');
                projectForm.reset();
                loadProjects();
                projectModal.classList.remove('active');
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            showNotification('Error adding project', 'error');
        }
    });

    // Load existing data
    async function loadSkills() {
        try {
            const response = await fetch('api/get_skills.php');
            const data = await response.json();
            
            const skillsGrid = document.querySelector('.skills-grid');
            if (skillsGrid && data.skills) {
                skillsGrid.innerHTML = data.skills.map(skill => `
                    <div class="skill-card">
                        <div class="card-actions">
                            <button class="edit-btn" onclick="editSkill(${skill.id})">Edit</button>
                            <button class="delete-btn" onclick="deleteSkill(${skill.id})">Delete</button>
                        </div>
                        <img src="${skill.icon}" alt="${skill.name}">
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
            const response = await fetch('api/get_projects.php');
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
            const response = await fetch(`api/get_skill.php?id=${id}`);
            const data = await response.json();
            
            if (data.status === 'success' && data.skill) {
                const form = document.getElementById('skill-form');
                form.querySelector('[name="name"]').value = data.skill.name;
                form.querySelector('[name="icon_url"]').value = data.skill.icon;
                skillModal.classList.add('active');
            }
        } catch (error) {
            showNotification('Error loading skill data', 'error');
        }
    };

    window.editProject = async (id) => {
        try {
            const response = await fetch(`api/get_project.php?id=${id}`);
            const data = await response.json();
            
            if (data.status === 'success' && data.project) {
                const form = document.getElementById('project-form');
                form.querySelector('[name="title"]').value = data.project.title;
                form.querySelector('[name="description"]').value = data.project.description;
                form.querySelector('[name="technologies"]').value = data.project.technologies;
                form.querySelector('[name="github_url"]').value = data.project.github_url;
                form.querySelector('[name="demo_url"]').value = data.project.demo_url;
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
                const response = await fetch(`api/delete_skill.php?id=${id}`, {
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
                const response = await fetch(`api/delete_project.php?id=${id}`, {
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

    // Notification helper
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Logout functionality
    const logoutBtn = document.querySelector('.logout');
    logoutBtn?.addEventListener('click', async () => {
        try {
            const response = await fetch('api/logout.php', {
                method: 'POST'
            });
            
            if (response.ok) {
                window.location.href = 'portfolio.php';
            }
        } catch (error) {
            showNotification('Error logging out', 'error');
        }
    });

    // Initial load
    loadSkills();
    loadProjects();
});
