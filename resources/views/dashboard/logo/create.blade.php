@extends('dashboard.layout.app')
@section('title', 'Add User')
@section('content')
    <div class="container-fluid">
        <div class="text-right">
            <button class="btn btn-danger mr-2 mb-2" onclick="window.history.back()"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
            <button class="btn btn-warning mr-2 mb-2" onclick="window.location.reload()"><i class="fa fa-spinner" aria-hidden="true"></i></button>
            {{-- <a href="{{ route('logo.list') }}" class="btn btn-primary mr-2 mb-2">
                <i class="fa fa-users" aria-hidden="true"></i>
            </a>             --}}
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2 shadow border">
                <h2 class="my-4">Add Company Info</h2>
                <div id="successMessage" class="alert alert-success" style="display: none;"></div>
                <form id="addLogo" action="{{ route('logo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Company Name</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Company Email address</label> <span class="text-danger">*</span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                        <span class="text-danger" id="email-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" id="contact_number" name="phone" placeholder="Enter contact number" required>
                        <span class="text-danger" id="phone-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="place">Place</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" id="place" name="place" placeholder="Enter place" required>
                    </div>
                    <div class="form-group">
                        <label for="place">Select Logo</label> <span class="text-danger">*</span>
                        <input type="file" class="form-control" id="place" name="logo" placeholder="" required>
                    </div>

                    
                    
                    <button type="submit" class="btn btn-primary mb-2">Add Logo</button>
                </form>
            </div>
        </div>
    </div>
@endsection
