@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center gap-5 p-5 bg-gray-100">

  <!-- Categories Section -->
  <div class="flex justify-center items-center w-72 h-24 bg-gray-50 text-gray-800 font-bold text-lg hover:bg-blue-500 rounded-lg px-4 py-3 shadow-sm transition duration-300">
    <a href="/categories">Categories</a>
  </div>

  <!-- Authorisations Section -->
  <div class="flex justify-center items-center w-72 h-24 bg-gray-50 text-gray-800 font-bold text-lg hover:bg-blue-500 rounded-lg px-4 py-3 shadow-sm transition duration-300">
    <a href="/authorisations">Authorisations</a>
  </div>

  <!-- Items Grid Section -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full max-w-4xl">
    <a href="/modules" class="flex justify-center items-center h-32 bg-white text-gray-700 font-semibold text-basehover:scale-105 hover:bg-blue-500 rounded-lg px-4 py-3 shadow-sm transition duration-300">
      eLearning
    </a>
    <a href="/f2fs" class="flex justify-center items-center h-32 bg-white text-gray-700 font-semibold text-basehover:scale-105 hover:bg-blue-500 rounded-lg px-4 py-3 shadow-sm transition duration-300">
      Face 2 Face
    </a>
    <a href="/inductions" class="flex justify-center items-center h-32 bg-white text-gray-700 font-semibold text-basehover:scale-105 hover:bg-blue-500 rounded-lg px-4 py-3 shadow-sm transition duration-300">
      Inductions
    </a>
    <a href="/licenses" class="flex justify-center items-center h-32 bg-white text-gray-700 font-semibold text-basehover:scale-105 hover:bg-blue-500 rounded-lg px-4 py-3 shadow-sm transition duration-300">
      Licenses
    </a>
  </div>

</div>
@endsection