<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Followers</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        h1 {
            margin-bottom: 20px;
        }

        #github-search-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        #github-search-form input {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            /* Adjust width as needed */
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #github-search-form button, #more {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #github-search-form button:hover {
            background-color: #0056b3;
        }

        p, h2{
            text-align: center;
        }
    </style>

</head>

<body>
    <h1>Search GitHub Followers</h1>
    <form id="github-search-form">
        <input type="text" id="username" placeholder="Enter GitHub Username" required>
        <button type="submit">Search</button>
    </form>

    <div id="user"></div>
    <div id="followers"></div>
    <button id="more" style="display: none; margin-bottom: 50px;">Load More</button>

    <script>
        $(document).ready(function () {
            let currentPage = 1; // Initialize the current page to 1
            const perPage = {{ config('app.users_per_page') }}; // Number of followers per page

            $('#github-search-form').on('submit', function (e) {
                e.preventDefault();
                let username = $('#username').val();
                currentPage = 1; // Reset the page to 1 on a new search

                $.ajax({
                    url: '{{ route('search') }}',
                    type: 'POST',
                    data: {
                        username: username,
                        page: currentPage,
                        per_page: perPage,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        console.log('Search Success:', data); // Debugging line
                        displayUserInfo(data.user);
                        displayFollowers(data.followers);
                        toggleLoadMoreButton(data.nextPage);
                    },
                    error: function () {
                        alert('User not found or error occurred.');
                    }
                });
            });

            $('#more').on('click', function () {
                let username = $('#username').val();
                currentPage++; // Incrementing the page number

                $.ajax({
                    url: '{{ route('load-more') }}',
                    type: 'POST',
                    data: {
                        username: username,
                        page: currentPage,
                        per_page: perPage,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        console.log('Load More Success:', data); // Debugging line
                        displayFollowers(data.followers, true);
                        toggleLoadMoreButton(data.nextPage);
                    },
                    error: function () {
                        alert('Failed to load more followers.');
                    }
                });
            });

            function displayUserInfo(user) {
                $('#user').html(`
                    <h2>User: ${user.login}</h2>
                    <p><u>Followers: ${user.followers}</u></p>
                `);
            }

            function displayFollowers(followers, append = false) {
                let followersHtml = followers.map(follower => `
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <img src="${follower.avatar_url}" alt="${follower.login}" width="50" 
                            style="border-radius: 50%; margin-right: 10px;">
                        <span>${follower.login}</span>
                    </div>
                `).join('');

                if (append) {
                    $('#followers').append(followersHtml);
                } else {
                    $('#followers').html(followersHtml);
                }
            }

            function toggleLoadMoreButton(nextPage) {
                console.log('Next Page:', nextPage); // Debug line
                if (nextPage) {
                    $('#more').show();
                } else {
                    $('#more').hide();
                }
            }
        });
    </script>

</body>

</html>