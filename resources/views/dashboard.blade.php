@extends('layouts.app')

{{-- @section('title', 'Dashboard - Laravel Admin Panel With Login and Registration') --}}

@section('contents')
  <div class="row">
   <h3 style="color: black"> Dashboard {{ auth()->user()->name }}</h3>
  </div>
@endsection
