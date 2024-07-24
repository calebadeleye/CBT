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
                <h3 class="main-title-agile">All Questions</h3>
                <div class="title-line">
                </div>
            </div>

          
            <div id="products" class="row view-group"></div>
            <div id="pagination" class="pagination"></div>
             <div class="leave-coment-form edit-question-form"></div>
             <div class="loading" id="loadingMessage">Loading, please wait...</div>
        </div>
<script src="{{ asset('js/jquery-2.2.3.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const questions = JSON.parse(sessionStorage.getItem('questions'));
            const questionsPerPage = 50;
            let currentPage = 1;

            const productsContainer = document.getElementById('products');
            const paginationContainer = document.getElementById('pagination');

            const renderQuestions = (questions, page) => {
                productsContainer.innerHTML = '';
                const start = (page - 1) * questionsPerPage;
                const end = start + questionsPerPage;
                const paginatedQuestions = questions.slice(start, end);

                paginatedQuestions.forEach(question => {
                    const itemDiv = document.createElement('div');
                    itemDiv.classList.add('item', 'col-lg-4');
                    itemDiv.innerHTML = `
                        <div class="thumbnail card">
                            <div class="caption card-body">
                                <h4 class="group card-title inner list-group-item-heading">
                                    ${question.subject}</h4>
                                <p class="group inner list-group-item-text">
                                    ${question.question}</p>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="lead">
                                            ${new Date(question.created_at).toLocaleDateString()}</p>
                                    </div>
                                    <div class="col-6">
                                        <a class="btn btn-course edit-btn" href="#" data-id="${question.id}">Edit</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    productsContainer.appendChild(itemDiv);
                });
            };

            const renderPagination = (questions, currentPage) => {
                paginationContainer.innerHTML = '';
                const totalPages = Math.ceil(questions.length / questionsPerPage);

                const createPageLink = (page, label = page, active = false, disabled = false) => {
                    const pageLink = document.createElement('a');
                    pageLink.textContent = label;
                    if (active) pageLink.classList.add('active');
                    if (disabled) pageLink.classList.add('disabled');
                    pageLink.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (!disabled) {
                            currentPage = page;
                            renderQuestions(questions, currentPage);
                            renderPagination(questions, currentPage);
                        }
                    });
                    return pageLink;
                };

                // Previous Page Link
                paginationContainer.appendChild(createPageLink(currentPage - 1, '«', false, currentPage === 1));

                // Page Numbers
                for (let i = 1; i <= totalPages; i++) {
                    paginationContainer.appendChild(createPageLink(i, i, i === currentPage));
                }

                // Next Page Link
                paginationContainer.appendChild(createPageLink(currentPage + 1, '»', false, currentPage === totalPages));
            };

            if (questions && Array.isArray(questions)) {
                renderQuestions(questions, currentPage);
                renderPagination(questions, currentPage);
            } else {
                console.log('No questions found in session storage.');
            }
        });   
    </script>

     <script>
        $(document).ready(function() {
            // JSON array

             const questions = JSON.parse(sessionStorage.getItem('questions'));
             
            // Function to find an object by ID
            function findQuestionById(id) {
                let result = null;
                $.each(questions, function(index, question) {
                    if (question.id === id) {
                        result = question;
                        return false; // Break the loop
                    }
                });
                return result;
            }

            // Attach click event listener to dynamically created edit buttons
            $(document).on('click', '.edit-btn', function(event) {
                event.preventDefault();
                const id = $(this).data('id');
                const question = findQuestionById(id);
                const subjects = JSON.parse(sessionStorage.getItem('subjects'));
                const subtopics = JSON.parse(sessionStorage.getItem('subjects_topic'));

                // Create the edit form
                let EditQuestion = $('<form class="js-quiz-form" enctype="multipart/form-data">');

                const  subs = $('<select id="subjectSelect" class="form-control mb-3"><option value="" required="">Select a Subject</option></select>');

               for (const key in subjects) {
                        if (subjects.hasOwnProperty(key)) {
                            const option = $('<option></option>').val(key).text(subjects[key]);
                            if (key == question.subject) {
                                option.prop('selected', true);
                            }
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


                if (question) {
                     question;
                       // Clear the edit form container
                        $('.edit-question-form').empty();

                        let quest= $(`<div class="form-group">
                                        <label>Question*</label>
                                        <textarea name="question" class="form-control" 
                                            required>${question.question}</textarea>
                                    </div>`);

                        let questImg= $(`<div class="form-group">
                                        <label>Image(Optional)</label>
                                        <input type="file" value="${question.img}" name="file" class="form-control"></div>`);
                        let questid= $(`<div class="form-group">
                                        <input type="hidden" value="${question.id}" name="questid" />
                                    </div>`);
                        let optiona = $(`<div class="form-group">
                                     <label>Option A*</label>
                                    <textarea name="optiona" class="form-control"
                                        required>${question.options[0]}</textarea>
                                </div>`);
                        let optionb = $(`<div class="form-group">
                                     <label>Option B*</label>
                                     <textarea name="optionb" class="form-control" 
                                        required>${question.options[1]}</textarea>
                                </div>`);
                        let optionc = $(`<div class="form-group">
                                    <label>Option C*</label>
                                    <textarea name="optionc" class="form-control" 
                                        required>${question.options[2]}</textarea>
                                </div>`);
                        let optiond = $(`<div class="form-group">
                                    <label>Option D*</label>
                                    <textarea name="optiond" class="form-control"
                                        required>${question.options[3]}</textarea>
                                </div>`);
                        let answer = $(`<div class="form-group">
                                     <label>Answer*</label>
                                    <textarea name="answer" class="form-control"
                                        required>${question.answer}</textarea>
                                </div>`);
                        
                        let button = $('<div class="mm_single_submit"><input type="submit" value="Submit" class="save btn btn-primary"> <button class="cancel btn btn-secondary">Cancel</button></div>');

                         // Append all form elements to the form
                        EditQuestion.append(subs, subs_topic, quest,questImg,questid, optiona, optionb, optionc, optiond, answer, button);

                        // Close the form tag
                        EditQuestion.append('</form>');

                         // Append the form to the edit form container
                        $('.edit-question-form').append(EditQuestion);

                        $('.item').hide(); // Hide the section with the class 'item'
                        $('.edit-question-form').show(); // show the edit form
                } else {
                    console.log('Question not found');
                }
            });

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

                    const questid = form.find('input[name="questid"]').val();

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

                    // Send data to backend via AJAX
                    $.ajax({
                        url: '/api/question/'+questid,
                        method: 'PUT',
                        data: JSON.stringify(formData),
                        contentType: 'application/json',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Response:', response);
                            alert('Question updated successfully');
                        },
                        error: function(xhr, status, error) {
                          var errorMessage = 'An error occurred.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                if (xhr.responseJSON.errors.email) {
                                    // Email not found error
                                    errorMessage = xhr.responseJSON.errors.email[0];
                                } else {
                                    // General error message
                                    errorMessage = 'An error occurred: Please Fill all fields ';
                                }
                            }
                            alert(errorMessage);
                        },
                        complete: function(){
                             // Re-enable the submit button and hide the loading message
                            $('#submit').prop('disabled', false);
                            $('#loadingMessage').hide();
                        }
                    });

                }
               
            });


            // Cancel edit form and show questions
            $(document).on('click', '.cancel', function(event) {
                event.preventDefault();
                $('.edit-question-form').empty();
                $('.item').show();
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
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>

     <style>
        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            color: #007bff;
            text-decoration: none;
            cursor: pointer;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
        .pagination a.disabled {
            pointer-events: none;
            color: #ccc;
        }
    </style>

    </section>
@endsection
    <!-- //courses -->