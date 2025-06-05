<!DOCTYPE HTML>
<html lang="zxx">

<head>
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="keywords" content="JAMB preparation,JAMB practice questions,UTME practice,Online JAMB prep,JAMB exam prep,JAMB test practice,University entrance exams,UTME questions,JAMB mock tests,Nigerian university exams,JAMB online practice,UTME success,Affordable JAMB prep,JAMB study resources,Practice JAMB online" />
    <meta name="description" content="Prepare for JAMB with UTME.com.ng. Practice questions, track progress, and boost confidence for 1000 Naira. Start your journey to success today!">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/bootstrap.css') }}" rel='stylesheet' type='text/css' />
    <!-- login icon animation -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet" type="text/css" media="all">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    <!-- font-awesome icons -->
    <link href="{{ asset('css/fontawesome-all.min.css') }}" rel="stylesheet">
    <!-- //Custom Theme files -->
    <link rel="icon" href="{{ asset('favicon/android-chrome-192x192.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon/android-chrome-512x512.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon/apple-touch-icon.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon/favicon-16x16.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon/favicon-32x32.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon/favicon.ico') }}" type="image/png">
    <!-- online fonts -->
    <link href="//fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Gentium+Book+Basic:400,400i,700i" rel="stylesheet">
    <!--//online fonts -->
</head>

