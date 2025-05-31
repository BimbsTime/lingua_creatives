<?php
// Start session for CSRF protection
session_start();

// Generate a CSRF token if none exists
if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Lingua Creatives</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.html">Lingua Creatives</a>
            </div>
            <div class="main-navigation">
                <nav>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="about.html">About Us</a></li>
                        <li>
                            <a href="services.html" class="dropdown-toggle">Services</a>
                            <ul class="dropdown-menu">
                                <li><a href="services.html#language-services">Language Services</a></li>
                                <li><a href="services.html#corporate-training">Corporate Training</a></li>
                                <li><a href="services.html#pharma-export">Pharma Export Consultancy</a></li>
                                <li><a href="services.html#corporate-consulting">Corporate Consulting</a></li>
                            </ul>
                        </li>
                        <li><a href="industries.html">Industries</a></li>
                        <li><a href="opportunities.html">Opportunities</a></li>
                        <li><a href="contact.php" class="active">Contact Us</a></li>
                    </ul>
                </nav>
            </div>
            <div class="header-actions">
                <div class="phone-number">
                    <a href="tel:+919870450110"><i class="fas fa-phone"></i> +91 9870450110</a>
                </div>
            </div>
            <div class="mobile-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <!-- Page Banner -->
    <section class="page-banner">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Get in touch to discuss your language service needs</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Information -->
                <div class="contact-info-box fade-in">
                    <h3>Contact Information</h3>
                    <div class="contact-info-item">
                        <i class="fas fa-phone"></i>
                        <div class="content">
                            <h4>Phone</h4>
                            <p>+91 9870450110</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fas fa-envelope"></i>
                        <div class="content">
                            <h4>Email</h4>
                            <p>info@linguacreatives.com</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="content">
                            <h4>Address</h4>
                            <p>123 Business Street<br>City, Country</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fas fa-clock"></i>
                        <div class="content">
                            <h4>Business Hours</h4>
                            <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                            Saturday: 10:00 AM - 2:00 PM<br>
                            Sunday: Closed</p>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="social-media">
                        <h4>Follow Us</h4>
                        <div class="social-icons">
                            <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="contact-form fade-in">
                    <h3>Send Us a Message</h3>
                    <form id="contactForm" method="post" action="process-form.php">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <!-- Honeypot field to catch bots - hidden from users -->
                        <div class="form-group" style="display:none;">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" autocomplete="off">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Your Name *</label>
                                <input type="text" id="name" name="user_name" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="user_phone">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email *</label>
                            <input type="email" id="email" name="user_email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="service">Service of Interest</label>
                            <select id="service" name="service">
                                <option value="">Select a service</option>
                                <option value="language-services">Language Services</option>
                                <option value="corporate-training">Corporate Training</option>
                                <option value="pharma-export">Pharma Export Consultancy</option>
                                <option value="corporate-consulting">Corporate Consulting</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Your Message *</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <div id="form-status"></div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Start Your Global Journey?</h2>
            <p>Schedule a free consultation with our language experts today.</p>
            <a href="#" class="btn btn-large btn-primary">Schedule Consultation</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <?php include 'footer-links.php'; ?>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script src="js/form-handler.js"></script>
</body>
</html> 