<?php 
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php'; 
?>

<section class="add-user">
    <h2>Add New User</h2>
    <form action="process_user.php" method="POST">
        <label for="title">Title (Mr/Mrs):</label>
        <input type="text" id="title" name="title" required>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="town">Town of Residence:</label>
        <input type="text" id="town" name="town" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Add User</button>
    </form>
</section>

<?php include 'php/footer.php'; ?>