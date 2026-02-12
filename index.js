/* =========================
   UTME CBT - Final index.js (Skip = ignored, Review = revisited)
   ========================= */

/* Reusable SweetAlert */
function showAlert(title, message, icon = "warning") {
    Swal.fire({
        title: title,
        html: `<p>${message}</p>`,
        icon: icon,
        confirmButtonText: "OK",
        confirmButtonColor: "#74bc2d",
    });
}

/* Counters update (reflects review remaining in review mode) */
function updateCounters() {
    $("#skipped-count").text(skippedQuestions.length);
    if (reviewMode) {
        $("#review-count").text(Math.max(0, reviewLaterQuestions.length - reviewIndex));
    } else {
        $("#review-count").text(reviewLaterQuestions.length);
    }
}

/* =========================
   Globals & session data
   ========================= */
var addSubjectUrl = '/api/practice';
var subjects = {};
var randomQuestions = [];
var currentIndex = 0;      // index for main pass
var score = 0;
var timerInterval = null;
var questionStartTime = null;
var totalSeconds = 2 * 60 * 60; // default 2 hours

// SKIP & REVIEW (store ORIGINAL indexes of randomQuestions)
var skippedQuestions = [];       // e.g. [2, 5, 10] — these will NOT be revisited
var reviewLaterQuestions = [];   // e.g. [12, 19] — these WILL be revisited
var reviewMode = false;
var reviewIndex = 0;             // pointer inside reviewLaterQuestions

// session data (must exist)
const user = JSON.parse(sessionStorage.getItem('user') || 'null');
const token = sessionStorage.getItem('login_token');
const questions = JSON.parse(sessionStorage.getItem('questions') || '[]');
const pin = JSON.parse(sessionStorage.getItem('mypin') || 'null');

/* Build subject list from questions */
$.each(questions, function(index, item) {
    if (item && item.subject && !subjects[item.subject]) {
        subjects[item.subject] = true;
    }
});
var subjectKeys = Object.keys(subjects || {});

/* Protect <u> tags from wrapping */
function protectUnderlines(html) {
    if (!html) return '';
    return html.replace(/<u>(.*?)<\/u>/g, '<span class="no-break"><u>$1</u></span>');
}

/* Escape helper */
function escapeHtml(str) {
    if (typeof str !== 'string') return '';
    return str.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

/* =========================
   1) Render subject list
   ========================= */
function renderSubjectList() {
    const $list = $(".subject-list");
    $list.empty();
    subjectKeys.forEach(sub => {
        $list.append(`
            <label class="subject-item" style="display:block;margin-bottom:8px;cursor:pointer;">
                <input type="checkbox" name="subject" value="${sub}" style="margin-right:8px;">
                ${sub}
            </label>
        `);
    });
}
renderSubjectList();

/* =========================
   2) Start exam -> validate & fetch questions
   ========================= */
    $("#start-btn").on("click", function () {
    // -----------------------------
    // 1. Collect selected subjects
    // -----------------------------
    let selectedSubjects = [];
    $('input[name="subject"]:checked').each(function () {
        selectedSubjects.push($(this).val());
    });

    // Ensure ENG is compulsory
    // if (!selectedSubjects.includes("ENG")) {
    //     showAlert("English Required", "ENG is compulsory. Please select ENG.");
    //     return;
    // }

    // Require exactly 4 subjects
    // if (selectedSubjects.length !== 4) {
    //     showAlert("Only 4 subjects Required", "Please select exactly 4 subjects.");
    //     return;
    // }

      if (selectedSubjects.length < 1 || selectedSubjects.length > 4) {
        showAlert(
            "Invalid Selection",
            "Please select a minimum of 1 subject and a maximum of 4 subjects."
        );
        return;
    }

    // -----------------------------
    // 2. PIN validation
    // -----------------------------
    let pinValue = $("#pin-input").val().trim();
    if (!pinValue) {
        showAlert("PIN required", "Enter a valid PIN.");
        return;
    }

    if (!pin || !pin.pin) {
        showAlert("Invalid PIN", "Your pin is invalid.");
        return;
    }

    if (pin.count >= 10) {
        showAlert("PIN limit reached", "You have exceeded your PIN limit. Please purchase another PIN.");
        return;
    }

    // increment usage locally
    pin.count++;
    sessionStorage.setItem('mypin', JSON.stringify(pin));

    // -----------------------------
    // 3. Update UI
    // -----------------------------
    $(".subject-screen").addClass("hidden");
    $(".question-screen").removeClass("hidden");
    $("#question-text").html("Loading questions...");

    // -----------------------------
    // 4. Start exam session
    // -----------------------------
    $.ajax({
        url: '/api/exam/start',
        type: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            user_id: user?.id,
        },
        success(res) {
            // Store exam session ID
            sessionStorage.setItem('exam_session_id', res.exam_session_id);

            // -----------------------------
            // 5. Fetch questions from backend
            // -----------------------------
            $.ajax({
                url: addSubjectUrl, // your existing API to get question data
                method: "POST",
                data: {
                    subject: selectedSubjects,
                    pin: pinValue,
                    user_id: user?.id
                },
                headers: {
                    "Authorization": "Bearer " + token,
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    randomQuestions = response.data || [];
                    if (!randomQuestions.length) {
                        showAlert("No questions", "No questions returned for the selected subjects.");
                        $(".subject-screen").removeClass("hidden");
                        $(".question-screen").addClass("hidden");
                        return;
                    }

                    // -----------------------------
                    // 6. Reset state
                    // -----------------------------
                    currentIndex = 0;
                    score = 0;
                    skippedQuestions = [];
                    reviewLaterQuestions = [];
                    reviewMode = false;
                    reviewIndex = 0;
                    $("#score").text(`${score}/400`);
                    updateCounters();

                    // -----------------------------
                    // 7. Load first question
                    // -----------------------------
                    loadQuestion();

                    // -----------------------------
                    // 8. Start timer
                    // -----------------------------
                    startTimer();
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON?.message || xhr.responseText || "Failed to load questions.";
                    showAlert("Error", msg, "error");
                    $(".subject-screen").removeClass("hidden");
                    $(".question-screen").addClass("hidden");
                }
            });
        },
        error(xhr) {
            let msg = xhr.responseJSON?.message || xhr.responseText || "Unable to start exam session";
            showAlert("Error", msg, "error");
        }
    });
});


