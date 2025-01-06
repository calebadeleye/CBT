@extends('layout.master')

@section('title', 'Questions | Questions :: UTME')
    <!-- //header -->
    <!-- banner -->
@section('banner')
    <div class="inner-banner-w3ls text-right d-flex align-items-center">
        <div class="container">
            <h6 class="agileinfo-title">Questions </h6>
            <ol class="breadcrumb-parent d-flex justify-content-end">
                <li class="breadcrumb-nav">
                    <a href="/">Home</a>
                </li>
                <li class="breadcrumb-nav active  text-capitalize" aria-current="page">Questions</li>
            </ol>
        </div>
    </div>
@endsection
    <!-- //banner -->
    <!-- courses -->
@section('content')
    <section class="courses-sec py-5">
        <div class="container py-lg-5">
            <div class="title-section pb-4">
                <h3 class="main-title-agile">Add Question</h3>
                <div class="title-line">
                </div>
            </div>
            <div class="error-messages"></div>
             <div class="leave-coment-form add-question-form"></div>
             <div class="loading" id="loadingMessage">Loading, please wait...</div>
        </div>
    <script src="{{ asset('js/jquery-2.2.3.min.js') }}"></script>

       
     <script>
        $(document).ready(function() {


            const subjects = JSON.parse(sessionStorage.getItem('subjects'));
            const subtopics = JSON.parse(sessionStorage.getItem('subjects_topic'));

            // Create the  form
            let AddQuestion = $('<form class="js-quiz-form" enctype="multipart/form-data">');

            const  subs = $('<select id="subjectSelect" class="form-control mb-3"><option value="" required="">Select a Subject</option></select>');

            for (const key in subjects) {
                    if (subjects.hasOwnProperty(key)) {
                        const option = $('<option></option>').val(key).text(subjects[key]);
                        // if (key == question.subject) {
                        //     option.prop('selected', true);
                        // }
                        subs.append(option);
                    }
                }

            // Create the select element for subtopics
            const subs_topic = $('<select id="subtopicSelect" class="form-control mb-3"><option value="" required="">Select a Sub Topic</option></select>');

            for (const key in subtopics) {
                 if (subtopics.hasOwnProperty(key)) {
                        const option = $('<option></option>').val(key).text(key);
                        subs_topic.append(option);
                    }
                }

            let quest= $(`<div class="form-group"><label>Question*</label><textarea name="question" class="form-control wysiwyg"></textarea></div>`);

            let questImg= $(`<div class="form-group"><label>Image(Optional)</label><input type="file" name="file" class="form-control"></div>`);

            let optiona = $(`<div class="form-group"><label>Option A*</label><textarea name="optiona" class="form-control wysiwyg"></textarea></div>`);

            let optionb = $(`<div class="form-group"><label>Option B*</label><textarea name="optionb" class="form-control wysiwyg"></textarea></div>`);

            let optionc = $(`<div class="form-group"><label>Option C*</label><textarea name="optionc" class="form-control wysiwyg"></textarea></div>`);

            let optiond = $(`<div class="form-group"><label>Option D*</label><textarea name="optiond" class="form-control wysiwyg"></textarea></div>`);

            let answer = $(`<div class="form-group"><label>Answer*</label><textarea name="answer" class="form-control wysiwyg"></textarea></div>`);
            
            let button = $('<div class="mm_single_submit"><input type="submit" value="Submit" class="save btn btn-primary"></div>');

             // Append all form elements to the form
            AddQuestion.append(subs, subs_topic, quest,questImg, optiona, optionb, optionc, optiond, answer, button);

            // Close the form tag
            AddQuestion.append('</form>');

             // Append the form to the edit form container
            $('.add-question-form').append(AddQuestion);


            //$('.add-question-form').show(); // show the edit form

            // Handle form submission
            $(document).on('submit', '.js-quiz-form', function(event) {
                event.preventDefault();
                const token = sessionStorage.getItem('admin_token');
                const form = $(this);
                const fileInput = form.find('input[type="file"]')[0];
                const file = fileInput.files[0];
               
                // Disable the submit button and show the loading message
                $('#submit').prop('disabled', true);
                $('#loadingMessage').show();

                 if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const base64Image = event.target.result;
                        sendFormData(base64Image); // Proceed with form submission including base64 image
                    };
                    reader.readAsDataURL(file);
                } else {
                    sendFormData(null); // Proceed with form submission without image
                }

                function sendFormData(base64Image) {

                    const subtopics = JSON.parse(sessionStorage.getItem('subjects_topic'));
                    const subject = form.find('#subjectSelect').val();
                    const subtopic = form.find('#subtopicSelect').val();
                    const subtopicValue = subtopics[subtopic];

                    // Collect form data
                    const formData = {
                        subject: subject,
                        subtopic: {
                            key: subtopic,
                            value: subtopicValue
                        },
                        question: form.find('textarea[name="question"]').val(),
                        options: [
                            form.find('textarea[name="optiona"]').val(),
                            form.find('textarea[name="optionb"]').val(),
                            form.find('textarea[name="optionc"]').val(),
                            form.find('textarea[name="optiond"]').val()
                        ],
                        answer: form.find('textarea[name="answer"]').val(),
                        image: base64Image // Placeholder for the image
                    };
                    $('.error-messages').empty();
                    console.log(formData);
                    // Send data to backend via AJAX
                    $.ajax({
                        url: '/api/question',
                        method: 'POST',
                        data: JSON.stringify(formData),
                        contentType: 'application/json',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Response:', response);
                            alert('Question added successfully');
                             // Clear the form
                             $('.js-quiz-form')[0].reset();
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = 'An error occurred.';

                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                let errorMessages = '';

                                $.each(xhr.responseJSON.errors, function(field, messages) {
                                    $.each(messages, function(index, message) {
                                        errorMessages += '<p>' + message + '</p>';
                                    });
                                });

                                $('.error-messages').html(errorMessages);

                                 // Scroll to the error messages section
                                $('html, body').animate({
                                    scrollTop: $('.error-messages').offset().top
                                 }, 500); // Adjust the duration as needed
                            } else {
                                $('.error-messages').html('<p>An unexpected error occurred.</p>');
                            }
                        },
                        complete: function(){
                             // Re-enable the submit button and hide the loading message
                            $('#submit').prop('disabled', false);
                            $('#loadingMessage').hide();
                        }
                    });

                }
               
            });

            const admin_token = sessionStorage.getItem('admin_token');
                
            //check if admin is authenticeated if not redirect back to login
            if(admin_token === null){
                window.location.href= '{{ route('guest.showAdminLogin') }}'; 
            }

        });
    </script>

     <style>
        .mm_single_submit {
            display: flex;
            flex-direction: column; /* Arrange items in a column */
            gap: 10px; /* Space between buttons */
            margin-top: 20px;
        }
        
        .mm_single_submit button, 
        .mm_single_submit input[type="submit"] {
            width: 100%; /* Full width for buttons */
        }
        .error-messages{
            color: red !important;
            font-weight: bold;
            padding: 10px;
        }
    </style>


    </section>
@endsection
    <!-- //courses -->