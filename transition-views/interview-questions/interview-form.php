<?php 
if (file_exists('menu/menu.php')) {
    include 'menu/menu.php'; 
} elseif (file_exists('../transition-views/menu/menu.php')) {
    include '../transition-views/menu/menu.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interview Questionnaire</title>
    <link rel="stylesheet" href="menu/menu-style.css">
    <style>
        body { background-color: #F4F6F8; font-family: 'Segoe UI', Arial, sans-serif; }
        .form-container {
            max-width: 800px; margin: 50px auto; background: white;
            padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        h2 { color: #1565C0; margin-bottom: 10px; }
        p.intro { color: #666; margin-bottom: 30px; }
        .form-group { margin-bottom: 25px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #333; }
        textarea {
            width: 100%; height: 120px; padding: 12px; border: 1px solid #ddd;
            border-radius: 6px; font-size: 1rem; resize: vertical;
        }
        textarea:focus { border-color: #1976D2; outline: none; }
        .submit-btn {
            background-color: #1976D2; color: white; border: none; padding: 12px 30px;
            font-size: 1rem; font-weight: bold; border-radius: 6px; cursor: pointer;
            transition: background 0.3s;
        }
        .submit-btn:hover { background-color: #1565C0; }
        .success-message {
            text-align: center; padding: 40px; color: #2e7d32;
        }
        .success-icon { font-size: 50px; margin-bottom: 20px; display: block; }
        .back-link { display: inline-block; margin-top: 20px; color: #1565C0; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-container">
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="success-message">
            <span class="success-icon">✅</span>
            <h2>Submission Received!</h2>
            <p>Your answers have been successfully submitted to the hiring team.</p>
            <p>They will review your responses and contact you shortly.</p>
            <a href="../../application-page/controller/my-applications-controller.php" class="back-link">← Back to My Applications</a>
        </div>
    <?php else: ?>
        <h2>Pre-Interview Questionnaire</h2>
        <p class="intro">The hiring team requests you answer a few questions before the scheduled interview.</p>

        <form method="POST">
            <div class="form-group">
                <label>1. Briefly describe your experience relevant to this position.</label>
                <textarea name="q1" required placeholder="Type your answer here..."></textarea>
            </div>

            <div class="form-group">
                <label>2. What motivates you to join our company specifically?</label>
                <textarea name="q2" required placeholder="Type your answer here..."></textarea>
            </div>

            <div class="form-group">
                <label>3. Describe a challenging technical problem you solved recently.</label>
                <textarea name="q3" required placeholder="Type your answer here..."></textarea>
            </div>

            <button type="submit" class="submit-btn">Submit Answers</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>