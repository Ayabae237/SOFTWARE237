<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Prepare statement
    $stmt = $conn->prepare("SELECT id, fullname, email, password FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        
        $stmt->bind_result($id, $fullname, $db_email, $db_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $db_password)) {

            // Create session
            $_SESSION["user_id"] = $id;
            $_SESSION["fullname"] = $fullname;
            $_SESSION["email"] = $email;

            header("Location: profile.php");
            exit();

        } else {
            echo "<script>alert('Incorrect password!'); window.location='index.php';</script>";
        }

    } else {
        echo "<script>alert('Email not found!'); window.location='index.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
