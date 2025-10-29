<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        return view('frontend.contact');
    }

    /**
     * Submit contact form
     */
    public function submit(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'privacy' => 'required|accepted'
        ]);

        // This would typically save the contact form to database
        // and send email notifications

        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you within 24 hours.');
    }
}
