@extends('admin.layouts.app')

@section('title', 'Edit Profile')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('user.dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Edit Profile</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Hi, {{ $user->username }}!</h2>
                <p class="section-lead">
                    Change information about yourself on this page.
                </p>

                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-5">
                        <div class="card profile-widget">
                            <div class="profile-widget-header">
                                <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture">
                                <div class="profile-widget-items">
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Posts</div>
                                        <div class="profile-widget-item-value">187</div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Followers</div>
                                        <div class="profile-widget-item-value">6,8K</div>
                                    </div>
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Following</div>
                                        <div class="profile-widget-item-value">2,1K</div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">{{ $user->fullname }} <div class="text-muted d-inline font-weight-normal">
                                </div>
                                </div>
                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">Username : {{$user->username}}
                                </div>
                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">Email : {{$user->email}}
                                </div>
                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">Tanggal Lahir : {{$user->tanggal_lahir}}
                                </div>

                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">Alamat : {{$user->tempat}}
                                </div>

                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">Nomor Telephon : {{$user->no_telpon}}
                                </div>

                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">NIK : {{$user->nik}}
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <div class="font-weight-bold mb-2"> {{ $user->username }} </div>
                                <a href="#" class="btn btn-social-icon btn-facebook mr-1">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="btn btn-social-icon btn-twitter mr-1">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-social-icon btn-github mr-1">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a href="#" class="btn btn-social-icon btn-instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-7">
                        <div class="card">
                            <form action="{{ route('admin.profile.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-header">
                                    <h4>Edit Profile</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>NIK</label>
                                            <input type="text" name="nik" class="form-control" value="{{ old('nik', $user->nik) }}" required="">
                                            <div class="invalid-feedback">
                                                Please fill in the NIK
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Username</label>
                                            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required="">
                                            <div class="invalid-feedback">
                                                Please fill in the username
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required="">
                                            <div class="invalid-feedback">
                                                Please fill in the email
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Phone</label>
                                            <input type="tel" name="no_telpon" class="form-control" value="{{ old('no_telpon', $user->no_telpon) }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Tempat</label>
                                            <input type="text" name="tempat" class="form-control" value="{{ old('tempat', $user->tempat) }}" required="">
                                            <div class="invalid-feedback">
                                                Please fill in the place
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Fullname</label>
                                            <input type="text" name="fullname" class="form-control" value="{{ old('fullname', $user->fullname) }}" required="">
                                            <div class="invalid-feedback">
                                                Please fill in the fullname
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <!-- Page Specific JS File -->
@endpush
