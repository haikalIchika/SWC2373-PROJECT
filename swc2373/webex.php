<!DOCTYPE html>
<html>

<head>
    <meta charset="utf8">
    <title>Student Portal</title>
    <link rel="stylesheet" href="https://code.s4d.io/widget-recents/production/main.css">
    <link rel="stylesheet" href="https://code.s4d.io/widget-space/production/main.css">
    <script src="https://code.s4d.io/widget-space/production/bundle.js"></script>
    <script src="https://code.s4d.io/widget-recents/production/bundle.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        html {
            height: 100%;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: peachpuff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        #access-token-form {
            background-color: black;
            color: #ffffff;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
        }

        #access-token-form label {
            font-size: 18px;
        }

        #access-token {
            width: 300px;
            padding: 10px;
            font-size: 16px;
        }

        #create-space-form {
            display: none;
        }

        #create-space-form label {
            font-size: 18px;
        }

        #create-space-name {
            width: 300px;
            padding: 10px;
            font-size: 16px;
        }

        #widgets-container {
            display: none;
            padding: 20px;
            text-align: center;
        }

        #recents {
            width: 300px;
            height: 600px;
            float: left;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 20px;
        }

        #space {
            width: 800px;
            height: 600px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .button {
            width: 120px;
            height: 35px;
            background-color: greenyellow;
        }
    </style>
</head>

<body>

    <!-- Access Token Input Form -->
    <div id="access-token-form">
        <label for="access-token">Please Enter Your Webex API Access Token:<br></label>
        <br>
        <input type="text" id="access-token" placeholder="Paste your token here">
        <br><br>
        <button class="button" onclick="setAccessToken()">Submit</button>
    </div>

    <!-- Create Space Form -->
    <div id="create-space-form">
        <label for="create-space-name">Enter Space Name:</label>
        <br>
        <input type="text" id="create-space-name" placeholder="Space Name">
        <br><br>
        <button class="button" onclick="createNewSpace()">Create Space</button>
    </div>

   
    <!-- Recents and Space Widgets -->
    <div id="widgets-container" style="display: none;">
        <div id="recents"></div>
        <div id="space"></div>
    </div>


    <div id="schedule-meeting-form">
        <label for="meeting-date-time">Meeting Date and Time:</label>
        <br>
        <input type="datetime-local" id="meeting-date-time">
        <br><br>
        <label for="meeting-duration">Meeting Duration:</label>
        <br>
        <input type="number" id="meeting-duration" min="1">
        <br><br>
        <button class="button" onclick="scheduleMeeting()">Schedule Meeting</button>
    </div>

    <script>
        function setAccessToken() {
            const accessTokenInput = document.getElementById('access-token');
            const token = accessTokenInput.value.trim();

            if (token) {
                hideAccessTokenForm();
                initializeWidgets(token);
            } else {
                alert('Please enter a valid access token.');
            }
        }

        function hideAccessTokenForm() {
            const accessTokenForm = document.getElementById('access-token-form');
            accessTokenForm.style.display = 'none';

            const createSpaceForm = document.getElementById('create-space-form');
            createSpaceForm.style.display = 'block';

            const widgetsContainer = document.getElementById('widgets-container');
            widgetsContainer.style.display = 'block';
        }

        function createNewSpace() {
            const spaceNameInput = document.getElementById('create-space-name');
            const spaceName = spaceNameInput.value.trim();
            const accessToken = document.getElementById('access-token').value.trim();

            if (spaceName) {
                // Redirect to the PHP file for creating a new space with the provided space name
                window.location.href = `create-space.php?token=${accessToken}&space_name=${encodeURIComponent(spaceName)}`;
            } else {
                alert('Please enter a space name.');
            }
        }

      


        function initializeWidgets(token) {
            // Init the Recents widget
            const recentsElement = document.getElementById('recents');
            webex.widget(recentsElement).recentsWidget({
                accessToken: token,
                onEvent: callback
            });

            function callback(type, event) {
                if (type === "rooms:selected") {
                    const selectedRoom = event.data.id;
                    const spaceElement = document.getElementById('space');

                    // Remove existing 'Space' widget (if any)
                    try {
                        webex.widget(spaceElement).remove().then(function (removed) {
                            if (removed) {
                                console.log('removed!');
                            }
                        });
                    } catch (err) {
                        console.error('could not remove Space widget :-(, continuing...');
                    }

                    // Inject a new 'Space' widget with the selected room
                    webex.widget(spaceElement).spaceWidget({
                        accessToken: token,
                        destinationType: "spaceId",
                        destinationId: selectedRoom,
                        activities: {
                            "files": true,
                            "meet": true,
                            "message": true,
                            "people": true
                        },
                        initialActivity: 'message',
                        secondaryActivitiesFullWidth: false
                    });
                }
            }
        }

        function scheduleMeeting() {
            const dateAndTimeInput = document.getElementById('meeting-date-time');
            const durationInput = document.getElementById('meeting-duration');
            const accessToken = document.getElementById('access-token').value.trim();

            const dateAndTime = dateAndTimeInput.value.trim();
            const duration = durationInput.value.trim();

            if (dateAndTime && duration) {
               
                console.log('Meeting Scheduled:');
                console.log('Date and Time:', dateAndTime);
                console.log('Duration:', duration);

            } else {
                alert('Please enter valid date and time, and duration for the meeting.');
            }
        }
        
    </script>

</body>

</html>