/* =========================
   3) Load main question (normal pass)
   ========================= */
function loadQuestion() {

    // RESET PER-QUESTION TIMER
    questionStartTime = Date.now();

    // if main pass finished
    if (!randomQuestions || currentIndex >= randomQuestions.length) {
        // Only prompt for review if there are reviewLaterQuestions
        if (reviewLaterQuestions.length > 0) {
            handleEndOfFirstPass();
        } else {
            showResults();
        }
        return;
    }

    const q = randomQuestions[currentIndex];

    $("#question-number").text(`${currentIndex + 1} / ${randomQuestions.length}`);
    $(".subject-title").text(q.subject || "");
    $("#question-text").html(protectUnderlines(q.question || ""));

    // image fallback
    if(q.image){
        $("#question-img").attr("src", q.image).show().off('error').on('error', function(){ $(this).hide(); });
    } else {
        $("#question-img").hide();
    }

    // render options
    $(".options-box").empty();
    const options = q.options || [];

    if (Array.isArray(options)) {
        options.forEach((optText, idx) => {
            $(".options-box").append(`
                <label class="option" style="display:block;padding:10px;border-radius:6px;border:1px solid #ddd;margin-bottom:8px;cursor:pointer;">
                    <input type="radio" name="option" value="${idx}" data-text="${escapeHtml(optText)}" style="margin-right:8px;">
                    ${optText}
                </label>
            `);
        });
    } else {
        Object.keys(options).forEach(key => {
            $(".options-box").append(`
                <label class="option" style="display:block;padding:10px;border-radius:6px;border:1px solid #ddd;margin-bottom:8px;cursor:pointer;">
                    <input type="radio" name="option" value="${key}" data-text="${escapeHtml(options[key])}" style="margin-right:8px;">
                    <strong>${key}.</strong> ${options[key]}
                </label>
            `);
        });
    }

    // ensure nav buttons exist (inline single row)
    if ($(".nav-buttons").length === 0) {
        $(".question-screen").append(`
            <div class="nav-buttons" style="display:flex;gap:12px;margin-top:12px;">
                <button class="btn-primary" id="submit-answer">Submit Answer</button>
                <button class="btn-secondary" id="skip-question">Skip</button>
                <button class="btn-secondary" id="review-later">Review Later</button>
            </div>
        `);
    }
}

/* =========================
   4) Skip & Review handlers (store ORIGINAL indexes)
   ========================= */

// SKIP - permanently ignore (does not add to review)
$(document).on("click", "#skip-question", function(){
    if (!randomQuestions || currentIndex >= randomQuestions.length) return;

    const originalIndex = currentIndex; // original index in randomQuestions

    // avoid duplicates
    if (!skippedQuestions.includes(originalIndex)) skippedQuestions.push(originalIndex);

    // advance main pointer
    currentIndex++;
    // clear selection
    $('input[name="option"]').prop('checked', false);
    updateCounters();
    loadQuestion();
});

