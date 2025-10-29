@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Activity Logs</h3>
                    <div class="card-tools">
                        <span class="text-muted">Activity Logs</span>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="log_name" class="form-control">
                                    <option value="">All Log Types</option>
                                    <option value="default" {{ request('log_name') == 'default' ? 'selected' : '' }}>Default</option>
                                    <option value="auth" {{ request('log_name') == 'auth' ? 'selected' : '' }}>Authentication</option>
                                    <option value="order" {{ request('log_name') == 'order' ? 'selected' : '' }}>Orders</option>
                                    <option value="payment" {{ request('log_name') == 'payment' ? 'selected' : '' }}>Payments</option>
                                    <option value="shipping" {{ request('log_name') == 'shipping' ? 'selected' : '' }}>Shipping</option>
                                    <option value="system" {{ request('log_name') == 'system' ? 'selected' : '' }}>System</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="event" class="form-control">
                                    <option value="">All Events</option>
                                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                    <option value="viewed" {{ request('event') == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="log_level" class="form-control">
                                    <option value="">All Levels</option>
                                    <option value="info" {{ request('log_level') == 'info' ? 'selected' : '' }}>Info</option>
                                    <option value="warning" {{ request('log_level') == 'warning' ? 'selected' : '' }}>Warning</option>
                                    <option value="error" {{ request('log_level') == 'error' ? 'selected' : '' }}>Error</option>
                                    <option value="critical" {{ request('log_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>


                    <!-- Activity Logs Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Description</th>
                                    <th>Event</th>
                                    <th>User</th>
                                    <th>Subject</th>
                                    <th>Level</th>
                                    <th>IP</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>
                                        <span class="text-truncate" style="max-width: 200px;" title="{{ $log->description }}">
                                            {{ $log->description }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $log->event }}</span>
                                    </td>
                                    <td>
                                        @if($log->causer)
                                            {{ $log->causer->name }}
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->subject)
                                            {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $levelColors = [
                                                'info' => 'primary',
                                                'warning' => 'warning',
                                                'error' => 'danger',
                                                'critical' => 'dark'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $levelColors[$log->log_level] ?? 'secondary' }}">
                                            {{ ucfirst($log->log_level) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $log->ip_address }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $log->created_at->format('M d, Y H:i') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $log->human_time }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No activity logs found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
