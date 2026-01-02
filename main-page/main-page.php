<?php 
include '../transition-views/menu/menu.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to JobBoard</title>
    <link rel="stylesheet" href="../main-page/CSS/main-style.css">
    <link rel="stylesheet" href="../../transition-views/menu/menu-style.css">
</head>
<body>

<div class="container">
    
    <div class="hero-section">
        <h1>Building Bridges Between Talent and Opportunity</h1>
        <p>Your career journey starts here. Discover how we connect ambitious professionals with world-class companies in a seamless, efficient environment.</p>
    </div>

    <div class="feature-row">
        <div class="text-content">
            <h2>Unlock Your True Potential</h2>
            <p>
                In today's rapidly evolving job market, finding the right role is about more than just a paycheck; it is about finding a culture where you can thrive. Our platform is designed with the candidate in mind, offering intuitive search tools that allow you to filter opportunities by seniority, location, and industry specificities. We believe that every professional deserves a career path that aligns with their personal values and long-term goals.
            </p>
            <p>
                Whether you are a junior developer looking for your first break or a senior executive seeking a new challenge, our extensive database of active listings ensures you never miss an opportunity. Create your profile, track your applications in real-time, and take the next decisive step towards your dream career today.
            </p>
            <a href="../job-page/controller/job-browse-controller.php" class="cta-btn">Browse Openings</a>
        </div>
        <div class="image-content">
            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Professional working on laptop">
        </div>
    </div>

    <div class="feature-row reverse">
        <div class="text-content">
            <h2>Streamlined Recruitment for Modern Companies</h2>
            <p>
                For employers, time is the most valuable asset. The traditional hiring process is often cluttered with administrative overhead and inefficient communication channels. We have reimagined the recruitment workflow to put control back in your hands. With our powerful company dashboard, you can manage job postings, screen candidates, and track application statuses all in one centralized hub.
            </p>
            <p>
                Our platform allows you to showcase your company culture and attract top-tier talent who are genuinely interested in your mission. From posting a new position to marking a candidate as "Hired," every step is designed to be frictionless. Join thousands of forward-thinking companies who use our tools to build their dream teams faster and smarter.
            </p>
            <a href="../job-page/controller/post_job-controller.php" class="cta-btn">Post a Job</a>
        </div>
        <div class="image-content">
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Team collaboration in office">
        </div>
    </div>

    <div class="feature-row">
        <div class="text-content">
            <h2>A Community Built on Trust and Transparency</h2>
            <p>
                We understand that the recruitment process is deeply human. It relies on trust between the applicant and the organization. That is why we have implemented robust features to ensure the quality of our listings. We verify company profiles and provide transparent application tracking so candidates are never left in the dark about their status.
            </p>
            <p>
                By fostering an ecosystem of transparency, we reduce the anxiety associated with job hunting and hiring. Our detailed audit logs and secure data handling ensure that your information is always protected. Whether you are screening applications or waiting for an interview call, you can rely on our platform to provide a professional, secure, and respectful experience for all users.
            </p>
        </div>
        <div class="image-content">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Handshake and trust">
        </div>
    </div>

</div>

</body>
</html>