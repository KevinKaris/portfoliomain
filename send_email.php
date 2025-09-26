<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["message"])) {
            throw new Exception("Missing required fields");
        }
        
        $name = htmlspecialchars(trim($_POST["name"]), ENT_QUOTES, 'UTF-8');
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars(trim($_POST["message"]), ENT_QUOTES, 'UTF-8');
        
        $services = isset($_POST["services"]) ? $_POST["services"] : [];
        $services_text = !empty($services) ? implode(", ", $services) : "Not specified";
        
        if (empty($name) || empty($email) || empty($message)) {
            throw new Exception("Please fill in all required fields.");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        
        $to = "kevinkarish001@gmail.com";
        $subject = "New Contact Form Message from " . $name;
        
        // Email content
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n";
        $email_content .= "Services Interested In: $services_text\n\n";
        $email_content .= "Message:\n$message\n";
        $email_content .= "\n\n---\nThis message was sent from your portfolio contact form";
        
        // Email headers
        $headers = "From: Portfolio Contact Form <noreply@localhost>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($to, $subject, $email_content, $headers)) {
            echo json_encode([
                "status" => "success", 
                "message" => "Message sent successfully!"
            ]);
        } else {
            throw new Exception("Mail function failed. Check server mail configuration.");
        }
        
        // For local testing, we'll simulate email sending
        /*$isLocal = $_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1';
        
        if ($isLocal) {
            // Local development - save to file instead of sending email
            $filename = "contact_messages/" . date('Y-m-d_H-i-s') . "_" . uniqid() . ".txt";
            
            // Create directory if it doesn't exist
            if (!is_dir('contact_messages')) {
                mkdir('contact_messages', 0755, true);
            }
            
            // Save message to file
            file_put_contents($filename, $email_content);
            
            // Also log to XAMPP's error log for debugging
            error_log("Contact form submitted: From $name ($email)");
            
            echo json_encode([
                "status" => "success",
                "message" => "Message saved locally for testing. File: " . $filename,
                "debug" => "Local environment detected - email saved to file instead of sending"
            ]);
        } else {
            // Production - try to send actual email
            if (mail($to, $subject, $email_content, $headers)) {
                echo json_encode([
                    "status" => "success", 
                    "message" => "Message sent successfully!"
                ]);
            } else {
                throw new Exception("Mail function failed. Check server mail configuration.");
            }
        }*/
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method not allowed. Please use POST."
    ]);
}
?>