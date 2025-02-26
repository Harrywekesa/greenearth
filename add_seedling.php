<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $region = trim($_POST['region']);
    $height = trim($_POST['height']);
    $fruit = trim($_POST['fruit']);
    $purpose = trim($_POST['purpose']);
    $stock = (int)$_POST['stock'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $upload_dir = 'images/seedlings/';
        $image_path = $upload_dir . $file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
        }

        if (move_uploaded_file($tmp_name, $image_path)) {
            // Insert seedling into the database
            $insert_sql = "INSERT INTO seedlings (name, description, price, image, region, height, fruit, purpose, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sssdssssi", $name, $description, $price, $image_path, $region, $height, $fruit, $purpose, $stock);

            if ($stmt->execute()) {
                $success_message = 'Seedling added successfully!';
            } else {
                $error_message = 'Error adding seedling: ' . $stmt->error;
            }
        } else {
            $error_message = 'Error uploading image.';
        }
    } else {
        $error_message = 'Please upload a valid image.';
    }
}
?>

<section class="add-seedling">
    <h2>Add New Seedling</h2>

    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <!-- Name -->
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <!-- Description (Rich Text Editor) -->
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <!-- Price -->
        <label for="price">Price (KES):</label>
        <input type="number" id="price" name="price" step="0.01" min="0" required>

        <!-- Image Upload -->
        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <!-- Region -->
        <label for="region">Region:</label>
        <select id="region" name="region" required>
            <option value="Central Highlands">Central Highlands</option>
            <option value="Lake Victoria Basin and Highlands">Lake Victoria Basin and Highlands</option>
            <option value="North-western">North-western</option>
            <option value="North-eastern">North-eastern</option>
            <option value="South-eastern">South-eastern</option>
            <option value="The Coastal strip">The Coastal strip</option>
            <option value="Rift Valley">Rift Valley</option>
            <option value="East African Rift valley">East African Rift valley</option>
            <option value="Kerio valley">Kerio valley</option>
            <option value="Elegeyo valley">Elegeyo valley</option>
            <option value="Lower Kerio and Suguta valleys">Lower Kerio and Suguta valleys</option>
        </select>

        <!-- Height -->
        <label for="height">Height:</label>
        <select id="height" name="height" required>
            <option value="tall">Tall</option>
            <option value="short">Short</option>
        </select>

        <!-- Fruit -->
        <label for="fruit">Fruiting:</label>
        <select id="fruit" name="fruit" required>
            <option value="edible">Edible</option>
            <option value="non-edible">Non-Edible</option>
        </select>

        <!-- Purpose -->
        <label for="purpose">Purpose:</label>
        <select id="purpose" name="purpose" required>
            <option value="ornamental">Ornamental</option>
            <option value="timber">Timber</option>
            <option value="shade">Shade</option>
        </select>

        <!-- Stock -->
        <label for="stock">Number in Stock:</label>
        <input type="number" id="stock" name="stock" min="0" required>

        <!-- Submit Button -->
        <button type="submit">Add Seedling</button>
    </form>
</section>

<!-- Include TinyMCE for Rich Text Editing -->
<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/55w2doib0zah7klxck5dr4nvmik36cc3rmrnmtxuhbl51z96/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: [
      // Core editing features
      'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
      // Your account includes a free trial of TinyMCE premium features
      // Try the most popular premium features until Mar 12, 2025:
      'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
    ],
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
  });
</script>


<?php include 'php/footer.php'; ?>