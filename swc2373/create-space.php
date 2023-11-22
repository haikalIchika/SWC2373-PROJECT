<?php

// Check if a Webex token and space name are provided
if (!isset($_GET['token']) || !isset($_GET['space_name'])) {
    echo 'Access Token or Space Name not provided.';
    exit;
}

$accessToken = $_GET['token'];
$spaceName = $_GET['space_name'];

// Create a new space using the Webex API
function createSpace($accessToken, $spaceName)
{
    $url = 'https://webexapis.com/v1/rooms';

    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken,
    );

    $data = array(
        'title' => $spaceName,
    );

    $options = array(
        'http' => array(
            'header'  => implode("\r\n", $headers),
            'method'  => 'POST',
            'content' => json_encode($data),
        ),
    );

    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        // Handle error
        echo 'Error creating space.<br>';

        // Output additional information for debugging
        $lastError = error_get_last();
        echo '<pre>';
        print_r($lastError);
        echo '</pre>';

        exit;
    }

    $responseData = json_decode($response, true);

    return $responseData['id']; // Return the ID of the created space
}

// Create a new space and get its ID
$newSpaceId = createSpace($accessToken, $spaceName);

if (!$newSpaceId) {
    echo 'Error creating space.';
    exit;
}

// Redirect to the new space using its ID
header("Location: webex.php?token=$accessToken&space=$newSpaceId");
exit;
?>
