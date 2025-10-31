@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Lead #{{ $lead->id }}</h1>
        <div>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.contact-leads.edit', $lead) }}">Edit</a>
            <a class="btn btn-secondary btn-sm" href="{{ route('admin.contact-leads.index') }}">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><strong>Name:</strong> {{ $lead->first_name }} {{ $lead->last_name }}</div>
                <div class="col-md-6"><strong>Email:</strong> {{ $lead->email }}</div>
                <div class="col-md-6"><strong>Phone:</strong> {{ $lead->phone ?? '—' }}</div>
                <div class="col-md-6"><strong>Status:</strong> <span class="badge bg-secondary">{{ $lead->status }}</span></div>
                <div class="col-md-12"><strong>Subject:</strong> {{ $lead->subject }}</div>
                <div class="col-md-12"><strong>Message:</strong><br>{{ $lead->message }}</div>
                <div class="col-md-6"><strong>IP:</strong> {{ $lead->ip_address ?? '—' }}</div>
                <div class="col-md-6"><strong>Page:</strong> {{ $lead->page_url ?? '—' }}</div>
                <div class="col-md-12"><strong>User Agent:</strong><br><small class="text-muted">{{ $lead->user_agent ?? '—' }}</small></div>
            </div>
        </div>
    </div>
</div>
@endsection


