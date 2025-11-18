<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTME Practice</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('main.css') }}">

</head>

<body>

<!-- 🟦 TOP BAR -->
<header class="cbt-header">
    <div class="left">
        <div class="meta">Question <span id="question-number">0</span></div>
        <div class="meta">Score: <span id="score">0</span></div>
        <div class="meta">Skipped: <span id="skipped-count">0</span> | Review: <span id="review-count">0</span></div>
    </div>
    <div class="timer" id="timer">02:00:00</div>
</header>

<!-- ===================== MAIN ===================== -->
<main class="cbt-container">

    <!-- SUBJECT SELECTION -->
    <section class="subject-screen">
        <h2>Select Four Subjects</h2>

        <div class="subject-list"></div>

        <div class="pin-box">
            <label>Enter PIN</label>
            <input type="text" id="pin-input">
        </div>

        <button class="btn-primary" id="start-btn">Start Exam</button>
    </section>

    <!-- QUIZ AREA -->
    <section class="question-screen hidden">

        <h2 class="subject-title"></h2>

        <div class="question-box">
            <div id="question-text"></div>

            <div class="question-image">
                <img id="question-img" />
            </div>
        </div>

        <div class="options-box"></div>

      <div class="nav-buttons" style="margin-top:12px; display:flex; flex-direction:column; gap:10px;">

        <!-- Submit Button (full width) -->
        <button class="btn-primary" id="submit-answer" style="width:100%;">Submit Answer</button>

        <!-- Skip + Review on one row -->
        <div style="display:flex; gap:10px; width:100%;">
            <button class="btn-secondary" id="skip-question" style="flex:1;">Skip</button>
            <button class="btn-secondary" id="review-later" style="flex:1;">Review Later</button>
        </div>

    </div>


    </section>

    <!-- RESULTS -->
    <section class="results-screen hidden">
        <h2>Exam Complete</h2>
        <p class="result-score"></p>
        <button class="btn-primary" id="restart">Start New Exam</button>
    </section>

</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('index.js') }}"></script>
</body>
</html>
