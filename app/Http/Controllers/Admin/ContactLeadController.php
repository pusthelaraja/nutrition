<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLead;
use Illuminate\Http\Request;

class ContactLeadController extends Controller
{
    public function index()
    {
        $leads = ContactLead::latest()->paginate(20);
        return view('admin.contact-leads.index', compact('leads'));
    }

    public function create()
    {
        return view('admin.contact-leads.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'status' => 'nullable|string|max:32',
        ]);
        $data['privacy_accepted'] = true;
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->userAgent();
        $data['page_url'] = $request->input('page_url');

        ContactLead::create($data);

        return redirect()->route('admin.contact-leads.index')->with('success', 'Lead created.');
    }

    public function show(ContactLead $contact_lead)
    {
        return view('admin.contact-leads.show', ['lead' => $contact_lead]);
    }

    public function edit(ContactLead $contact_lead)
    {
        return view('admin.contact-leads.edit', ['lead' => $contact_lead]);
    }

    public function update(Request $request, ContactLead $contact_lead)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'status' => 'required|string|max:32',
        ]);
        $contact_lead->update($data);
        return redirect()->route('admin.contact-leads.index')->with('success', 'Lead updated.');
    }

    public function destroy(ContactLead $contact_lead)
    {
        $contact_lead->delete();
        return redirect()->route('admin.contact-leads.index')->with('success', 'Lead deleted.');
    }
}


