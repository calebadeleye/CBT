
var addSubjectUrl = '/api/practice';
var subjects = {}; 
var randomQuestions;
const user = JSON.parse(sessionStorage.getItem('user'));
const token =  sessionStorage.getItem('login_token');
const questions = JSON.parse(sessionStorage.getItem('questions'));
const pin = JSON.parse(sessionStorage.getItem('mypin'));


$.each(questions, function(index, item) {
    if (!subjects[item.subject]) {
        subjects[item.subject] = true;
    }
});
var subjectKeys = Object.keys(subjects);

// Map over subject keys and append to HTML

let ExamSubjects =$(`<form class ="js-quiz-form">
<ul class="radiogroup" role="radiogroup" aria-labelledby="ExamSubjects"></ul>`);
let subjectListItems = subjectKeys.map(function(subject){
    return `<label for="${subject}"><input type="checkbox" id="${subject}" name="subject" tabindex="${subject}" value="${subject}" aria-checked="false">${subject}</label><br>`;
});
let pinForm = $(`<label for="pin">Enter PIN:<input style="width : 100%;" type="text" name="pin" required></label>`);
let button = $(`<button type="submit" style="width : 100%;" id ="button-submit-quest">Submit</button></form>`)
$('.js-quiz').append(ExamSubjects);
$('.radiogroup').append(subjectListItems,pinForm, button);



$('main').on('click','#button-submit-quest', function (event){
     event.preventDefault();

    let pinValue = $('input[name="pin"]').val();
    var selectedSubjects = [];
    $('input[name="subject"]:checked').each(function() {
    selectedSubjects.push($(this).val()); 
    });

    let selectedSubs = $('input[name="subject"]:checked').length; 
    if (selectedSubs !== 4) {
         alert('Please select 4 subjects only.');
         return; // Exit the function if the condition is not met
    }

    //check if PIN is valid
    if (pinValue !== pin.pin) {
        alert('Invalid PIN.');
        return; // Exit the function if the condition is not met
 
    }

    // Check if pin exists
    if (pin.count == 10) {
        alert('You have exceeded your PIN Limit. please purchase another PIN from your dashboard.');
        return; // Exit the function if the condition is not met
    }
 
    $('.start-quiz').hide(); // Hide the section with the class 'start-quiz'
    $('.js-quiz-form').empty();
    

    let newvalue = pin.count+1;
    pin.count = newvalue;

    // Save the updated pin count back to session storage
    sessionStorage.setItem('mypin', JSON.stringify(pin));
    randomQuestions = getRandomQuestions(selectedSubjects,questions,180);
    generateQuizQuestion();
    startTimer();
      
});

//get random questions from user selected subjects
function getRandomQuestions(selectedSubjects, questions, numQuestionsPerSubject) {
    const groupedQuestions = [];

    selectedSubjects.forEach(subject => {
        // Get and shuffle questions for this subject
        const subjectQuestions = questions
            .filter(q => q.subject === subject)
            .sort(() => 0.5 - Math.random())
            .slice(0, numQuestionsPerSubject); // Adjust to your per-subject limit

        groupedQuestions.push(...subjectQuestions);
    });

    return groupedQuestions;
}


/* sets initial values to zero for the question number and score */

let qNumber = 0;
let score = 0;

function generateQuizQuestion() {

     if (qNumber == randomQuestions.length) {
        displayResults();
        return;
    }

        let currentQuestion = randomQuestions[qNumber];
        // Always clear previous content

        // Always show the current subject header
        let subject = $(`<h2 class="subject-header">${currentQuestion.subject.toUpperCase()}</h2>`);

        // Build the question form
        let question = $(`
            <form class="js-quiz-form">
                <fieldset>
                    <legend class="question">
                        ${currentQuestion.question}
                        ${currentQuestion.image ? `
                            <div class="image-container">
                                <img src="${currentQuestion.image}" onerror="this.style.display='none';"/>
                            </div>` : ''}
                    </legend>
                    <ul class="radiogroup" role="radiogroup" aria-labelledby="question"></ul>
                </fieldset>
            </form>
        `);

        // Build answers
        let answers = currentQuestion.options.map((answerValue, answerIndex) => {
            return `
                <label for="${answerValue}">
                    <input type="radio" id="${answerValue}" name="answer" tabindex="${answerIndex}" value="${answerValue}" required>
                    ${answerValue}
                </label><br>`;
        });

        // Submit button
        let button = $(`<button type="submit" style="width:100%; padding:10px;" id="button-submit">Submit</button>`);

        // Append everything
        $('.js-quiz').append(subject,question);
        $('.radiogroup').append(answers, button);

        questionNumber();
}



