@extends('dashboard.layout.app')
@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800 text-center">Edit Company Info</h1>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg rounded-lg">
                    <div class="card-header bg-primary text-white border-0 rounded-top">
                        <h4 class="m-0 font-weight-bold">Edit Info</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('logo.update', $logo->id) }}" method="POST" id="updateUser" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            {{-- <input type="hidden" name="id" value="{{ $logo->id }}"> --}}

                            <div class="mb-4">
                                <label for="name" class="form-label font-weight-bold">Company Name</label>
                                <input type="text" id="name" name="name" class="form-control  rounded-pill shadow-sm" value="{{ old('name', $logo->name) }}" required>
                                <span class="text-danger" id="name-error"></span>
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="form-label font-weight-bold">Company Phone</label>
                                <input type="tel" id="phone" name="phone" class="form-control rounded-pill shadow-sm" value="{{ old('phone', $logo->phone) }}" required>
                                <span class="text-danger" id="phone-error"></span>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label font-weight-bold">Company Email</label>
                                <input type="email" id="email" name="email" class="form-control  rounded-pill shadow-sm" value="{{ old('email', $logo->email) }}" required>
                                <span class="text-danger" id="email-error"></span>
                            </div>

                            <div class="form-group">
                                <label for="place">Place</label> <span class="text-danger">*</span>
                                <input type="text" class="form-control" id="place" name="place" placeholder="Enter place" value="{{ old('email', $logo->place) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="place">Select Logo</label> <span class="text-danger">*</span>
                                <input type="file" class="form-control" id="place" name="logo" placeholder="" value="" >
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Update Logo</button>
                                <a href="{{ route('logos') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
