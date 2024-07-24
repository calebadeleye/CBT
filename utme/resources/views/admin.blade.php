@extends('layout.master')
@section('title', 'Admin Login here | Admin Login :: UTME')

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
                <h4>Login to dashbaord</h4>
            
                <form class="register-wthree admin-login-form">
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
                        <div class="row">
                            <div class="col-md-12">
                                <span class="fas fa-lock"></span>
                                <label>
                                    Password
                                </label>
                                <input type="text" class="form-control" name="password" id="password"
                                    required="">
                            </div>
                        </div>
                        <br>
                        <a href="{{ route('guest.showForgot') }}" style="color: #75BC2F;">Forgot your password ?</a>
                        <div class="mt-4">
                            <button type="submit" id ="submit" class="btn btn-agile btn-block w-100">Login</button>
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