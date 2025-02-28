<?php 
include 'php/init.php'; // Start session and initialize configurations
//include 'php/header.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

include 'php/db.php';

$user_id = $_SESSION['user_id'];
$program_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($program_id <= 0) {
    echo '<p>Invalid program ID.</p>';
    exit;
}

// Fetch user details
$user_sql = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo '<p>User not found.</p>';
    exit;
}

// Fetch program details
$program_sql = "SELECT * FROM training_programs WHERE id = ?";
$program_stmt = $conn->prepare($program_sql);
$program_stmt->bind_param("i", $program_id);
$program_stmt->execute();
$program_result = $program_stmt->get_result();

if ($program_result->num_rows > 0) {
    $program = $program_result->fetch_assoc();
} else {
    echo '<p>Program not found.</p>';
    exit;
}

// Fetch certificate settings
$settings_sql = "SELECT * FROM certificate_settings LIMIT 1";
$settings_result = $conn->query($settings_sql);

if ($settings_result->num_rows > 0) {
    $settings = $settings_result->fetch_assoc();
} else {
    $settings = [
        'logo_greenearth' => 'images/logo.png',
        'logo_kenya' => 'images/kenya-logo.png',
        'signature_ceo' => 'images/default-signature.png',
        'ceo_name' => 'John Doe'
    ];
}
?>

<section class="certificate">
    <header class="certificate-header">
        <!-- Custom Logos -->
        <img src="<?php echo htmlspecialchars($settings['logo_greenearth']); ?>" alt="GreenEarth Logo" class="greenearth-logo">
        <img src="<?php echo htmlspecialchars($settings['logo_kenya']); ?>" alt="Republic of Kenya Logo" class="kenya-logo">
    </header>

    <h1>Certificate of Completion</h1>
    <p>This certifies that</p>
    <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
    <p>has successfully completed the training program:</p>
    <h3><?php echo htmlspecialchars($program['title']); ?></h3>
    <p><strong>Duration:</strong> <?php echo htmlspecialchars($program['duration']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($program['location']); ?></p>
    <p><strong>Date Completed:</strong> <?php echo date("F j, Y"); ?></p>
    <p>Issued by GreenEarth in collaboration with the Republic of Kenya.</p>

    <!-- CEO Signature -->
    <div class="ceo-signature">
        <img src="<?php echo htmlspecialchars($settings['signature_ceo']); ?>" alt="CEO Signature" class="signature-image">
        <p><strong><?php echo htmlspecialchars($settings['ceo_name']); ?></strong></p>
        <p>Chief Executive Officer</p>
    </div>

    <!-- Download Button -->
    <button onclick="downloadCertificate()" class="download-button">Download Certificate</button>
</section>

<style>
    /* Certificate Styling */
    .certificate {
        text-align: center;
        padding: 50px;
        border: 2px solid #4CAF50;
        border-radius: 10px;
        background-color: #f9f9f9;
        margin: 80px auto; /* Add space below the fixed header */
        max-width: 600px;
        font-family: Arial, sans-serif;
        position: relative; /* For logo positioning */
    }

    .certificate-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .greenearth-logo, .kenya-logo {
        width: 100px; /* Set fixed size for logos */
        height: auto;
    }

    .greenearth-logo {
        margin-left: 20px;
    }

    .kenya-logo {
        margin-right: 20px;
    }

    .certificate h1 {
        font-size: 28px;
        color: #4CAF50;
    }

    .certificate h2, .certificate h3 {
        font-size: 24px;
        margin: 10px 0;
    }

    .certificate p {
        font-size: 16px;
        margin: 5px 0;
    }

    /* CEO Signature */
    .ceo-signature {
        margin-top: 30px;
        text-align: center;
    }

    .ceo-signature img {
        max-width: 150px;
        max-height: 50px;
        margin-bottom: 10px;
    }

    .ceo-signature p {
        font-size: 14px;
        margin: 5px 0;
    }

    /* Download Button */
    .download-button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 20px;
    }

    .download-button:hover {
        background-color: #3e8e41;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    function downloadCertificate() {
        const { jsPDF } = window.jspdf;

        // Get the certificate content
        const certificateContent = document.querySelector('.certificate').innerHTML;

        // Create a new PDF instance
        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });

        // Add the certificate content to the PDF
        pdf.html(certificateContent, {
            callback: function (pdfDoc) {
                try {
                    pdfDoc.save('certificate.pdf'); // Save the PDF with a filename
                } catch (error) {
                    alert('Error generating PDF: ' + error.message);
                }
            },
            x: 10, // Horizontal offset
            y: 10, // Vertical offset
            width: 190 // Width of the content in the PDF
        });
    }
</script>

