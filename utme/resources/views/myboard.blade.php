@extends('layout.master')

@section('title', 'My Board | My Board :: UTME')
    <!-- //header -->
    <!-- banner -->
@section('banner')
    <div class="inner-banner-w3ls text-right d-flex align-items-center">
        <div class="container">
            <h6 class="agileinfo-title">My Board </h6>
            <ol class="breadcrumb-parent d-flex justify-content-end">
                <li class="breadcrumb-nav">
                    <a href="/">Home</a>
                </li>
                <li class="breadcrumb-nav active  text-capitalize" aria-current="page">My Board</li>
            </ol>
        </div>
    </div>
@endsection
    <!-- //banner -->

    <!-- stats -->
    @section('content')
    <section class="agile_stats">
        <div class="container">
            <div class="container pt-sm-5">
                <div class="title-section pb-4  text-center">
                    <h3 class="main-title-agile" id="username"></h3>
                    <span class="bg-theme"></span>
                </div>
                <div class="row stat-row  position-relative text-center">
                    <img src="images/slide.jpg" alt="" class="img-fluid position-absolute" />
                    <div class="col-lg-3 col-md-6">
                        <div class="counter py-4 px-3 bg4-theme">
                            <i class="fas fa-shopping-cart"></i>
                            <p class="count-text text-capitalize">Purchase PIN</p>
                            <br>
                                <a href="#" class="btn btn-success btn-sm make-payment">Click Here</a>
                            
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-lg-0 mt-sm-5 mt-3 ">
                        <div class="counter py-4 px-3 bg5-theme">
                            <i class="fas fa-pencil-alt"></i>
                            <p class="count-text text-capitalize">Take Test</p>
                            <br>
                                <a href="{{ route('guest.showQuiz')  }}" class="btn btn-success btn-sm">Click Here</a>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mt-md-0 mt-sm-5 mt-3">
                        <div class="counter py-4 px-3 bg2-theme">
                            <i class="fas fa-hourglass-half"></i>
                            <p class="count-text text-capitalize">Atempts Left</p>
                            <div class="timer count-title count-number mt-2 pin" data-to="0" data-speed="100"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mt-lg-0 mt-sm-5 mt-3">
                     <a href="{{ route('guest.showUserScores') }}" title="click to view all scores">
                        <div class="counter py-4 px-3 bg3-theme">
                            <i class="fas fa-trophy"></i>
                            <p class="count-text text-capitalize">Latest score</p>
                            <div class="timer count-title count-number mt-2 myscore" data-to="0" data-speed="100"></div>
                        </div>
                    </a>
                    </div>

                <!-- <div class="col-lg-3 col-md-6 mt-lg-0 mt-sm-5 mt-3">
                     <a href="{{ route('guest.addBank') }}" title="click to add bank details">
                        <div class="counter py-4 px-3 bg1-theme">
                            <i class="fa fa-piggy-bank"></i>
                            <p class="count-text text-capitalize">Add Bank</p>
                            <br>
                                <a href="{{ route('guest.addBank')  }}" class="btn btn-success btn-sm">Click Here</a>
                        </div>
                    </a>
                    </div> -->

                </div>
            </div>
        </div>

          <script>
            
            //redirect user if token is not set
            if (!sessionStorage.getItem('login_token')) {
                window.location.href = '{{ route('guest.showLogin') }}'; 
            }

            // Retrieve user details from session storage
            const user = JSON.parse(sessionStorage.getItem('user'));

            // Display the user's name
            if (user && user.name) {
                document.getElementById('username').textContent = `Welcome, ${user.name}!`;
            }
        </script>

        <script>
            //show user pin count
            document.addEventListener('DOMContentLoaded', (event) => {
                // Retrieve the pin from session storage
                const pin = JSON.parse(sessionStorage.getItem('mypin'));

                // Check if pin exists
                if (pin) {
                    // Get the element and set the `data-to` attribute
                    const countElement = document.querySelector('.pin');
                    if (countElement) {
                        console.log(pin.count);
                        countElement.setAttribute('data-to', 10-pin.count);
                    }
                }

                 // Retrieve the score from session storage
                const scores = JSON.parse(sessionStorage.getItem('myscore'));
                
                console.log(scores[0]['score']);

                // Check if score exists
                if (scores[0]['score']) {
                    // Get the element and set the `data-to` attribute
                    const countElement = document.querySelector('.myscore');
                    if (countElement) {
                        countElement.setAttribute('data-to', scores[0]['score']);
                    }
                }

            });

        </script>

    </section>
    @endsection
    <!-- //stats -->