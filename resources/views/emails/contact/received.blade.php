<h2>New Contact Lead</h2>
<p><strong>Name:</strong> {{ $lead->first_name }} {{ $lead->last_name }}</p>
<p><strong>Email:</strong> {{ $lead->email }}</p>
<p><strong>Phone:</strong> {{ $lead->phone ?? '—' }}</p>
<p><strong>Subject:</strong> {{ $lead->subject }}</p>
<p><strong>Message:</strong></p>
<p style="white-space: pre-line;">{{ $lead->message }}</p>
<hr>
<p><strong>IP:</strong> {{ $lead->ip_address ?? '—' }}</p>
<p><strong>Page URL:</strong> {{ $lead->page_url ?? '—' }}</p>
<p><strong>Received at:</strong> {{ $lead->created_at ?? now() }}</p>

