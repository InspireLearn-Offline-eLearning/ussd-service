<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\User; // Example model

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class LandingPageController extends Controller
{
    public function index()
    {
        // Example HTTP call to an external API

        try {
            $response = Http::post('http://127.0.0.1:8000/api/asteriskuser', [
                'phone' => '999599810',
            ]);
            // Check if the request was successful
            if ($response->successful()) {
                $data = $response->json(); // Get the data as an array
            } else {
                $data = "Something went wrong boss, sorry"; // Handle the error or set a default value
            }

            // $data = "Test better than nothing at all!";
            // Pass the data to the view
            return view('landing', ['data' => $data]);
        } catch (\Exception $e) {
            // Handle exceptions, such as network errors or connection timeouts
            \Log::error('Exception: ' . $e->getMessage());
            return view('landing', ['data' => $e->getMessage()]);
        }
    }
}
