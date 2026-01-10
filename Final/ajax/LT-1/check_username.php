<?php
$existingUsernames = [
    'cristiano', 'ronaldo', 'neymar', 'messi', 
    'ramos', 'maldini', 'casillas', 'zarir',
    'kroos', 'beckham', 'modric', 'mbappe'
];

// Get the requested username from query parameter
$requestedUsername = isset($_GET['username']) ? strtolower(trim($_GET['username'])) : '';

// Initialize response
$response = [
    'available' => false,
    'message' => 'Username is required'
];

// Check if username was provided
if (!empty($requestedUsername)) {
    // Username validation rules
    $minLength = 3;
    $maxLength = 20;
    
    // Check length requirements
    if (strlen($requestedUsername) < $minLength) {
        $response['message'] = "Username must be at least $minLength characters";
    } elseif (strlen($requestedUsername) > $maxLength) {
        $response['message'] = "Username cannot exceed $maxLength characters";
    } else {
        // Check if username contains only allowed characters (letters, numbers, underscore)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $requestedUsername)) {
            $response['message'] = "Username can only contain letters, numbers, and underscores";
        } else {
            // Check if username exists (case-insensitive)
            $isTaken = in_array(strtolower($requestedUsername), array_map('strtolower', $existingUsernames));
            
            if ($isTaken) {
                $response['available'] = false;
                $response['message'] = "Username '$requestedUsername' is already taken";
            } else {
                $response['available'] = true;
                $response['message'] = "Username '$requestedUsername' is available!";
            }
        }
    }
}

// Set header for JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>