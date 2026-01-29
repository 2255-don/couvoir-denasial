@extends('layouts/layoutMaster')

@section('title', 'Mon Profil')

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Utilisateur /</span> Paramètres du Compte
</h4>

<div class="row">
  <div class="col-md-12">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
      <h5 class="card-header">Détails du Profil</h5>
      <!-- Account -->
      <form id="formAccountSettings" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="d-flex align-items-start align-items-sm-center gap-4">
            <img src="{{ $user->logo ? asset('storage/' . $user->logo) : asset('assets/img/avatars/1.png') }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" style="object-fit: cover;" />
            <div class="button-wrapper">
              <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                <span class="d-none d-sm-block">Changer le logo</span>
                <i class="ti ti-upload d-block d-sm-none"></i>
                <input type="file" id="upload" name="logo" class="account-file-input" hidden accept="image/png, image/jpeg" />
              </label>
              <div class="text-muted">Allowed JPG or PNG. Max size of 2MB</div>
            </div>
          </div>
        </div>
        <hr class="my-0">
        <div class="card-body">
          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="name" class="form-label">Nom Complet</label>
              <input class="form-control" type="text" id="name" name="name" value="{{ $user->name }}" autofocus />
            </div>
            <div class="mb-3 col-md-6">
              <label for="email" class="form-label">E-mail</label>
              <input class="form-control" type="text" id="email" name="email" value="{{ $user->email }}" placeholder="john.doe@example.com" />
            </div>
          </div>
        </div>
        
        <hr class="my-0">
        <div class="card-body">
          <h5 class="mb-4">Changer de Mot de Passe</h5>
          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="current_password" class="form-label">Mot de Passe Actuel</label>
              <input class="form-control" type="password" name="current_password" id="current_password" placeholder="············" />
            </div>
          </div>
          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="new_password" class="form-label">Nouveau Mot de Passe</label>
              <input class="form-control" type="password" name="new_password" id="new_password" placeholder="············" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="new_password_confirmation" class="form-label">Confirmer Nouveau Mot de Passe</label>
              <input class="form-control" type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="············" />
            </div>
          </div>
          <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2">Sauvegarder les modifications</button>
            <button type="reset" class="btn btn-label-secondary">Annuler</button>
          </div>
        </div>
      </form>
      <!-- /Account -->
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  // Simple preview for uploaded image
  document.getElementById('upload').onchange = function (evt) {
    const [file] = this.files
    if (file) {
      document.getElementById('uploadedAvatar').src = URL.createObjectURL(file)
    }
  }
</script>
@endsection
