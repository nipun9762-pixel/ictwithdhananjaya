</main>

<footer class="modern-footer">
    <div class="container footer-top">
        <!-- Left side - Brand -->
        <div class="footer-brand">
            <h3>ictwithdhananjaya</h3>
            <p>Designing a user-friendly interface is essential for improving user engagement. Focus on simplicity.</p>
            
            <div class="social-links">
                <a href="https://www.linkedin.com/in/nipun-dhananjaya-575a8b368/" title="LinkedIn">in</a>
                <a href="https://www.youtube.com/@THEICTCLASS-t7q/streams" title="Youtube">yt</a>

                <a href="#" title="Facebook">f</a>
            </div>
        </div>

        <!-- Support Column -->
        <div class="footer-column">
            <h4>Support</h4>
            <ul>
                <li><a href="#">Forum Support</a></li>
                <li><a href="#">Help & FAQ</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>

        <!-- Company Column -->
        <div class="footer-column">
            <h4>Company</h4>
            <ul>
                <li><a href="#">About Us</a></li>
                <li><a href="<?= BASE_URL ?>/login.php">Courses</a></li>
                <li><a href="#">Help Center</a></li>
            </ul>
        </div>

        <!-- Newsletter -->
        <div class="footer-column">
            <h4>Subscribe to Newsletter</h4>
            <form class="newsletter-form" onsubmit="return false;">
                <input type="email" placeholder="Enter email address" required>
                <button type="submit">Join Now</button>
            </form>
        </div>
    </div>

    <!-- Bottom bar -->
    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>© <?= date('Y') ?>. All rights reserved by ictwithdhananjaya</p>
            <p>Developed by cortex.io </p>
        </div>
    </div>
</footer>

<script src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>
</html>