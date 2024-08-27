<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GithubController extends Controller
{
    private $githubApiUrl;

    public function __construct()
    {
        $this->githubApiUrl = env('GITHUB_API_BASE_URL');
    }

    public function index()
    {
        return view('github');
    }

    public function search(Request $request)
    {
        # Setting up the required info (username, page, per_page)
        $username = $request->input('username');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        # Making the API calls
        $userResponse = Http::get("{$this->githubApiUrl}/users/{$username}");
        $followersResponse = Http::get("{$this->githubApiUrl}/users/{$username}/followers", [
            'per_page' => $perPage,
            'page' => $page
        ]);

        # Checking for successful responses
        if ($userResponse->successful() && $followersResponse->successful()) {
            return response()->json([
                'user' => $userResponse->json(),
                'followers' => $followersResponse->json(),
                'nextPage' => $this->getNextPageUrl(
                    $page,
                    $followersResponse->json(),
                    $perPage
                )
            ]);
        }

        # If the response is not successful return error
        return response()->json(['error' => 'User not found'], 404);
    }

    public function loadMore(Request $request)
    {
        # Setting up the required info (username, page, per_page)
        $username = $request->input('username');
        $page = $request->input('page', 1); // Get the current page number from the request
        $perPage = $request->input('per_page', 30); // Get the number of followers per page

        # Making the API call
        $response = Http::get("{$this->githubApiUrl}/users/{$username}/followers", [
            'per_page' => $perPage,
            'page' => $page
        ]);

        # Checking for successful response
        if ($response->successful()) {
            return response()->json([
                'followers' => $response->json(),
                'nextPage' => $this->getNextPageUrl(
                    $page,
                    $response->json(),
                    $perPage
                )
            ]);
        }

        # If the response is not successful return error
        return response()->json(['error' => 'Failed to load more followers.'], 500);
    }

    private function getNextPageUrl($currentPage, $followers, $perPage)
    {
        // Check if we have reached the end of the results
        if (count($followers) < $perPage) {
            return null; // No more pages
        }

        // Otherwise, return the next page number
        return $currentPage + 1;
    }

}
