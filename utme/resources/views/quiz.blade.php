<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Subjects - UTME</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('main.css') }}"/>
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
  <style>
        #timer {
            font-size: 2em;
            margin: 20px;
        }

        fieldset {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        legend {
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            flex-direction: column; /* Align content in a column layout */
        }

        .image-container {
            margin-top: 10px; /* Add some spacing between the question text and the image */
            max-width: 100%; /* Ensure it doesn't overflow the form */
            overflow: hidden; /* Prevent overflow */
            text-align: center; /* Center align the image */
        }

        .image-container img {
            max-width: 100%; /* Ensure the image scales down responsively */
            height: auto; /* Maintain aspect ratio */
            border-radius: 5px; /* Optional: Add rounded corners */
        }

    </style>
</head>
<body>
    <header class="header">
            <ul>
                <li >Question Number: <span id="question-number">0</span>/180</li>
                <li id="current-score">Score: <span id="score">0/400 </span></li>
            </ul>
           <div id="timer">02:00:00</div>
    </header>
    <main>
        <section class="js-quiz">
             
            <section class ="start-quiz">
                <h2>Select your subjects combination</h2>
            </section>

            <section class ="subjectsList"></section>
             <section class ="js-answer"></section>
            <section class="js-results"></section>
        </section>
    </main>
    <script>
          //show user pin count
            document.addEventListener('DOMContentLoaded', (event) => {

                // Retrieve subjects from session storage
                const token = sessionStorage.getItem('login_token');
                
                //check if admin is authenticeated if not redirect back to login
                if(token === null){
                    window.location.href= '{{ route('guest.showLogin') }}'; 
                }

            });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{ asset('index.js') }}"></script>
</body>
</html>
