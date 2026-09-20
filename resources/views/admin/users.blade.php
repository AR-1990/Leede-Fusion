@extends('layouts.admin')

@section('title', 'Users')
@section('page_title', 'Users')
@section('page_subtitle', 'Registered accounts and access roles')

@section('content')
<div class="admin-users-view">
    <div class="admin-action-bar">
        <p>List of all registered customers and administrators in the store database.</p>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ROLE</th>
                    <th>JOINED</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td style="font-weight: 700;">#{{ $user->id }}</td>
                        <td style="font-weight: 600;">{{ $user->name }}</td>
                        <td>
                            <span style="font-family: ui-monospace, monospace; font-size: 13px;">{{ $user->email }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $user->is_admin ? 'completed' : 'pending' }}">
                                {{ $user->is_admin ? 'ADMIN' : 'CUSTOMER' }}
                            </span>
                        </td>
                        <td style="color: #64748b; font-size: 13px;">
                            {{ $user->created_at ? $user->created_at->format('M d, Y') : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colSpan="5" style="text-align: center; padding: 48px; color: #94a3b8;">
                            No registered users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
