<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Admin</title>
    <link rel="stylesheet" href="style/admin.css">
</head>

<body>
    <div class="admin-container">
        <nav class="sidebar">
            <div class="logo">
                <h2>PORTFOLIO</h2>
                <p>Admin Panel</p>
            </div>
            <ul class="nav-links">
                <li class="active" data-section="projects">
                    <span class="nav-item">Projects</span>
                </li>
                <li data-section="skills">
                    <span class="nav-item">Skills</span>
                </li>
                <li data-section="profile">
                    <span class="nav-item">Profile</span>
                </li>
            </ul>
            <div class="logout">
                <span class="nav-item">Logout</span>
            </div>
        </nav>

        <main class="content">
            <!-- Projects Section -->
            <section id="projects" class="section active">
                <h1>Manage Projects</h1>
                <button class="add-btn">Add New Project</button>

                <div class="projects-grid">
                    <!-- Projects will be loaded dynamically -->
                </div>
            </section>

            <!-- Skills Section -->
            <section id="skills" class="section">
                <h1>Manage Skills</h1>
                <button class="add-btn">Add New Skill</button>

                <div class="skills-grid">
                    <!-- Skills will be loaded dynamically -->
                </div>
            </section>

            <!-- Profile Section -->
            <section id="profile" class="section">
                <h1>Update Profile</h1>
                <form id="profile-form" class="profile-form">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Your Name">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" placeholder="Your Title">
                    </div>
                    <div class="form-group">
                        <label>About</label>
                        <textarea name="about" placeholder="About yourself"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Profile Picture</label>
                        <input type="file" name="profile_picture" accept="image/*">
                    </div>
                    <button type="submit" class="save-btn">Save Changes</button>
                </form>
            </section>
        </main>
    </div>

    <!-- Modal for Add/Edit Project -->
    <div id="project-modal" class="modal">
        <div class="modal-content">
            <h2>Add/Edit Project</h2>
            <form id="project-form">
                <div class="form-group">
                    <label>Project Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>Technologies</label>
                    <input type="text" name="technologies" placeholder="HTML, CSS, JavaScript, etc.">
                </div>
                <div class="form-group">
                    <label>Project Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <div class="form-group">
                    <label>GitHub URL</label>
                    <input type="url" name="github_url">
                </div>
                <div class="form-group">
                    <label>Live Demo URL</label>
                    <input type="url" name="demo_url">
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit" class="save-btn">Save Project</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Add/Edit Skill -->
    <div id="skill-modal" class="modal">
        <div class="modal-content">
            <h2>Add/Edit Skill</h2>
            <form id="skill-form">
                <div class="form-group">
                    <label>Skill Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Icon URL</label>
                    <input type="text" name="icon_url" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit" class="save-btn">Save Skill</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/admin.js"></script>
</body>

</html>