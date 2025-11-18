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
    <!-- Leaderboard -->
    <section class="wthree-row py-5" id="pricing">
        <div class="container py-lg-5">
            <div class="title-section pb-4">
                <h3 class="main-title-agile">leaderboard </h3>
                <div class="title-line">
                </div>
            </div>
            <div class="pt-lg-5 pricing-wthree">

                <div class="col-md-5">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name...">
                </div>

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
    let currentPage = 1;
    let searchTerm = ""; // global search term

    function loadScores(page = 1, search = "") {
        $.ajax({
            url: `/api/leaderboard/show?page=${page}&search=${search}`,
            method: 'GET',
            success: function(response) {
                const tableBody = document.getElementById('scoreTableBody');
                tableBody.innerHTML = ""; 

                response.data.forEach(item => {
                    const row = document.createElement('tr');

                    const createdAt = new Date(item.month);
                    const month = createdAt.toLocaleString('default', { month: 'short' });
                    const year = createdAt.getFullYear();

                    let scoreColor;
                    if (item.max_score < 200) scoreColor = 'red';
                    else if (item.max_score <= 250) scoreColor = 'black';
                    else scoreColor = 'green';

                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td style="color: ${scoreColor};">${item.max_score}</td>
                        <td>${month}</td>
                        <td>${year}</td>
                    `;

                    tableBody.appendChild(row);
                });

                currentPage = response.current_page;

                $("#prevBtn").prop("disabled", !response.prev_page_url);
                $("#nextBtn").prop("disabled", !response.next_page_url);
            },
            error: function(xhr) {
                console.error(xhr);
            }
        });
    }

    // Debounce function to avoid too many requests
    function debounce(func, delay) {
        let timer;
        return function() {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, arguments), delay);
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadScores(1);

        $("#prevBtn").on("click", function() {
            if (currentPage > 1) {
                loadScores(currentPage - 1, searchTerm);
            }
        });

        $("#nextBtn").on("click", function() {
            loadScores(currentPage + 1, searchTerm);
        });

        // live search listener
        $("#searchInput").on(
            "keyup",
            debounce(function() {
                searchTerm = this.value;
                loadScores(1, searchTerm); // always reset to page 1 when searching
            }, 400)
        );
    });
</script>


<div class="text-center mt-3">
    <button id="prevBtn" class="btn btn-secondary">Previous</button>
    <button id="nextBtn" class="btn btn-primary">Next</button>
</div>

    </section>
    <!-- leaderboard -->
@endsection