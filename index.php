<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; ?>

<!-- Hero Section -->
<section class="hero" style="background-image: url('images/nature1.png');">
<div class="hero-marquee">
    <!-- Vertical Marquee Text -->
    <marquee behavior="scroll" direction="up" scrollamount="2" onmouseover="this.stop()" onmouseout="this.start()">
    GreenEarth: Promoting Tree Planting and Environmental Conservation

GreenEarth is a digital platform dedicated to promoting tree planting, environmental sustainability, and community engagement in Kenya. The website provides comprehensive information about various tree species, their ecological benefits, and their suitability for different regions. It serves as an educational resource to encourage informed afforestation efforts.

The platform features an online marketplace where users can browse and purchase tree seedlings, ensuring easy access to high-quality saplings for reforestation projects. This e-commerce section connects tree nurseries with individuals and organizations dedicated to environmental conservation.

GreenEarth also fosters community engagement by organizing and promoting tree-planting initiatives, workshops, and awareness campaigns. Users can participate in upcoming greening activities, share their experiences, and collaborate on sustainability efforts.

The project highlights the contrast between thriving nature and environmental degradation, emphasizing the urgent need for reforestation. Through visually compelling content, GreenEarth raises awareness about the consequences of deforestation while inspiring action toward a greener future.

By leveraging digital technology, GreenEarth simplifies access to information, promotes eco-friendly practices, and empowers individuals to contribute to global reforestation efforts. This initiative aligns with sustainable development goals and the role of science, technology, and innovation in building economic resilience
    </marquee>
</div>
</section>

<!-- Buttons Below Hero Section -->
<div class="hero-buttons">
    <button onclick="location.href='marketplace.php'">Explore Marketplace</button>
    <button onclick="scrollToSection('features')">Learn More</button>
</div>

<!-- Features Section -->
<section id="features" class="features">
<section id="features" class="features">
    <a href="tree_species.php" class="feature-link">
        <div class="feature">
            <img src="images/tree-info.png" alt="Tree Species Info">
            <h2>Tree Species Information Hub</h2>
            <p>Discover tree species suitable for different regions in Kenya and their ecological benefits.</p>
        </div>
    </a>

    <a href="marketplace.php" class="feature-link">
        <div class="feature">
            <img src="images/marketplace.png" alt="Online Marketplace">
            <h2>Online Tree Seedling Marketplace</h2>
            <p>Purchase high-quality seedlings tailored to your geographical needs with convenient delivery options.</p>
        </div>
    </a>

    <a href="community_engagement.php" class="feature-link">
        <div class="feature">
            <img src="images/community.png" alt="Community Engagement">
            <h2>Community Engagement</h2>
            <p>Join events, collaborate with others, and contribute to large-scale reforestation efforts across Kenya.</p>
        </div>
    </a>
</section>
</section>

<!-- Upcoming Events Section -->
<section class="events">
    <h2>Upcoming Greening Activities</h2>
    <?php
    include 'php/db.php';
    $sql = "SELECT * FROM events ORDER BY event_date LIMIT 3";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="event">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p><strong>Date:</strong> ' . date("F j, Y", strtotime($row['event_date'])) . '</p>';
            echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No upcoming events at the moment.</p>';
    }
    ?>
    <button onclick="location.href='events.php'">View All Events</button>
</section>

<!-- Testimonials Section -->
<section class="testimonials">
    <h2>What Our Community Says</h2>
    <div class="testimonial-slider">
        <div class="testimonial">
            <p>"GreenEarth has transformed my community by teaching us sustainable practices and providing access to quality seedlings."</p>
            <cite>- Jane Kinyoro, Kitale Resident</cite>
        </div>
        <div class="testimonial">
            <p>"I started my own tree nursery business thanks to the training programs offered by GreenEarth!"</p>
            <cite>- John Wafula, Eco-Entrepreneur</cite>
        </div>
        <div class="testimonial">
            <p>"Planting trees with this group was a blast! It felt great to dig in and help the planet. I'm proud knowing those trees will grow and make a difference. Join in---it's worth it!."</p>
            <cite>- Rodgers Kimutai, Kitale Resident</cite>
        </div>
        <div class="testimonial">
            <p>"Planting trees with GreenEarth has been an amazing experience. Together, we're making Kenya greener!"</p>
            <cite>- Sarah Kimani, Environmentalist</cite>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta">
    <h2>Join the Movement</h2>
    <p>Every tree planted is a step towards a sustainable future. Let's work together to restore our planet!</p>
    <button onclick="location.href='contacts.php'">Get Involved</button>
</section>

<?php include 'php/footer.php'; ?>