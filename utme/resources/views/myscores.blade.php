@extends('layout.master')

@section('title', 'LeaderBoard | Leaders Board :: UTME')
    <!-- //header -->

    <!-- banner -->
    @section('banner')
    <div class="inner-banner-w3ls text-right d-flex align-items-center">
        <div class="container">
            <h6 class="agileinfo-title">leaderboard </h6>
            <ol class="breadcrumb-parent d-flex justify-content-end">
                <li class="breadcrumb-nav">
                    <a href="/">Home</a>
                </li>
                <li class="breadcrumb-nav active  text-capitalize" aria-current="page">LeaderBoard</li>
            </ol>
        </div>
    </div>
    @endsection
    <!-- //banner -->

@section('content')
    <!-- pricing -->
    <section class="wthree-row py-5" id="pricing">
        <div class="container py-lg-5">
            <div class="title-section pb-4">
                <h3 class="main-title-agile" id="username"></h3>
                <div class="title-line">
                </div>
            </div>
            <div class="pt-lg-5 pricing-wthree">

                 <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Score</th>
                              <th>Month</th>
                              <th>Year</th>
                            </tr>
                          </thead>
                          <tbody id="scoreTableBody"></tbody>
                    </table>
         
            </div>
        </div>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Retrieve and parse the scores from sessionStorage
                const score = JSON.parse(sessionStorage.getItem('myscore'));

                 // Retrieve user details from session storage
                const user = JSON.parse(sessionStorage.getItem('user'));

                // Display the user's name
                if (user && user.name) {
                    document.getElementById('username').textContent = `${user.name}' Scores`;
                }

                // Reference to the table body
                const tableBody = document.getElementById('scoreTableBody');

                // Loop through the scores and create rows
                score.forEach(item => {
                    const row = document.createElement('tr');
                     // Extract month and year from created_at
                    const createdAt = new Date(item.created_at);
                    const month = createdAt.toLocaleString('default', { month: 'short' });
                    const year = createdAt.getFullYear();

                    row.innerHTML = `
                        <td><span class="btn btn-sm btn-success">${item.score}</span></td>
                        <td>${month}</td>
                        <td>${year}</td>
                    `;

                    // Append the row to the table body
                    tableBody.appendChild(row);
                });
            });
        </script>

    </section>
    <!-- pricing -->
@endsection