// REVIEW LATER - add original index to review list (unique)
$(document).on("click", "#review-later", function(){
    if (!randomQuestions || currentIndex >= randomQuestions.length) return;

    const originalIndex = currentIndex;

    if (!reviewLaterQuestions.includes(originalIndex)) reviewLaterQuestions.push(originalIndex);

    currentIndex++;
    $('input[name="option"]').prop('checked', false);
    updateCounters();
    loadQuestion();
});

/* =========================
   5) Single unified Submit handler (normal pass & review mode)
   ========================= */
    $(document).on("click", "#submit-answer", function () {

    // -----------------------------
    // 1. VALIDATE FLOW STATE
    // -----------------------------
    if (reviewMode) {
        if (reviewIndex >= reviewLaterQuestions.length) {
            showResults();
            return;
        }
    } else {
        if (!randomQuestions || currentIndex >= randomQuestions.length) {
            handleEndOfFirstPass();
            return;
        }
    }

    // -----------------------------
    // 2. ENSURE OPTION SELECTED
    // -----------------------------
    const $selected = $('input[name="option"]:checked');
    if (!$selected.length) {
        showAlert("Select an answer", "Please choose an option.");
        return;
    }

    // -----------------------------
    // 3. RESOLVE QUESTION
    // -----------------------------
    const originalIndex = reviewMode
        ? reviewLaterQuestions[reviewIndex]
        : currentIndex;

    const q = randomQuestions[originalIndex];

    // -----------------------------
    // 4. DETERMINE SELECTED ANSWER
    // -----------------------------
    const selectedText =
        $selected.attr("data-text") ||
        $selected.parent().text().trim();

    const correct = q.answer;
    let isCorrect = false;

    // Support letter-based answers (A, B, C...) or text-based
    if (
        typeof correct === 'string' &&
        correct.length === 1 &&
        !Array.isArray(q.options)
    ) {
        isCorrect = String($selected.val()) === String(correct);
    } else {
        isCorrect = String(selectedText).trim() === String(correct).trim();
    }

    // -----------------------------
    // 5. SAVE ATTEMPT (CRITICAL PART)
    // -----------------------------
    const timeSpent = Math.floor((Date.now() - questionStartTime) / 1000);

    $.ajax({
        url: '/api/exam/attempt',
        type: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,             // your API token
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF
        },
        data: {
            exam_session_id: sessionStorage.getItem('exam_session_id'),
            question_id: q.id,
            selected_option: selectedText,
            is_correct: isCorrect ? 1 : 0,
            time_spent: timeSpent
        },
        success: function(res) {
            //console.log('Attempt saved', res);
        },
        error: function(xhr) {
            console.error('Error saving attempt:', xhr.responseText || xhr.statusText);
            showAlert("Error", "Failed to save answer. Please try again.", "error");
        }
    });


    // -----------------------------
    // 6. SCORE CALCULATION
    // -----------------------------
    let mark = 2;
    if (q.mark !== undefined && !isNaN(parseFloat(q.mark))) {
        mark = parseFloat(q.mark);
    }

    if (isCorrect) score += mark;

    $("#score").text(`${score}/400`);

    // -----------------------------
    // 7. CLEAN REVIEW QUEUE
    // -----------------------------
    const removeFromArray = (arr, val) => {
        const pos = arr.indexOf(val);
        if (pos !== -1) arr.splice(pos, 1);
    };

    removeFromArray(reviewLaterQuestions, originalIndex);

    // -----------------------------
    // 8. RESET UI STATE
    // -----------------------------
    $('input[name="option"]').prop('checked', false);

    // -----------------------------
    // 9. NAVIGATION LOGIC
    // -----------------------------
    if (reviewMode) {
        if (reviewIndex >= reviewLaterQuestions.length) {
            showResults();
            return;
        }

        updateCounters();
        loadReviewQuestion();
        return;
    }

    currentIndex++;
    updateCounters();
    loadQuestion();
});

/* =========================
   6) End of first pass: prompt only if review list exists
   ========================= */
