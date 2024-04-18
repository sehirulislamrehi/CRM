@extends('error.template.base_error')
@section('title')
    404 | Not Found
@endsection
@push('css')
    <style>
        body {
            background: rgb(2, 0, 36);
            background: -moz-linear-gradient(163deg, rgba(2, 0, 36, 1) 0%, rgba(255, 111, 146, 1) 50%, rgba(255, 255, 255, 1) 100%);
            background: -webkit-linear-gradient(163deg, rgba(2, 0, 36, 1) 0%, rgba(255, 111, 146, 1) 50%, rgba(255, 255, 255, 1) 100%);
            background: linear-gradient(163deg, rgba(2, 0, 36, 1) 0%, rgba(255, 111, 146, 1) 50%, rgba(255, 255, 255, 1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#020024", endColorstr="#ffffff", GradientType=1);
        }

    </style>
@endpush

@section('content')
    <div class="card p-5">
        <h3 class="text-center">CRM <sub>V-1.0</sub></h3>
        <div class="card-body">
            <div class="mb-5 text-center">
                <img class="error-icon" src="{{asset('backend/images/icon/404.png')}}" alt="">
                <h1 class="error-header">404 | Page Not Found</h1>
            </div>
            <p class="card-text text-danger text-center font-weight-bold">⚠️ {{ $errorText ?? ''}}</p>
        </div>
        <div class="text-center">
            <a href="{{route('admin.dashboard.index')}}" class="btn btn-primary">Return to home</a>
        </div>
    </div>
@endsection
