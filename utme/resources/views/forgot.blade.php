@extends('layout.master')
@section('title', 'Forgot password | Forgot :: UTME')

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
           
                <form class="register-wthree login-form-reset">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="fas fa-envelope-open"></span>
                                <label>
                                    Email
                                </label>
                                <input class="form-control" type="email" placeholder="example@email.com" name="email"
                                    required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mt-4">
                            <button type="submit" id ="submit" class="btn btn-agile btn-block w-100">Reset</button>
                            <div class="loading" id="loadingMessage">Loading, please wait...</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection
    <!-- //carousel -->
    <!-- //banner -->