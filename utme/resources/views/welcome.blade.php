@extends('layout.master')

@section('title', 'Ultimate JAMB Prep Platform | Practice, Track Progress, Reward')

    <!-- //header -->
    <!-- banner -->
    <!-- Carousel -->
    @section('banner')
    <div class="row justify-content-center align-items-center no-gutters banner-agile">
        <div class="col-lg-8">
            @include('layout.slider')
            <!-- Carousel -->
            <!-- //banner -->
        </div>
        <div class="col-lg-4">
            <div class="wthree-form">
                <h4>Sign Up</h4>
                <p class="login-sub">Let’s get you started for success!</p>
                <form class="register-wthree register-form">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <span class="fas fa-user"></span>
                                <label>
                                    First name
                                </label>
                                <input class="form-control" type="text" placeholder="John" name="firstname" id="firstname" required="">
                            </div>
                            <div class="col-md-6 mt-md-0 mt-2">
                                <label>
                                    Last name
                                </label>
                                <input class="form-control" type="text" placeholder="Does" name="lastname" id="lastname" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="fas fa-envelope-open"></span>
                                <label>
                                    Email
                                </label>
                                <input class="form-control" type="email" placeholder="example@email.com" name="email" id="email" 
                                    required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="fas fa-lock"></span>
                                <label>
                                    password
                                </label>
                                <input type="password" class="form-control" placeholder="*******" name="password1" id="password1"
                                    required="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="fas fa-lock"></span>
                                <label>
                                    confirm password
                                </label>
                                <input type="password" class="form-control" placeholder="*******" name="password2" id="password2"
                                    required="">
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('guest.showTerms') }}">By submitting, you agree to our Terms & Conditions.</a>

                    <div
                        class="cf-turnstile"
                        data-sitekey="{{ env('CLOUDFLARE_TURNSTILE_SITE_KEY') }}"
                        data-callback="onTurnstileSuccess">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-agile btn-block w-100" id="submit">register</button>
                        <div class="loading" id="loadingMessage">Loading, please wait...</div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    <!-- //carousel -->
    <!-- //banner -->
    <!-- about -->
    @section('content')
    <section class="wthree-row py-sm-5 py-3 about-top" id="ab-bot">
         @include('layout.info')
    </section>
    <section class="about-bottom-agileinfo pb-lg-5">
        @include('layout.body')
    </section>
    @endsection
    <!-- //about -->

