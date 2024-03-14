@extends('layouts.adminlte')

@push('css')
    {{-- Add here extra stylesheets --}}
@endpush

@section('subtitle', 'Inicio')
@section('content_header_title', 'Inicio')

@section('content_body')
    <div class="card">
        <div class="card-body">
            <p class="mb-0">Welcome to this beautiful admin panel.</p>
        </div>
    </div>
@endsection

@push('js')
    {{-- Add here extra scripts --}}
@endpush
