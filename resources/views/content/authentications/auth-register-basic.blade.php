@php
  $customizerHidden = 'customizer-hide';
  $pageConfigs = ['myLayout' => 'blank'];
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Register Basic - Pages')

@section('vendor-style')
  <!-- Vendor -->
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('page-style')
  <!-- Page -->
  <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
  <script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection

@section('content')
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-4">

        <!-- Register Card -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4 mt-2">
              <a href="{{url('/')}}" class="app-brand-link gap-2">
                <span
                  class="app-brand-logo demo">@include('_partials.macros', ["height" => 20, "withbg" => 'fill: #fff;'])</span>
                <span class="app-brand-text demo text-body fw-bold ms-1">{{config('variables.templateName')}}</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1 pt-2">Adventure starts here 🚀</h4>
            <p class="mb-4">Make your app management easy and fun!</p>

            <form id="formAuthentication" class="mb-3" action="{{ route('register') }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label for="name" class="form-label">Nom Complet</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                  placeholder="Entrez votre nom" value="{{ old('name') }}" autofocus>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                  placeholder="Entrez votre email" value="{{ old('email') }}">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">Mot de passe</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                  <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                  @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
              <div class="mb-3">
                <label for="logo" class="form-label">Logo de l'entreprise</label>
                <input class="form-control @error('logo') is-invalid @enderror" type="file" id="logo" name="logo">
                @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required>
                  <label class="form-check-label" for="terms-conditions">
                    J'accepte la
                    <a href="javascript:void(0);">politique de confidentialité</a>
                  </label>
                </div>
              </div>
              <button class="btn btn-primary d-grid w-100">
                S'inscrire
              </button>
            </form>

            <p class="text-center">
              <span>Vous avez déjà un compte ?</span>
              <a href="{{ route('login') }}">
                <span>Connectez-vous</span>
              </a>
            </p>

            <div class="divider my-4">
              <div class="divider-text">or</div>
            </div>

            <div class="d-flex justify-content-center">
              <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
                <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
              </a>

              <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">
                <i class="tf-icons fa-brands fa-google fs-5"></i>
              </a>

              <a href="javascript:;" class="btn btn-icon btn-label-twitter">
                <i class="tf-icons fa-brands fa-twitter fs-5"></i>
              </a>
            </div>
          </div>
        </div>
        <!-- Register Card -->
      </div>
    </div>
  </div>
@endsection