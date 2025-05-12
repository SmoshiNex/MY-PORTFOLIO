<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Albriane Jay Usman | Web Developer</title>
    <link rel="stylesheet" href="style/portfolio.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <nav class="navbar">
        <div class="name">
            <h3 class="name-title">PORTFOLIO</h3>
        </div>

        <button class="nav-toggle" aria-label="toggle navigation">
            <span class="hamburger"></span>
        </button>
        <div class="links">
            <ul>
                <li><a href="#" class="link active">HOME</a></li>
                <li><a href="#my-skills" class="link">SKILLS</a></li>
                <li><a href="#my-projects" class="link">PROJECTS</a></li>
                <li><a href="#contact" class="link">CONTACT</a></li>
                <button type="submit" class="login-btn">LOGIN</button>
            </ul>
        </div>
    </nav>
    <!-- Login Modal -->
    <div class="modal" id="loginModal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Admin Login</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div> <?php include 'get_profile.php'; ?>
    <header class="hero">
        <div class="container">
            <div class="profile">
                <div class="profile-image-container">
                    <img src="<?php echo htmlspecialchars($profileData['profile_picture'] ?? 'img/pic.jpg'); ?>"
                        alt="<?php echo htmlspecialchars($profileData['name']); ?>" />
                </div>
                <h1 class="profile-name"><?php echo htmlspecialchars($profileData['name']); ?></h1>
                <p class="profile-title"><?php echo htmlspecialchars($profileData['title']); ?></p>
                <div class="profile-text">
                    <?php if ($profileData['about']): ?>
                        <p><?php echo nl2br(htmlspecialchars($profileData['about'])); ?></p>
                    <?php endif; ?>
                </div>
                <div class="profile-actions">
                    <a href="#my-projects" class="btn btn-primary">View My Work</a>
                    <a href="#contact" class="btn btn-secondary">Contact Me</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="my-skills" id="my-skills">
            <div class="container">
                <h2 class="section-title">MY SKILLS</h2>
                <div class="skills-list">
                    <?php include 'get_skills.php'; ?>
                </div>
            </div>
        </section>

        <section class="my-projects" id="my-projects">
            <div class="container">
                <h2 class="section-title">MY PROJECTS</h2>
                <div class="projects-grid">
                    <?php include 'get_projects.php'; ?>
                    <?php if (!empty($projects)): ?>
                        <?php foreach ($projects as $project): ?>
                            <div class="project-card">
                                <div class="project-image">
                                    <img src="<?php echo htmlspecialchars($project['image']); ?>"
                                        alt="<?php echo htmlspecialchars($project['title']); ?>" />
                                </div>
                                <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                                <p class="project-description"><?php echo htmlspecialchars($project['description']); ?></p>
                                <div class="project-tech">
                                    <?php if (!empty($project['technologies'])): ?>
                                        <?php foreach (explode(',', $project['technologies']) as $tech): ?>
                                            <span class="tech-tag"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="project-actions">
                                    <?php if (!empty($project['project_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($project['project_link']); ?>" class="btn btn-sm"
                                            target="_blank">View Project</a>
                                    <?php endif; ?>
                                    <?php if (!empty($project['github_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($project['github_link']); ?>"
                                            class="btn btn-sm btn-secondary" target="_blank">GitHub</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-project">No projects to display.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <h3 class="footer-title">About Me</h3>
                    <p>
                        I'm a passionate web developer and BSIT student at
                        WMSU, focused on creating user-friendly and visually
                        appealing web applications.
                    </p>
                </div>

                <div class="footer-social">
                    <h3 class="footer-title">Connect With Me</h3>
                    <ul class="social-links">
                        <li>
                            <a href="https://www.facebook.com/albrianejay?mibextid=ZbWKwL" class="social-media"
                                target="_blank">
                                <i class="fab fa-facebook"></i>
                                <span>Facebook</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/nex_pogiii/profilecard/?igsh=eXNqMHh0ZG51MXJz"
                                class="social-media" target="_blank">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.tiktok.com/@nex.jpeg?_t=ZS-8vOAMJEBsET&_r=1" class="social-media"
                                target="_blank">
                                <i class="fab fa-tiktok"></i>
                                <span>TikTok</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/SmoshiNex" class="social-media" target="_blank">
                                <i class="fab fa-github"></i>
                                <span>GitHub</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="contact-info">
                    <h3 class="footer-title">Contact Me</h3>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <p>usman.albrianejay@gmail.com</p>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <p>+977 4244 540</p>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Zamboanga City, Philippines</p>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 ALBRIANE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

</body>
<script src="js/transitions.js"></script>
<script src="js/portfolio.js"></script>

</html>