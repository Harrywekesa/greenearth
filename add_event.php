<?php include 'php/header.php'; ?>

<section class="add-event">
    <h2>Add New Event</h2>
    <form action="process_event.php" method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="date">Event Date:</label>
        <input type="date" id="date" name="date" required>

        <button type="submit">Add Event</button>
    </form>
</section>

<?php include 'php/footer.php'; ?>