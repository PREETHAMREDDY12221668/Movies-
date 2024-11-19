<?php
class TMDbClient {
    private $apiKey;
    private $baseUrl;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://api.themoviedb.org/3/';
    }

    // Base function to make API requests
    public function request($endpoint, $params = []) {
        $params['api_key'] = $this->apiKey;
        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);

        $response = file_get_contents($url);
        if ($response === false) {
            return null; // Error handling if the API request fails
        }

        return json_decode($response, true);
    }

    // Fetch popular movies
    public function getPopularMovies($page = 1) {
        return $this->request('movie/popular', ['page' => $page]);
    }

    // Fetch now playing movies
    public function getNowPlayingMovies($page = 1) {
        return $this->request('movie/now_playing', ['page' => $page]);
    }

    // Fetch movie details by ID
    public function getMovieDetails($movieId, $appendToResponse = '') {
        return $this->request("movie/$movieId", ['append_to_response' => $appendToResponse]);
    }

    // Search movies by title
    public function searchMovies($query, $page = 1) {
        return $this->request('search/movie', ['query' => $query, 'page' => $page]);
    }
    public function getNowPlayingMoviesInIndia($page = 1) {
        return $this->request('movie/now_playing', ['region' => 'IN', 'page' => $page]);
    }
    // You can add more methods here for other endpoints
    public function getGenres() {
        $response = $this->request('genre/movie/list');
        return $response['genres'] ?? [];
    }
}
