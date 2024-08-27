# GitHub Followers App

## Description
GitHub Followers is a web application that allows users to search for a GitHub username and view the user's follower information. The app displays the user's GitHub handle, follower count, and a list of the user's followers (avatars only). It includes a "load more" feature to fetch additional followers for users with large follower counts.

## Features
- Search for GitHub users by username
- Display user's GitHub handle and follower count
- Show a list of user's followers with their avatars
- "Load more" functionality to fetch additional followers

## Technologies Used
- PHP (Frontend & Backend)
- Laravel framework (preferred, but other PHP frameworks can be used)
- GitHub API
- AJAX for asynchronous requests

## Requirements
- PHP 7.4 or higher
- Composer

## Installation
1. Clone the repository:
   ```
   git clone https://github.com/yourusername/github-followers.git
   cd github-followers
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

3. Copy the `.env.example` file to `.env` and configure your environment variables:
   ```
   cp .env.example .env
   ```

5. Generate an application key:
   ```
   php artisan key:generate
   ```

8. Start the development server:
   ```
   php artisan serve
   ```

## Usage
1. Open the application in your web browser.
2. Enter a GitHub username in the search field.
3. Click the "Search" button to retrieve the user's information and followers.
4. If the user has more followers than can be displayed on one page, use the "Load More" button to fetch additional followers.

## Configuration
- Adjust the number of followers displayed per page in the `env` file.

## Note
This application is a demonstration of PHP and JavaScript skills, focusing on functionality rather than design. The primary goal is to showcase the ability to work with PHP, make API calls, and handle AJAX requests in JavaScript.
