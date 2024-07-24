@extends('layout.master')

@section('title', 'Admin Board | Admin Board :: UTME')
    <!-- //header -->
    <!-- banner -->
@section('banner')
    <div class="inner-banner-w3ls text-right d-flex align-items-center">
        <div class="container">
            <h6 class="agileinfo-title">Admin Board </h6>
            <ol class="breadcrumb-parent d-flex justify-content-end">
                <li class="breadcrumb-nav">
                    <a href="/">Home</a>
                </li>
                <li class="breadcrumb-nav active  text-capitalize" aria-current="page">Admin Board</li>
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
                    <h3 class="main-title-agile">Admin</h3>
                    <span class="bg-theme"></span>
                </div>
                <div class="row stat-rodw  position-relative text-center">
                    <div class="col-lg-3 col-md-6">
                        <div class="counter py-4 px-3 bg4-theme">
                            <i class="fas fa-eye"></i>
                            <p class="count-text text-capitalize">View Questions</p>
                            <br>
                                <a href="{{ route('guest.showQuestions') }}" class="btn btn-success btn-sm">Click Here</a>
                            
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-lg-0 mt-sm-5 mt-3 ">
                        <div class="counter py-4 px-3 bg5-theme">
                            <i class="fas fa-pencil-alt"></i>
                            <p class="count-text text-capitalize">Create Question</p>
                            <br>
                                <a href="{{ route('guest.addQuestion')  }}" class="btn btn-success btn-sm">Click Here</a>
                            
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
         <script>
            //show user pin count
            document.addEventListener('DOMContentLoaded', (event) => {

                // Retrieve subjects from session storage
                const subjects = JSON.parse(sessionStorage.getItem('subjects'));
                const admin_token = sessionStorage.getItem('admin_token');
                
                //check if admin is authenticeated if not redirect back to login
                if(admin_token === null){
                    window.location.href= '{{ route('guest.showAdminLogin') }}'; 
                }

            });

        </script>

    </section>
    @endsection
    <!-- //stats -->