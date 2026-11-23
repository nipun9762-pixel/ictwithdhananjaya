<?php
require_once 'includes/auth.php';
if (isLoggedIn()) {
    if ($_SESSION['role'] === 'teacher') {
        header('Location: ' . BASE_URL . '/teacher/dashboard.php');
    } else {
        header('Location: ' . BASE_URL . '/student/dashboard.php');
    }
    exit;
}
$pageTitle = 'Welcome - Tuition LMS';
require 'includes/header.php';
?>

<!-- HERO SECTION -->
<section class="hero-modern">
    <div class="hero-content">
        <h1>Access to <span>Class Recordings</span></h1>
        <p>
            Upload your class card or the payment slip for this month, you can unlock 
            all benefits by uploading a clear photo of it. These benefits include 50% 
            paid courses and Free access to the recordings of this month’s 
            Online and Physical classes.
        </p>
        <a href="<?= BASE_URL ?>/register.php" class="btn-hero">Upload Class Card or Payment Slip →</a>
    </div>
    <div class="hero-image">
        <div class="hero-slider" data-hero-slider aria-label="Educator photos">
            <div class="hero-slider-track">
                <div class="hero-slide active" aria-hidden="false">
                    <img src="<?= BASE_URL ?>/assets/images/p1.jpg" alt="Your ICT educator">
                </div>
                <div class="hero-slide" aria-hidden="true">
                    <img src="<?= BASE_URL ?>/assets/images/hero-teacher.png" alt="Your ICT educator">
                </div>
            </div>
            <button class="hero-slider-control hero-slider-prev" type="button" aria-label="Show previous photo">&#10094;</button>
            <button class="hero-slider-control hero-slider-next" type="button" aria-label="Show next photo">&#10095;</button>
            <div class="hero-slider-dots" aria-label="Choose a photo">
                <button class="hero-slider-dot active" type="button" aria-label="Show photo 1" aria-pressed="true"></button>
                <button class="hero-slider-dot" type="button" aria-label="Show photo 2" aria-pressed="false"></button>
            </div>
        </div>
    </div>
</section>

