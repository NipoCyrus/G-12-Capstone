<?php

include 'upper.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

// If the user is logged in, retrieve their information from the session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Prepare and bind the parameter
$stmt = $conn->prepare("SELECT * FROM user WHERE userID = ?");
$stmt->bind_param("i", $user_id);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Fetch the row
    $row = $result->fetch_assoc();

    // Access the columns
    $username = $row['username'];
    // Access other columns as needed

    // Check status and redirect if necessary
    $status = $row['status']; // Assuming status column contains the user status
    if ($status == 'ban') {
        // JavaScript redirection to ban.php
        echo "<script>window.location.href = 'ban.php';</script>";
        exit(); // Make sure to exit after redirection
    } elseif ($status == 'Verified') {
        // JavaScript redirection to wait.php
        echo "<script>window.location.href = 'home.php';</script>";
        exit(); // Make sure to exit after redirection
    }

    // Proceed with other code if the user status is neither 'ban' nor 'Unverified'
} else {
    // Handle case where no user with the given userID is found
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: #0fc9e7;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .availability {
            font-size: 1rem;
            color: red;
        }

        .emoji {
            font-size: 3rem;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="emoji">🔍👀</div>

        <h1>HANG TIGHT! <?php echo $username; ?></h1>
        <p>Your account is currently under review by our super hardworking admin.</p>
        <p>Please be patient, it'll be worth the wait!</p>
        <div class="spinner"></div>
        <p class="availability">Please note: Account verification may take between 30 minutes to 6 hours and is only applicable during Monday to Friday (excluding holidays).</p>
    </div>
</body>

</html>