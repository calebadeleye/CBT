@extends('layout.master')

@section('title', 'About UTME | Learn About Our JAMB Prep Platform')
    <!-- //header -->

    <!-- banner -->
    @section('banner')
    <div class="inner-banner-w3ls text-right d-flex align-items-center">
        <div class="container">
            <h6 class="agileinfo-title">about us </h6>
            <ol class="breadcrumb-parent d-flex justify-content-end">
                <li class="breadcrumb-nav">
                    <a href="/">Home</a>
                </li>
                <li class="breadcrumb-nav active  text-capitalize" aria-current="page">About Us</li>
            </ol>
        </div>
    </div>
    @endsection
    <!-- //banner -->

    @section('content')
    <!-- about -->
    <section class="wthree-row py-sm-5 py-3 about-top" id="ab-bot">
        @include('layout.info')
    </section>
    <section class="about-bottom-agileinfo pb-lg-5">
       @include('layout.body')
    </section>
    <!-- //about -->
    <!-- flow_of_work -->
    <section id="flow_of_work" class="home-section py-lg-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="home-title main-title-agile text-center">UTME flow</h3>
                    <div class="row flow_of_work-line">
                        <div class="col-lg-3 col-sm-6">
                            <div class="flow_of_work-item bg1-theme">
                                <span class="text-color1">1</span>
                                <i class="fas fa-user-check"></i>
                                <h4>Create account</h4>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 mt-sm-0 mt-5">
                            <div class="flow_of_work-item bg2-theme">
                                <span class="text-color2">2</span>
                                <i class="fas fa-shopping-cart"></i>
                                <h4>Purchase PIN</h4>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 mt-lg-0 mt-5">
                            <div class="flow_of_work-item bg3-theme">
                                <span class="text-color3">3</span>
                                <i class="fas fa-pencil-alt"></i>
                                <h4>Practice Test</h4>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 mt-lg-0 mt-5">
                            <div class="flow_of_work-item bg4-theme">
                                <span class="text-color4">4</span>
                                <i class="fas fa-award"></i>
                                <h4>Reward</h4>
                            </div>
                        </div>
                    </div>
                    <!-- flow_of_work-line -->
                </div>
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </section>
    <!-- //flow_of_work -->
    @endsection