<body>
    <div class="se-pre-con"></div>
    <!-- header -->
    <header>
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="d-flex header-agile">
                            <li>
                                <span class="fas fa-envelope-open"></span>
                                <a href="mailto:example@email.com" class="text-white">info@utme.com.ng</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 hearder-right-agile">
                        <div class="d-flex">
                            <!-- Multiple select filter -->
                            <select class="custom-select">
                            </select>
                            <!-- Multiple select filter  -->
                            <div class="login-wthree my-auto">
                                @if (!Route::is('guest.showUserBoard') && !Route::is('guest.showUserScores') && !Route::is('guest.showQuestions') && !Route::is('guest.showAdminBoard') && !Route::is('guest.addQuestion') &&
                                !Route::is('guest.showLeaderBoard') &&
                                !Route::is('guest.addBank'))
                                    <a href="{{ route('guest.showLogin') }}" class="text-white text-capitalize">
                                        login <span class="fas fa-sign-in-alt flash animated infinite"></span>
                                    </a>
                                @endif

                                 @if (Route::is('guest.showUserBoard') || Route::is('guest.showUserScores') || Route::is('guest.showAdminBoard') || Route::is('guest.showQuestions')  ||
                                 Route::is('guest.addQuestion')   ||
                                 Route::is('guest.showLeaderBoard')   ||
                                 Route::is('guest.addBank'))   
                                    <a href="#" class="text-white text-capitalize {{ Route::is('guest.showQuestions') || 
                                    Route::is('guest.showAdminBoard')||
                                    Route::is('guest.addQuestion') ? 'admin-logout' : 'logout' }}">logout <span class="fas fa-sign-in-alt flash animated infinite"></span></a>
                                @endif
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light p-0">
                    <h1><a class="navbar-brand" href="/">
                            <span>UTME</span>.com.ng
                            <i class="w3-spacing"></i>
                        </a></h1>
                    <button class="navbar-toggler ml-lg-auto ml-sm-5" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav text-center ml-auto">
                            <li class="nav-item {{ Request::Is('/') ? 'active' : '' }} mr-lg-3 mt-lg-0 mt-4">
                                <a class="hover-fill" href="/" data-txthover="Home">Home</a>
                            </li>
                            <li class="nav-item {{ Request::routeIs('guest.showAbout') ? 'active' : '' }} mr-lg-3 mt-lg-0 mt-4">
                                <a class="hover-fill" href="{{ route('guest.showAbout') }}" data-txthover="About">About</a>
                            </li>
                            <li class="nav-item {{ Request::routeIs('guest.showLeaderBoard') ? 'active' : '' }} mr-lg-3 my-lg-0 my-4">
                                <a class="hover-fill" href="{{ route('guest.showLeaderBoard') }}" data-txthover="Leaders Board">LeaderBoard</a>
                            </li>
                            <li class="nav-item mr-lg-3 my-lg-0 my-4">
                                <a class="hover-fill" href="https://naitalk.com/sub/category/post-utme/54" data-txthover="Leaders Board">News & Update</a>
                            </li>
                            <li class="nav-item {{ Request::routeIs('guest.showContact') ? 'active' : '' }}">
                                <a class="hover-fill" href="{{ route('guest.showContact') }}" data-txthover="Contact">Contact</a>
                            </li>
                        </ul>

                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- //header -->
    <!-- banner -->
    <!-- Carousel -->
  	 @yield('banner')
    <!-- //carousel -->
    <!-- //banner -->
    <!-- about -->
   	@yield('content')
    <!-- //about -->


    <!-- footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="social-icons d-flex  my-auto justify-content-lg-start justify-content-center">
                        <h2 class="mr-4">stay connected :</h2>
                        <ul class="social-iconsv2 agileinfo">
                            <li>
                                <a href="https://facebook.com/postume">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                       
                            <li>
                                <a href="#">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 my-lg-auto mt-3">
                    <div class="cpy-right text-lg-right text-center">
                        <p class="text-secondary">© {{ date('Y') }} <a href="https://naitalk.com">Naitalk.com</a>.|<a href="{{ route('guest.showTerms') }}">Terms & Conditions</a> | <a href="">Privacy Policy</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- //footer -->
    <!-- js-->
    <script src="{{ asset('js/jquery-2.2.3.min.js') }}"></script>
    <!-- loading-gif Js -->
    <script src="{{ asset('js/modernizr.js') }}"></script>
    <script>
        //paste this code under head tag or in a seperate js file.
        // Wait for window load
        $(window).load(function () {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");;
        });
    </script>
    <!--// loading-gif Js -->
    <!-- Multiple select filter using jQuery -->
    <script src="{{ asset('js/custom-select.js') }}"></script>
    <!-- //Multiple select filter using jQuery -->
    <!-- stats counter -->
    <script src="{{ asset('js/counter.js') }}"></script>
  
    <!-- start-smooth-scrolling -->
    <script src="{{ asset('js/move-top.js') }}">
    </script>
    <script src="{{ asset('js/easing.js') }}"></script>
    <script>
        jQuery(document).ready(function ($) {
            $(".scroll").click(function (event) {
                event.preventDefault();

                $('html,body').animate({
                    scrollTop: $(this.hash).offset().top
                }, 1000);
            });
        });
    </script>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <!-- //end-smooth-scrolling -->
    <!-- smooth-scrolling-of-move-up -->
    <script>
        $(document).ready(function () {
            /*
             var defaults = {
                 containerID: 'toTop', // fading element id
                 containerHoverID: 'toTopHover', // fading element hover id
                 scrollSpeed: 1200,
                 easingType: 'linear' 
             };
             */

            $().UItoTop({
                easingType: 'easeOutQuart'
            });

        });
    </script>

    <script>

        /*login to test platform */
        $('.login-form').on('submit', function(event) {
            event.preventDefault(); 

            let emailValue = $('input[name="email"]').val();
            let passwordValue = $('input[name="password"]').val();

            // Prepare data to send to the backend
            var requestData = {
                email: emailValue,
                password: passwordValue,
            };

           // Disable the submit button and show the loading message
            $('#submit').prop('disabled', true);
            $('#loadingMessage').show();

            // Send data to the backend
            $.ajax({
                url: '/api/signin', 
                method: 'POST',
                data: requestData, // Send the data to the backend
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    // Save the token in session storage
                    sessionStorage.setItem('login_token',response.token);

                    // Save user details in session storage
                    sessionStorage.setItem('user', JSON.stringify(response.user));

                    // Save questions
                    sessionStorage.setItem('questions',  JSON.stringify(response.questions));

                    // Save pin details in session storage
                    sessionStorage.setItem('mypin', JSON.stringify(response.pin));

                    // Save bank details in session storage
                    sessionStorage.setItem('mybank', JSON.stringify(response.bank));

                    // Save bank lists in session storage
                    sessionStorage.setItem('banklist', JSON.stringify(response.banklist));

                    // Save leaderboard details in session storage
                    sessionStorage.setItem('myscore', response.leaderboard);

                     // Redirect to  user board
                     window.location.href = '{{ route('guest.showUserBoard') }}'; 
                },
                error: function(xhr, status, error) {
                    var errorMessage = 'An error occurred.';

                    if (xhr.status === 403) {
                        // Handle 403 error (email not verified)
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Handle general error messages
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        if (xhr.responseJSON.errors.email) {
                            // Email not found error
                            errorMessage = xhr.responseJSON.errors.email[0];
                        } else {
                            // General error message
                            errorMessage = 'An error occurred: Login credentials not correct';
                        }
                    }
                    
                    alert(errorMessage);
                },
              complete: function(){
                     // Re-enable the login button and hide the loading message
                    $('#submit').prop('disabled', false);
                    $('#loadingMessage').hide();
                     // Clear the form
                    $('.login-form')[0].reset();
                }
            });
        });

    </script>

    <script>

        $('.logout').on('click', function(event) {
            event.preventDefault();
            let token =  sessionStorage.getItem('login_token');

            $.ajax({
                url: '/api/logout',
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    sessionStorage.clear(); 
                    // Redirect to  login
                     window.location.href = '{{ route('guest.showLogin') }}'; 
                },
                error: function(xhr, status, error) {
                    console.error('Logout error:', error);
                }
            });
        });

    </script>

     <script>

        $('.admin-logout').on('click', function(event) {
            event.preventDefault();
            let token =  sessionStorage.getItem('admin_token');

            $.ajax({
                url: '/api/logout',
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    sessionStorage.clear(); 
                    // Redirect to  login
                     window.location.href = '{{ route('guest.showAdminLogin') }}'; 
                },
                error: function(xhr, status, error) {
                    console.error('Logout error:', error);
                }
            });
        });

    </script>

     <style>
        .loading {
            display: none;
            color: #75BC2F;
        }
    </style>

       <script>

        /*Register user to the platform */
        $('.register-form').on('submit', function(event) {
            event.preventDefault(); 

            let firstNameValue = $('input[name="firstname"]').val();
            let lastNameValue = $('input[name="lastname"]').val();
            let emailValue = $('input[name="email"]').val();
            let password1Value = $('input[name="password1"]').val();
            let password2Value = $('input[name="password2"]').val();

            // Prepare data to send to the backend
            var requestData = {
                name: firstNameValue+' '+lastNameValue,
                email: emailValue,
                password: password1Value,
            };

            console.log(requestData);
            if (password1Value !== password2Value) {
                 alert('Passwords do not match.');
            } else {
                   // Disable the submit button and show the loading message
                $('#submit').prop('disabled', true);
                $('#loadingMessage').show();
                    // Send data to the backend
                $.ajax({
                    url: '/api/signup', 
                    method: 'POST',
                    data: requestData, // Send the data to the backend
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                         alert(response.data);
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = 'An error occurred.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            if (xhr.responseJSON.errors.email) {
                                // Email not found error
                                errorMessage = xhr.responseJSON.errors.email[0];
                            } else {
                                // General error message
                                errorMessage = 'An error occurred: Please check information ';
                            }
                        }
                        alert(errorMessage);
                    },
                    complete: function(){
                         // Re-enable the submit button and hide the loading message
                        $('#submit').prop('disabled', false);
                        $('#loadingMessage').hide();
                         // Clear the form
                        $('.register-form')[0].reset();
                    }
                });
            }
        });

    </script>


     <script>

            /*Send reset link to email*/
            $('.login-form-reset').on('submit', function(event) {
                event.preventDefault(); 

                let emailValue = $('input[name="email"]').val();

                // Prepare data to send to the backend
                var requestData = {
                    email: emailValue,
                };

                console.log(requestData);
                
                       // Disable the submit button and show the loading message
                    $('#submit').prop('disabled', true);
                    $('#loadingMessage').show();
                        // Send data to the backend
                    $.ajax({
                        url: '/api/reset', 
                        method: 'POST',
                        data: requestData, // Send the data to the backend
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {

                             alert(response.data);
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = 'An error occurred.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                if (xhr.responseJSON.errors.email) {
                                    // Email not found error
                                    errorMessage = xhr.responseJSON.errors.email[0];
                                } else {
                                    // General error message
                                    errorMessage = 'An error occurred: Please check information ';
                                }
                            }
                            alert(errorMessage);
                        },
                        complete: function(){
                             // Re-enable the submit button and hide the loading message
                            $('#submit').prop('disabled', false);
                            $('#loadingMessage').hide();
                             // Clear the form
                            $('.login-form-reset')[0].reset();
                        }
                    });
                
            });

    </script>

      <script>

        /*Change password form */
        $('.reset-form').on('submit', function(event) {
            event.preventDefault(); 

            let tokenValue = $('input[name="token"]').val();
            let password1Value = $('input[name="password1"]').val();
            let password2Value = $('input[name="password2"]').val();

            // Prepare data to send to the backend
            var requestData = {
                token: tokenValue,
                password: password1Value,
            };

            if (password1Value !== password2Value) {
                 alert('Passwords do not match.');
            } else {
                   // Disable the submit button and show the loading message
                $('#submit').prop('disabled', true);
                $('#loadingMessage').show();
                    // Send data to the backend
                $.ajax({
                    url: '/api/reset/'+tokenValue, 
                    method: 'PUT',
                    data: requestData, // Send the data to the backend
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                         alert(response.data);
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = 'An error occurred.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            if (xhr.responseJSON.errors.email) {
                                // Email not found error
                                errorMessage = xhr.responseJSON.errors.email[0];
                            } else {
                                // General error message
                                errorMessage = 'An error occurred: Please check information ';
                            }
                        }
                        alert(errorMessage);
                    },
                    complete: function(){
                         // Re-enable the submit button and hide the loading message
                        $('#submit').prop('disabled', false);
                        $('#loadingMessage').hide();
                         // Clear the form
                        $('.register-form')[0].reset();
                    }
                });
            }
        });

    </script>

        <script>
        /*Initiate Payment */

        $('.make-payment').on('click', function(event) {
            event.preventDefault();
            const user = JSON.parse(sessionStorage.getItem('user'));
            const tx_ref = Math.floor(1000 + Math.random() * 9000);
            const public_key = "{{ config('services.flutterwave.public_key') }}";

             // Prepare data to send to the backend
            var userData = {
                name: user.name,
                email: user.email,
            };

            FlutterwaveCheckout({
              public_key: public_key,
              tx_ref: tx_ref,
              amount: 1000,
              currency: "NGN",
              payment_options: "card, banktransfer, ussd",
              meta: {
                source: "UTME-Myboard",
              },
              customer: {
                email: userData.email,
                name: userData.name,
              },
              customizations: {
                title: "UTME.COM.NG",
                description: "UTME CBT PAYMENT",
                logo: "https://utme.com.ng/favicon/favicon-32x32.png",
              },
              callback: function (data){
                sendCallback(tx_ref,userData,data.transaction_id);
              },
              onclose: function() {
                //alert("Payment cancelled!");
              }
            });

          function sendCallback(tx_ref,user,transaction_id) {

                const token =  sessionStorage.getItem('login_token');
                requestData = {
                    name: user.name,
                    email: user.email,
                    tx_ref: tx_ref,
                    transaction_id: transaction_id
                }
                console.log(requestData);
                $.ajax({
                    url: '/api/bank/'+transaction_id,
                    method: 'PUT',
                    data: requestData, // Send the data to the backend
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response.data); 
                    },
                    error: function(xhr, status, error) {
                        console.error( error);
                    }
                });
          }
        });

    </script>

       
    <script>

        /*login to admin dashboard */
        $('.admin-login-form').on('submit', function(event) {
            event.preventDefault(); 

            let emailValue = $('input[name="email"]').val();
            let passwordValue = $('input[name="password"]').val();

            // Prepare data to send to the backend
            var requestData = {
                email: emailValue,
                password: passwordValue,
            };

           // Disable the submit button and show the loading message
            $('#submit').prop('disabled', true);
            $('#loadingMessage').show();

            // Send data to the backend
            $.ajax({
                url: '/api/admin/login', 
                method: 'POST',
                data: requestData, // Send the data to the backend
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    // Save the token in session storage
                    sessionStorage.setItem('admin_token',response.token);

                    // Save subjects
                    sessionStorage.setItem('subjects', response.subjects);

                    // Save questions
                    sessionStorage.setItem('questions', response.questions);

                    // Save subject topic
                    sessionStorage.setItem('subjects_topic', response.subjects_topic);

                     // Redirect to  admin board
                     window.location.href = '{{ route('guest.showAdminBoard') }}'; 
                },
                error: function(xhr, status, error) {
                    var errorMessage = 'An error occurred.';

                    if (xhr.status === 403) {
                        // Handle 403 error (email not verified)
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Handle general error messages
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        if (xhr.responseJSON.errors.email) {
                            // Email not found error
                            errorMessage = xhr.responseJSON.errors.email[0];
                        } else {
                            // General error message
                            errorMessage = 'An error occurred: Login credentials not correct';
                        }
                    }
                    
                    alert(errorMessage);
                },
              complete: function(){
                     // Re-enable the login button and hide the loading message
                    $('#submit').prop('disabled', false);
                    $('#loadingMessage').hide();
                     // Clear the form
                    $('.admin-login-form')[0].reset();
                }
            });
        });

    </script>

    <script src="{{ asset('js/SmoothScroll.min.js') }}"></script>
    <!-- //smooth-scrolling-of-move-up -->
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('js/bootstrap.js') }}">
    </script>
    <!-- //Bootstrap Core JavaScript -->
</body>

</html>