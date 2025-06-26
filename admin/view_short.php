<?php
// admin/view_short.php

// 1. Error Reporting (for debugging purposes, remove or restrict in production)
ini_set('display_errors', 1); // VERY IMPORTANT FOR DEBUGGING
ini_set('display_startup_errors', 1); // VERY IMPORTANT FOR DEBUGGING
error_reporting(E_ALL); // VERY IMPORTANT FOR DEBUGGING

// Ensure this path is correct relative to view_short.php
// Assuming db.php is in the same 'admin' directory.
// If it's in the parent directory (e.g., knews/db.php), use: require_once('../db.php');
require_once('db.php'); // <--- CONFIRM THIS PATH!

header('Content-Type: application/json'); // Tell the client we're sending JSON

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// Check if shorts_id is provided
if (!isset($_GET['shorts_id']) || !is_numeric($_GET['shorts_id'])) {
    $response['message'] = 'Invalid or missing shorts_id.';
    echo json_encode($response);
    exit();
}

$shortsId = (int)$_GET['shorts_id'];

try {
    // --- Optional: Update the total views_count in the 'shorts' table ---
    // If you want to keep a running total directly on the shorts entry, keep this.
    // Otherwise, you can remove it if analytics table is your sole source of truth.
    $stmt1 = $conn->prepare("UPDATE shorts SET views_count = views_count + 1 WHERE id = ?");
    if ($stmt1 === false) {
        // Log the error but don't stop execution if analytics update is more critical
        error_log("Failed to prepare statement for shorts table: " . $conn->error);
    } else {
        $stmt1->bind_param("i", $shortsId);
        if (!$stmt1->execute()) {
            error_log("Error updating shorts.views_count for ID " . $shortsId . ": " . $stmt1->error);
        }
        $stmt1->close();
    }

    // --- CRITICAL: Record the daily view in the 'shorts_analytics' table ---
    // This query will insert a new record for today's date if one doesn't exist for this short,
    // or increment the 'views' count if one already exists for today.
    $stmt2 = $conn->prepare("
        INSERT INTO shorts_analytics (shorts_id, views, date_viewed, ip_address, user_agent, watch_duration)
        VALUES (?, 1, CURRENT_DATE(), ?, ?, ?)
        ON DUPLICATE KEY UPDATE views = views + 1, watch_duration = watch_duration + VALUES(watch_duration)
    ");

    if ($stmt2 === false) {
        throw new Exception("Failed to prepare statement for shorts_analytics: " . $conn->error);
    }

    // Get IP address and user agent
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
    $watch_duration = $_GET['duration'] ?? 0; // Assuming you'll pass duration from frontend later

    // Bind parameters: 'isss' for integer, string, string, string
    $stmt2->bind_param("isss", $shortsId, $ip_address, $user_agent, $watch_duration); 

    if ($stmt2->execute()) {
        if ($stmt2->affected_rows > 0) {
            $response['status'] = 'success';
            $response['message'] = 'View recorded in analytics.';
        } else {
            // This might happen if the ID doesn't exist, or views_count is already at max int value.
            // Or if ON DUPLICATE KEY UPDATE didn't change anything (unlikely here, but possible for other UPDATEs)
            $response['message'] = 'Short ID ' . $shortsId . ' view already counted or no change (analytics).';
        }
    } else {
        throw new Exception("Failed to execute statement for shorts_analytics: " . $stmt2->error);
    }

    $stmt2->close();

} catch (Exception $e) {
    // Log the error for debugging
    error_log("Shorts analytics view logging error: " . $e->getMessage());
    $response['message'] = 'Database error while logging view: ' . $e->getMessage();
} finally {
    // Close connection only if it's not needed elsewhere.
    if ($conn) {
        $conn->close();
    }
}

echo json_encode($response);
exit();
?>