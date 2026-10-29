@extends('layouts.admin')

@section('title', 'Users')
@section('page_title', 'Users')
@section('page_subtitle', 'Registered accounts and access roles')

@section('content')
<div class="admin-users-view" x-data="{
    init() {
        adminDataTable('#users-table', @js(route('admin.data.users')), [
            { data: 'id', name: 'id', render: (id) => '#' + id },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'is_admin', name: 'is_admin', searchable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
        ], { emptyText: 'No registered users found.' });
    }
}">
    <div class="admin-action-bar">
        <p>All registered customers and administrators in the store database.</p>
    </div>

    <div class="admin-table-container admin-dt">
        <table id="users-table" class="admin-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ROLE</th>
                    <th>JOINED</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
