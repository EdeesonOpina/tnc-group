@include('layouts.auth.header')

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('auth.profile') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active">Account</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Account</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Basic Information</strong></p>
                <p class="text-muted">Edit your account details and settings.</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input id="fname" name="firstname" type="text" class="form-control" placeholder="First name" value="{{ auth()->user()->firstname }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input id="lname" name="lastname" type="text" class="form-control" placeholder="Last name" value="{{ auth()->user()->lastname }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input id="mobile" name="mobile" type="text" class="form-control" placeholder="Mobile" value="{{ auth()->user()->mobile }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input id="phone" name="phone" type="text" class="form-control" placeholder="Phone" value="{{ auth()->user()->phone }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="mobile">Line Address 1</label>
                            <input id="mobile" name="line_address_1" type="text" class="form-control" placeholder="Line Address 1" value="{{ auth()->user()->line_address_1 }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="phone">Line Address 2</label>
                            <input id="phone" name="line_address_2" type="text" class="form-control" placeholder="Line Address 2" value="{{ auth()->user()->line_address_2 }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="desc">Bio / Description</label>
                    <textarea id="desc" name="biography" rows="4" class="form-control" placeholder="Bio / description ..."></textarea>
                </div>
                <div class="form-group">
                    <label for="country">Country</label><br>
                    <select id="country" class="custom-select" style="width: auto;">
                        <option value="Philippines">Philippines</option>
                        <option value="Australia">Australia</option>
                    </select>
                    <small class="form-text text-muted">The country is not visible to other users.</small>
                </div>
                <div class="form-group">
                    <label for="subscribe">Subscribe to newsletter</label><br>
                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                        <input checked="" type="checkbox" id="subscribe" class="custom-control-input">
                        <label class="custom-control-label" for="subscribe">Yes</label>
                    </div>
                    <label for="subscribe" class="mb-0">Yes</label>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Profile Settings</strong></p>
                <p class="text-muted">Update your public profile with relevant and meaningful information.</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <div class="form-group">
                    <label>Avatar</label>
                    <div class="dz-clickable media align-items-center" data-toggle="dropzone" data-dropzone-url="http://" data-dropzone-clickable=".dz-clickable" data-dropzone-files='["assets/images/account-add-photo.svg"]'>
                        <div class="dz-preview dz-file-preview dz-clickable mr-3">
                            <div class="avatar" style="width: 80px; height: 80px;">
                                @if (auth()->user()->avatar)
                                    <img src="{{ url(auth()->user()->avatar) }}" class="avatar-img rounded" alt="..." data-dz-thumbnail>
                                @else
                                    <img src="{{ url(env('APP_ICON')) }}" class="avatar-img rounded" alt="..." data-dz-thumbnail>
                                @endif
                            </div>
                        </div>
                        <div class="media-body">
                            <button class="btn btn-sm btn-primary dz-clickable">Choose photo</button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="social1">Social links</label>
                    <div class="input-group input-group-merge mb-2" style="width: 270px;">
                        <input id="social1" type="text" class="form-control form-control-prepended" name="facebook" placeholder="Facebook">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fab fa-facebook"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-merge mb-2" style="width: 270px;">
                        <input id="social2" type="text" class="form-control form-control-prepended" name="twitter" placeholder="Twitter">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fab fa-twitter"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-merge mb-2" style="width: 270px;">
                        <input id="social3" type="text" class="form-control form-control-prepended" name="instagram" placeholder="Instagram">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fab fa-instagram"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="text-right mb-5">
        <a href="" class="btn btn-success">Save</a>
    </div>
</div>

@include('layouts.auth.footer')