function handleEndOfFirstPass() {
    const reviewCount = reviewLaterQuestions.length;

    if (reviewCount === 0) {
        // nothing to review; submit results immediately
        showResults();
        return;
    }

    // Confirm with user if they want to review marked questions
    Swal.fire({
        title: "Review Pending Questions?",
        html: `
            <p>You marked <strong>${reviewCount}</strong> questions for review.</p>
            <p>Do you want to answer them now?</p>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, take me there",
        cancelButtonText: "No, finish exam",
        confirmButtonColor: "#74bc2d"
    }).then((result) => {
        if (result.isConfirmed) {
            startReviewMode();
        } else {
            showResults();
        }
    });
}

/* =========================
   7) Review mode functions (only reviewLaterQuestions are shown)
   ========================= */
function startReviewMode() {
    // remove duplicates and normalize (shouldn't be necessary but safe)
    reviewLaterQuestions = Array.from(new Set(reviewLaterQuestions));
    reviewMode = true;
    reviewIndex = 0;
    updateCounters();
    loadReviewQuestion();
}

function loadReviewQuestion() {

    // RESET PER-QUESTION TIMER
    questionStartTime = Date.now();
    if (!reviewLaterQuestions || reviewLaterQuestions.length === 0 || reviewIndex >= reviewLaterQuestions.length) {
        showResults();
        return;
    }

    const originalIndex = reviewLaterQuestions[reviewIndex];
    const q = randomQuestions[originalIndex];

    $("#question-number").text(`${reviewIndex + 1} (Review) / ${reviewLaterQuestions.length}`);
    $(".subject-title").text(q.subject || "");
    $("#question-text").html(protectUnderlines(q.question || ""));

    if(q.image){
        $("#question-img").attr("src", q.image).show().off('error').on('error', function(){ $(this).hide(); });
    } else {
        $("#question-img").hide();
    }

    // render options for review question
    $(".options-box").empty();
    const options = q.options || [];

    if (Array.isArray(options)) {
        options.forEach((optText, idx) => {
            $(".options-box").append(`
                <label class="option" style="display:block;padding:10px;border-radius:6px;border:1px solid #ddd;margin-bottom:8px;cursor:pointer;">
                    <input type="radio" name="option" value="${idx}" data-text="${escapeHtml(optText)}" style="margin-right:8px;">
                    ${optText}
                </label>
            `);
        });
    } else {
        Object.keys(options).forEach(key => {
            $(".options-box").append(`
                <label class="option" style="display:block;padding:10px;border-radius:6px;border:1px solid #ddd;margin-bottom:8px;cursor:pointer;">
                    <input type="radio" name="option" value="${key}" data-text="${escapeHtml(options[key])}" style="margin-right:8px;">
                    <strong>${key}.</strong> ${options[key]}
                </label>
            `);
        });
    }
}

/* =========================
   8) Timer
   ========================= */
function startTimer(seconds) {
    if (typeof seconds === 'number') totalSeconds = seconds;
    const timerEl = document.getElementById('timer');
    if (!timerEl) return;

    if (timerInterval) clearInterval(timerInterval);

    const updateTimer = () => {
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const secs = totalSeconds % 60;
        timerEl.textContent = `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;

        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            endQuiz();
        } else {
            totalSeconds--;
        }
    };

    updateTimer();
    timerInterval = setInterval(updateTimer, 1000);
}

function endQuiz() {
    showAlert("Time's up!", "The quiz has ended.", "info");
    if (timerInterval) clearInterval(timerInterval);
    showResults();
}

/* =========================
   9) Show results & save to leaderboard
   ========================= */
function showResults() {
    if (timerInterval) clearInterval(timerInterval);

    $(".question-screen").addClass("hidden");
    $(".results-screen").removeClass("hidden");
    $(".result-score").text(`Your Score: ${score}/400`);

    const requestData = {
        score: score,
        user_id: user?.id,
        count: pin?.count ?? 0,
        pin: pin?.pin,
        exam_session_id: sessionStorage.getItem('exam_session_id'),
        time_used: (2 * 60 * 60) - totalSeconds,
    };

    $.ajax({
        url: '/api/leaderboard',
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: requestData,
        success: function(res) {
            console.log('Score saved to leaderboard', res);
        },
        error: function(xhr) {
            console.error('Failed to save leaderboard score:', xhr.responseText || xhr.statusText);
        }
    });
}

/* =========================
   10) Restart
   ========================= */
$("#restart").on("click", function () {
    location.reload();
});

/* =========================
   11) Quick keyboard + CSS
   ========================= */
$("#pin-input").on("keydown", function(e){
    if (e.key === 'Enter') $("#start-btn").click();
});

(function injectNoBreakStyle(){
    const css = `.no-break{white-space:nowrap;} .option input{vertical-align:middle;}`;
    const style = document.createElement('style');
    style.appendChild(document.createTextNode(css));
    document.head.appendChild(style);
})();

/* =========================
   12) Initialization safety
   ========================= */
$(function(){
    if (!user || !token) {
        window.location.href = '{{ route("guest.showLogin") }}';
    }
    updateCounters();
});