/* event listener for the next question button, calls the generateQuizQuestion function to display the next question */
function nextQuestion() {
        $('.js-answer').empty();
        $('.subject-header').remove();
        $('.js-quiz-form').empty();
        qNumber++;
        generateQuizQuestion();
        $('js-quiz-form').show();
}

/* displays the final percentage score and total number of correct answers */
function displayResults(){
    let finalScore = score;
    $('.js-answer').append(`<h2>UTME Results</h2>
    <img id="paint-bucket" alt="red paint bucket" src = "img/paint-bucket.jpg"/>
    <h3>Final Score ${finalScore}</h3>
    <p>You answered <span class="right-answers">${qNumber} </span>out of 180 questions right.</p>
    <button type="button" id ="button-restart">Start a New Quiz</button>`);

        // Prepare data to send to the backend
    var requestData = {
        score: finalScore,
        user_id: user.id,
        count:pin.count,
        pin:pin.pin,
    };

     // Send data to the backend
    $.ajax({
        url: '/api/leaderboard', 
        method: 'POST',
        headers: {
        'Authorization': 'Bearer ' + token,
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: requestData, // Send the data to the backend
        success: function(response) {
            console.log('Score saved');
        },
       error: function(xhr, status, error) {
            var errorMessage = '';
          if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = xhr.responseJSON.errors[0]; // Get the first error message
            } else {
                errorMessage = 'An error occurred: ' + error;
            }
            console.error(errorMessage);
        }

    });

}


/* updates the question number and displays it at the top of the page */
function questionNumber(){
    $('header').find('#question-number').text(qNumber+1);
}

/* keeps score of correct answers and displays at the top of the page */
function scoreKeeper(){
    score += parseFloat(randomQuestions[qNumber].mark);
    $('header').find('#score').text(`${score}/400`);

}


/* event listener for the submit button. Then checks to see if an input is selected, and if the answer selected is correct */
function questionChecker(){
    $('main').on('click','#button-submit', function (event){
        if ($('input:radio').is(':checked')) {
        event.preventDefault();
        let selectedAnswer= $("input[name=answer]:checked").val();

        if (selectedAnswer === randomQuestions[qNumber].answer) {
            scoreKeeper();
        }
        if (selectedAnswer) {
            nextQuestion();
        }
        }else {
            alert('Please select an answer.')
        }
    });
}


function restartQuiz(){
    console.log('restart quiz ran');
 $('main').on('click', '#button-restart', function(event){
     console.log('restart button clicked');
    score = 0;
    qNumber = 0;
    $('.js-answer').empty();
    $('.js-quiz-form').empty();
    $('.start-quiz').show();
    $('header').find('#score').text(`${score}/400`);
    $('header').find('#question-number').text(`${qNumber}`);
    window.location.reload();
 });
}

//end quiz if time is up

function endQuiz() {

    alert('Time is up! The quiz has ended.');

    //call funtion to display result
    displayResults();
}

// Timer starts here 

function startTimer(){
        const timerElement = document.getElementById('timer');
        let totalSeconds = 2 * 60 * 60; // 2 hours in seconds

        const updateTimer = () => {
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            timerElement.textContent = 
                `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            if (totalSeconds <= 0) {
                clearInterval(timerInterval);
                endQuiz();
            } else {
                totalSeconds--;
            }
        };

    updateTimer(); // Initial call to set the timer display
    const timerInterval = setInterval(updateTimer, 1000); // Update every second
}


function handleQuizApp(){
    questionChecker();
    restartQuiz();
}

/* calls the handleQuizApp to activate functions with event listeners */
$(handleQuizApp);