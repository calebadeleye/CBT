@extends('layout.master')

@section('title', 'Password Reset Form')

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
                <h4>Password Reset</h4>
                <p class="login-sub">Use the form below to change the password!</p>
                <form class="register-wthree reset-form">
                    <div class="form-group">
                        <div class="row">
                             <input type="hidden" name="token" value="{{ $token }}">
                            <div class="col-md-12">
                                <span class="fas fa-lock"></span>
                                <label>
                                    New password
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
                                    Confirm new password
                                </label>
                                <input type="password" class="form-control" placeholder="*******" name="password2" id="password2"
                                    required="">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-agile btn-block w-100" id="submit">reset</button>
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

