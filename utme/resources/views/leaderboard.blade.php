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
                <h3 class="main-title-agile">leaderboard </h3>
                <div class="title-line">
                </div>
            </div>
            <div class="pt-lg-5 pricing-wthree">

                <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Name</th>
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
            //show user pin count
            document.addEventListener('DOMContentLoaded', (event) => {

                $.ajax({
                    url: '/api/leaderboard/show',
                    method: 'GET',
                    headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                          // Reference to the table body
                          const tableBody = document.getElementById('scoreTableBody');
                          // Retrieve user details from session storage
                          const scores = JSON.parse(response.scores);

                          console.log(scores);
                        // Loop through the scores and create rows
                        scores.forEach(item => {
                            const row = document.createElement('tr');
                             // Extract month and year from created_at
                            const createdAt = new Date(item.month);
                            const month = createdAt.toLocaleString('default', { month: 'short' });
                            const year = createdAt.getFullYear();

                            // Determine score color
                            let scoreColor;
                            if (item.max_score < 200) {
                                scoreColor = 'red';
                            } else if (item.max_score <= 250) {
                                scoreColor = 'black';
                            } else {
                                scoreColor = 'green';
                            }

                            row.innerHTML = `
                                <td>${item.name}</td>
                                <td style="color: ${scoreColor};">${item.max_score}</td>
                                <td>${month}</td>
                                <td>${year}</td>
                            `;

                            // Append the row to the table body
                            tableBody.appendChild(row);
                        });
                      
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

            });

        </script>
    </section>
    <!-- pricing -->
@endsection