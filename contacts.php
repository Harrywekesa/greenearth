<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 
?>

<section class="contacts">
    <h2>Contact Us</h2>
    <p>Have questions or need more information? Feel free to reach out to us!</p>

    <form action="process_contact.php" method="POST">
        <label for="name">Your Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Your Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>

        <button type="submit">Send Message</button>
    </form>
</section>

<?php include 'php/footer.php'; ?>