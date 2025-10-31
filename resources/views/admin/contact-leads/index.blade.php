@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Contact Leads</h1>
        <a href="{{ route('admin.contact-leads.create') }}" class="btn btn-primary">New Lead</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                        <tr>
                            <td>{{ $lead->id }}</td>
                            <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->phone }}</td>
                            <td>{{ $lead->subject }}</td>
                            <td><span class="badge bg-secondary">{{ $lead->status }}</span></td>
                            <td>{{ $lead->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.contact-leads.show', $lead) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ route('admin.contact-leads.edit', $lead) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.contact-leads.destroy', $lead) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this lead?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No leads found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($leads instanceof \Illuminate\Contracts\Pagination\Paginator)
        <div class="card-footer">{{ $leads->links() }}</div>
        @endif
    </div>
</div>
@endsection