<!-- POPULAR COURSES -->
<section class="popular-courses">
    <div class="section-header">
        <h2>See Our <span>Popular Courses</span></h2>
        <a href="<?= BASE_URL ?>/login.php" class="btn-outline">View all courses →</a>
    </div>

    <div class="course-grid">
        <!-- Course Card 1 -->
        <div class="course-card">
            <div class="course-img">
                <img src="<?= BASE_URL ?>/assets/images/p2.png" alt="Course">
            </div>
            <div class="course-body">
                <span class="tag">Theory</span>
                <h3>2027 A/Level ICT - 1st Term Seminar</h3>
                <div class="price">Rs.1000</div>
                <div class="meta">
                    <span>★ 0 Rating</span>
                    <span>1 Week</span>
                    <span>2 Lessons</span>
                    <span>17 Students</span>
                </div>
            </div>
        </div>



        <div class="course-card">
            <div class="course-img">
                <img src="<?= BASE_URL ?>/assets/images/p2.png" alt="Course">
            </div>
            <div class="course-body">
                <span class="tag">Theory</span>
                <h3>2027 A/Level ICT - 1st Term Seminar</h3>
                <div class="price">Rs.1000</div>
                <div class="meta">
                    <span>★ 0 Rating</span>
                    <span>1 Week</span>
                    <span>2 Lessons</span>
                    <span>17 Students</span>
                </div>
            </div>
        </div>

        <div class="course-card">
            <div class="course-img">
                <img src="<?= BASE_URL ?>/assets/images/p2.png" alt="Course">
            </div>
            <div class="course-body">
                <span class="tag">Theory</span>
                <h3>2027 A/Level ICT - 1st Term Seminar</h3>
                <div class="price">Rs.1000</div>
                <div class="meta">
                    <span>★ 0 Rating</span>
                    <span>1 Week</span>
                    <span>2 Lessons</span>
                    <span>17 Students</span>
                </div>
            </div>
        </div>


        <div class="course-card">
            <div class="course-img">
                <img src="<?= BASE_URL ?>/assets/images/p2.png" alt="Course">
            </div>
            <div class="course-body">
                <span class="tag">Theory</span>
                <h3>2027 A/Level ICT - 1st Term Seminar</h3>
                <div class="price">Rs.1000</div>
                <div class="meta">
                    <span>★ 0 Rating</span>
                    <span>1 Week</span>
                    <span>2 Lessons</span>
                    <span>17 Students</span>
                </div>
            </div>
        </div>




        <div class="course-card">
            <div class="course-img">
                <img src="<?= BASE_URL ?>/assets/images/p2.png" alt="Course">
            </div>
            <div class="course-body">
                <span class="tag">Theory</span>
                <h3>2027 A/Level ICT - 1st Term Seminar</h3>
                <div class="price">Rs.1000</div>
                <div class="meta">
                    <span>★ 0 Rating</span>
                    <span>1 Week</span>
                    <span>2 Lessons</span>
                    <span>17 Students</span>
                </div>
            </div>
        </div>



        <div class="course-card">
            <div class="course-img">
                <img src="<?= BASE_URL ?>/assets/images/p2.png" alt="Course">
            </div>
            <div class="course-body">
                <span class="tag">Theory</span>
                <h3>2027 A/Level ICT - 1st Term Seminar</h3>
                <div class="price">Rs.1000</div>
                <div class="meta">
                    <span>★ 0 Rating</span>
                    <span>1 Week</span>
                    <span>2 Lessons</span>
                    <span>17 Students</span>
                </div>
            </div>
        </div>



        <div class="course-card">
            <div class="course-img">
                <img src="<?= BASE_URL ?>/assets/images/p2.png" alt="Course">
            </div>
            <div class="course-body">
                <span class="tag">Theory</span>
                <h3>2027 A/Level ICT - 1st Term Seminar</h3>
                <div class="price">Rs.1000</div>
                <div class="meta">
                    <span>★ 0 Rating</span>
                    <span>1 Week</span>
                    <span>2 Lessons</span>
                    <span>17 Students</span>
                </div>
            </div>
        </div>





        <div class="course-card">
            <div class="course-img">
                <img src="<?= BASE_URL ?>/assets/images/p2.png" alt="Course">
            </div>
            <div class="course-body">
                <span class="tag">Theory</span>
                <h3>2027 A/Level ICT - 1st Term Seminar</h3>
                <div class="price">Rs.1000</div>
                <div class="meta">
                    <span>★ 0 Rating</span>
                    <span>1 Week</span>
                    <span>2 Lessons</span>
                    <span>17 Students</span>
                </div>
            </div>
        </div>




        <div class="course-card">
            <div class="course-img">
                <img src="<?= BASE_URL ?>/assets/images/p2.png" alt="Course">
            </div>
            <div class="course-body">
                <span class="tag">Theory</span>
                <h3>2027 A/Level ICT - 1st Term Seminar</h3>
                <div class="price">Rs.1000</div>
                <div class="meta">
                    <span>★ 0 Rating</span>
                    <span>1 Week</span>
                    <span>2 Lessons</span>
                    <span>17 Students</span>
                </div>
            </div>
        </div>

        <!-- Add more course cards the same way -->
    </div>
</section>

<!-- STATS BAR -->
<section class="stats-bar">
    <div class="stat">
        <h3>15+</h3>
        <p>Course</p>
    </div>
    <div class="stat">
        <h3>2+</h3>
        <p>Years Experience</p>
    </div>
    <div class="stat">
        <h3>1</h3>
        <p>Expert Tutor</p>
    </div>
    <div class="stat">
        <h3>100+</h3>
        <p>Satisfied Students</p>
    </div>
</section>

<!-- MEET EDUCATOR -->
<section class="educator">
    <h2>Meet Your Educator</h2>
    <div class="educator-card">
        <img src="<?= BASE_URL ?>/assets/images/p4.jpg" alt="Teacher">
        <div>
            <h3>Dhananjaya Edirisinghe</h3>
            <p>-Educator- <br>I'm Nipun Dhananjaya, an undergraduate at SLIIT pursuing a degree in Information Technology, specializing in Software Engineering. Alongside my academic journey, Im leading a freelance software development startup, gaining practical industry experience. I’m passionate about ICT education and helping students understand technology through simple explanations and real-world examples.</p>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>