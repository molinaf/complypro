@extends('layouts.app2')

@section('content')
<div class="flex justify-center items-start pt-6 bg-gray-100 min-h-screen">
  <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Dashboard Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xl font-bold px-6 py-4">
      Dashboard
    </div>

    <!-- Card Content -->
    <div class="container">
      <!-- Individual Links Styled as Cards -->
      <a href="/main" class="block bg-gray-50 hover:bg-blue-500 hover:text-white rounded-lg px-4 py-3 shadow-sm transition duration-300">
        <span class="text-gray-800 font-semibold">Table Maintenance</span>
      </a>
      <a href="/requisites" class="block bg-gray-50 hover:bg-blue-500 hover:text-white rounded-lg px-4 py-3 shadow-sm transition duration-300">
        <span class="text-gray-800 font-semibold">Link Requisites</span>
      </a>
      <a href="/coming" class="block bg-gray-50 hover:bg-blue-500 hover:text-white rounded-lg px-4 py-3 shadow-sm transition duration-300">
        <span class="text-gray-800 font-semibold">Enrolment</span>
      </a>
      <a href="/coming" class="block bg-gray-50 hover:bg-blue-500 hover:text-white rounded-lg px-4 py-3 shadow-sm transition duration-300">
        <span class="text-gray-800 font-semibold">User Role Maintenance</span>
      </a>
      <a href="/coming" class="block bg-gray-50 hover:bg-blue-500 hover:text-white rounded-lg px-4 py-3 shadow-sm transition duration-300">
        <span class="text-gray-800 font-semibold">Upload/Link Scorm Files</span>
      </a>
      <a href="/coming" class="block bg-gray-50 hover:bg-blue-500 hover:text-white rounded-lg px-4 py-3 shadow-sm transition duration-300">
        <span class="text-gray-800 font-semibold">Reports</span>
      </a>
    </div>

    <!-- Footer -->
    <div class="bg-gray-50 px-6 py-4 text-center">
      <p class="text-gray-600">You are logged in!</p>
    </div>
  </div>
</div>
@endsection
