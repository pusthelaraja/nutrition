@extends('layouts.admin')

@section('title', 'Activity Log Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Activity Log Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Logs
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $activity->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $activity->description }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Event:</strong></td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $activity->event }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Log Name:</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ $activity->log_name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Log Level:</strong></td>
                                    <td>
                                        @php
                                            $levelColors = [
                                                'info' => 'primary',
                                                'warning' => 'warning',
                                                'error' => 'danger',
                                                'critical' => 'dark'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $levelColors[$activity->log_level] ?? 'secondary' }}">
                                            {{ ucfirst($activity->log_level) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>
                                        {{ $activity->created_at->format('M d, Y H:i:s') }}
                                        <br>
                                        <small class="text-muted">{{ $activity->human_time }}</small>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>User & Subject Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Causer:</strong></td>
                                    <td>
                                        @if($activity->causer)
                                            {{ $activity->causer->name }} ({{ $activity->causer_type }})
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Subject:</strong></td>
                                    <td>
                                        @if($activity->subject)
                                            {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>IP Address:</strong></td>
                                    <td>{{ $activity->ip_address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>User Agent:</strong></td>
                                    <td>
                                        <small class="text-muted">{{ $activity->user_agent ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>URL:</strong></td>
                                    <td>
                                        <small class="text-muted">{{ $activity->url ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Method:</strong></td>
                                    <td>
                                        <span class="badge badge-light">{{ $activity->method ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($activity->properties)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Additional Properties</h5>
                            <div class="card">
                                <div class="card-body">
                                    <pre class="mb-0"><code>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
