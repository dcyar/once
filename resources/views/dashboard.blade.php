@extends('layouts.adminlte')

{{-- Push extra CSS --}}
@push('css')
    {{-- Add here extra stylesheets --}}
@endpush

{{-- Customize layout sections --}}
@section('subtitle', 'Inicio')
@section('content_header_title', 'Inicio')

{{-- Content body: main page content --}}
@section('content_body')
    <div class="card">
        <div class="card-body">
            <p class="mb-0">Welcome to this beautiful admin panel.</p>
        </div>
    </div>
@endsection

{{-- Push extra scripts --}}
@push('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@endpush
