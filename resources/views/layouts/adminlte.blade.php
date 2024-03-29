@extends('adminlte::page')

{{-- Extend and customize the browser title --}}
@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle')
        | @yield('subtitle')
    @endif
@endsection


{{-- Add common CSS customizations --}}
@prepend('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endprepend

{{-- Extend and customize the page content header --}}
@section('content_header')
    @hasSection('content_header_title')
        <h1>
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@endsection

{{-- Rename section content to content_body --}}
@section('content')
    @yield('content_body')
@endsection

{{-- Create a common footer --}}
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.url', '#') }}">
            {{ config('app.name', 'My company') }}
        </a>
    </strong>
@endsection

{{-- Add common Javascript/Jquery code --}}
@prepend('js')
    <script>
        $(document).ready(function() {
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json'
                }
            });
        });
    </script>
@endprepend
