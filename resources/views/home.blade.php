@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('layout/app.css') }}">

@section('content')
    @include('partails.hero')
    @include('partails.products') 
    @include('partails.features')
    @include('partails.eyes')    
    @include('partails.testimonials')
    
@endsection