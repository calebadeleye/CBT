@extends('layout.master')

@section('title', 'Bank | Bank :: UTME')
    <!-- //header -->
    <!-- banner -->
@section('banner')
    <div class="inner-banner-w3ls text-right d-flex align-items-center">
        <div class="container">
            <h6 class="agileinfo-title">Bank Details </h6>
            <ol class="breadcrumb-parent d-flex justify-content-end">
                <li class="breadcrumb-nav">
                    <a href="/">Home</a>
                </li>
                <li class="breadcrumb-nav active  text-capitalize" aria-current="page">Bank Details</li>
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
                <h3 class="main-title-agile">Add Bank Details</h3>
                <div class="title-line">
                </div>
            </div>
          
             <div class="leave-coment-form add-bank-form"></div>
        </div>

        <div class="container py-lg-5">
            <div class="title-section pb-4">
                <h3 class="main-title-agile">My Bank Details</h3>
                <div class="title-line">
                </div>
            </div>
             <div class="error-messages"></div>
             <div class="bank-details-container"></div>

             <div class="loading" id="loadingMessage">Loading, please wait...</div>
        </div>

<script src="{{ asset('js/jquery-2.2.3.min.js') }}"></script>

     <script>
        $(document).ready(function () {
        // Retrieve data from sessionStorage

        const banklist = JSON.parse(sessionStorage.getItem('banklist'));


        // Start to user bank details

        let mybank; 
        try {
            mybank = JSON.parse(sessionStorage.getItem('mybank')); // Parse into an object
        } catch (error) {
            console.error('Failed to parse JSON:', error);
            mybank = null; // Fallback in case parsing fails
        }

        // Check if mybank is an array or a single object
        if (mybank && !Array.isArray(mybank)) {
            mybank = [mybank]; // Wrap single object in an array for consistency
        }

        // If mybank is empty or invalid, handle gracefully
        if (!mybank || mybank.length === 0) {
            console.error('No valid bank data available');
            return; // Exit early
        }

        // Target container where the divs will be added
        const bankContainer = $('.bank-details-container'); // Ensure this container exists in your HTML

        // Clear any existing content
        bankContainer.empty();

        // Loop through the array and append the details
        mybank.forEach(bank => {
            const bankDetailsDiv = $(`
                <div class="bank-detail">
                    <p><strong>Bank Name:</strong> <label class="bank-name-display">${bank.bankname}</label></p>
                    <p><strong>Account Number:</strong> <label class="account-number-display">${bank.account_number}</label></p>
                    <p><strong>Account Name:</strong> <label class="account-name-display">${bank.account_name}</label></p>
                </div>
            `);

            // Append the created div to the container
            bankContainer.append(bankDetailsDiv);
        });

        //End shoing user bank details

        // Create the edit form
        let EditBank = $('<form class="js-quiz-form" enctype="multipart/form-data"></form>');

        // Clear the edit form container
        $('.add-bank-form').empty();

        // Create a dropdown for the bank list
        let bankDropdown = $('<div class="form-group"></div>');
        let bankSelect = $('<select name="bankname" id="bankname" class="form-control" required></select>');
        bankSelect.append('<option value="" disabled selected>Select a Bank</option>'); // Default option

        // Populate the dropdown options
        if (Array.isArray(banklist)) {
            // Sort the banklist array alphabetically by bank name
            banklist.sort((a, b) => {
                if (a.name < b.name) return -1; // Sort in ascending order
                if (a.name > b.name) return 1;
                return 0;
            });

            // Iterate through the sorted list to populate the dropdown
            banklist.forEach(bank => {
            
                bankSelect.append(`<option value="${bank.name}" data-id-bankcode="${bank.code}">${bank.name}</option>`);
            });
        }


        bankDropdown.append('<label>Bank Name*</label>');
        bankDropdown.append(bankSelect);

        // Create the account number input field
        let accountNumber = $(`
            <div class="form-group">
                <label>Account Number*</label>
                <input 
                    name="account_number" 
                    class="form-control" 
                    value="" 
                    required
                >
            </div>
        `);

        // Add buttons
        let buttonGroup = $(`
            <div class="mm_single_submit">
                <input type="submit" value="Save" class="save btn btn-primary"> 
                
            </div>
        `);

        // Append all elements to the form
        EditBank.append(bankDropdown, accountNumber, buttonGroup);

        // Append the form to the container
        $('.add-bank-form').append(EditBank);

        // Show the form
        $('.add-bank-form').show();

    });


    //start save user bank details to banks table

           $(document).on('submit', '.js-quiz-form', function(event) {
                event.preventDefault();
                const token = sessionStorage.getItem('login_token');
                const form = $(this);

                const bankName = form.find('select[name="bankname"]').val();
                const bankCode = form.find('select[name="bankname"] option:selected').data('id-bankcode');

                const accountNumber = form.find('input[name="account_number"]').val();

                // Collect form data
                const formData = {
                    bankName: bankName,
                    bankCode: bankCode,
                    accountNumber: accountNumber,
                };

                if (!bankName || !bankCode || !accountNumber) {
                    $('.error-messages').html('<p>All fields are required.</p>');
                    return;
                }

                console.log(formData);

                   // Disable the submit button and show the loading message
                $('#submit').prop('disabled', true);
                
                $('#loadingMessage').show();

                 // Show loading message
                $('#error-messages').show();

               $.ajax({
                url: '/api/bank',
                method: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Response:', response);

                    // Save updated bank details in sessionStorage
                    sessionStorage.setItem('mybank', JSON.stringify({ 
                        bankname: response.data.bankname, 
                        account_number: response.data.account_number,
                        account_name: response.data.account_name, 
                    }));

                    alert(response.message);

                    // Clear the form
                    $('.js-quiz-form')[0].reset();

                    // Update DOM elements dynamically
                    if (response.data) {
                        $('.bank-name-display').text(response.data.bankname);
                        $('.account-number-display').text(response.data.account_number);
                        $('.account-name-display').text(response.data.account_name);
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'An error occurred.';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    }

                    // Display the error message
                    $('.error-messages').html('<p>' + errorMessage + '</p>');

                    // Scroll to the error messages section
                    $('html, body').animate({
                        scrollTop: $('.error-messages').offset().top
                    }, 500);

                    // Show the error message as an alert (optional)
                    alert(errorMessage);
                },
                complete: function() {
                    // Re-enable the submit button and hide the loading message
                    $('#submit').prop('disabled', false);
                    $('#loadingMessage').hide();
                }
            });

           
            });

        
    //end save user bank details

    </script>

      <style>
        .mm_single_submit {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>

    </section>
@endsection
    <!-- //courses -->