<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
            border-top: 5px solid #4CAF50;
        }

        h1 {
            color: #2E7D32;
            font-size: 24px;
            margin-bottom: 15px;
        }

        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-home {
            display: inline-block;
            background-color: #1565C0;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-home:hover {
            background-color: #0D47A1;
        }

        .icon {
            font-size: 60px;
            color: #4CAF50;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="icon">✔</div>
        <h1>Application Sent!</h1>
        <p>
            You have successfully applied for this position.
            <br>
            Good luck! You can track this application in your dashboard.
        </p>
        
        <a href="../../main-page/main-page.php" class="btn-home">Return to Main Page</a>
    </div>

</body>
</html>
