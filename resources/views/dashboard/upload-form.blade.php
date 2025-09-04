
@extends('dashboard.layout.app')

@section('title', 'Upload Profile Image')

@section('content')

    <h4>Upload Profile Image for User {{ $id }}</h4>

    <!-- Success -->

    @if (session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Display errors if any --}}

    @if ($errors->any())
    <div style="color: red;">
    <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
    </ul>
    </div>
    @endif

    {{-- Upload form --}}
    <form action="{{ route('profile.upload', ['id' => $id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="image">Select Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>
        <br><br>
        <button type="submit">Upload</button>
    </form>

    <hr>

    {{-- Show uploaded image if it exists --}}
    <h3>Current Profile Image:</h3>
<img src="{{ asset('storage/' . $user->profile_image) }}" alt="User Profile Image" width="200">
    
@endsection

