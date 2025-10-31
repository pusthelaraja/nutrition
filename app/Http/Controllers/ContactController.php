<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactLead;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactLeadReceived;
use App\Mail\ContactLeadConfirmation;
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
        $lead = new ContactLead();
        $lead->first_name = $request->first_name;
        $lead->last_name = $request->last_name;
        $lead->email = $request->email;
        $lead->phone = $request->phone;
        $lead->subject = $request->subject;
        $lead->message = $request->message;
        $lead->privacy_accepted = true;
        $lead->ip_address = $request->ip();
        $lead->user_agent = $request->userAgent();
        $lead->page_url = $request->headers->get('referer');
        $lead->status = 'new';
        $lead->save();

        // Send emails: to admin/management and confirmation to customer
        $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
        if ($adminEmail) {
            try { Mail::to($adminEmail)->send(new ContactLeadReceived($lead)); } catch (\Throwable $e) { \Log::warning('Admin mail failed: '.$e->getMessage()); }
        }
        try { Mail::to($lead->email)->send(new ContactLeadConfirmation($lead)); } catch (\Throwable $e) { \Log::warning('Customer mail failed: '.$e->getMessage()); }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message! We will get back to you within 24 hours.'
            ]);
        }

        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you within 24 hours.');
    }
}
