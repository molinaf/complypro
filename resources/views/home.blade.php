@extends('layouts.app2')

@section('content')
<div class="flex justify-center items-start pt-6 bg-gray-100 min-h-screen">
  <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">

    @php
    $role = auth()->user()->role ?? null;
@endphp

@if($role === 'U')
    <script>window.location.href = "/dashboard/user";</script>
@elseif($role === 'S')
    <script>window.location.href = "/dashboard/supervisor";</script>
@elseif($role === 'G')
    <script>window.location.href = "/dashboard/global-supervisor";</script>
@elseif($role === 'M')
    <script>window.location.href = "/dashboard/manager";</script>
@elseif($role === 'A')
    <script>window.location.href = "/dashboard/admin";</script>
@else
    <div class="text-red-500">Unauthorized Role</div>
@endif


  </div>
</div>
@endsection
