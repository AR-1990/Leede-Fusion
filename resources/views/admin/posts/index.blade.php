@extends('layouts.admin')

@section('title', 'Journal')
@section('page_title', 'Journal')
@section('page_subtitle', 'Write articles, styling guides and fabric stories')

@section('content')
<div x-data="{
    init() {
        adminDataTable('#posts-table', @js(route('admin.data.posts')), [
            { data: 'thumb', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'category', name: 'category' },
            { data: 'is_published', name: 'is_published', searchable: false },
            { data: 'published_at', name: 'published_at', searchable: false },
            { data: 'actions', orderable: false, searchable: false, className: 'dt-actions' },
        ], {
            order: [[4, 'desc']],
            emptyText: 'No articles yet. Write your first one.',
        });
    }
}">
    @if (session('status'))
        <div class="admin-flash">{{ session('status') }}</div>
    @endif

    <div class="admin-action-bar">
        <p>Published articles appear on /journal, the home page and the sitemap.</p>
        <a href="{{ route('admin.posts.create') }}" class="btn-admin-primary" style="text-decoration: none;">+ NEW ARTICLE</a>
    </div>

    <div class="admin-table-container admin-dt">
        <table id="posts-table" class="admin-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>COVER</th>
                    <th>TITLE</th>
                    <th>CATEGORY</th>
                    <th>STATUS</th>
                    <th>PUBLISHED</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
