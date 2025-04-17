@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('layout/app.css') }}">

@section('content')
    @include('partails.hero')
    @include('partails.eyes')
    @include('partails.marquee')
    @include('partails.features')
    @include('partails.testimonials')
    
@endsection