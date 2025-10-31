<p>Hi {{ $lead->first_name }},</p>
<p>Thanks for contacting {{ config('app.name') }}. We received your message and will get back to you within 24 hours.</p>
<p><strong>Subject:</strong> {{ $lead->subject }}</p>
<p><strong>Your Message:</strong></p>
<p style="white-space: pre-line;">{{ $lead->message }}</p>
<p>Regards,<br>{{ config('app.name') }} Support</p>

