@extends('layouts.app')
@section('content')
    <div class="main-content app-content mt-0">
          <div class="side-app">
              <div class="main-container container-fluid">
                  <div class="page-header">
                      <h1 class="page-title">Edit Profile</h1>
                      <div>
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="#">Pages</a></li>
                              <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                          </ol>
                      </div>
                  </div>
                  <div class="row">
                      @include('profile.partials.update-password-form')
                      @include('profile.partials.update-profile-information-form')
                  </div>
              </div>
          </div>
      </div>
@endsection
  