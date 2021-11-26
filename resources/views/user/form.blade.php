<div class="form-group">
    <label for="avatar">Avatar</label>
    <input id="avatar" type="file" name="avatar" class="form-control-file" accept='image/*'>
    <span class="text-danger error-text  avatar_error"></span>
    <div class="img-holder"></div>
</div>
@error('avatar')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
<div class="form-group">
    <label for="name">Name<span>*</span></label>
    <input id="name" type="text" name="name" class="form-control required"  value="{{ old('name', optional($user ?? null)->name) }}" >
    <span class="text-danger error-text  name_error"></span>
</div>
@error('name')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
<div class="form-group">
    <label for="email">Email<span>*</span></label>
    <input id="email" type="text" name="email" class="form-control required"  value="{{ old('email', optional($user ?? null)->email) }}" >
    <span class="text-danger error-text  email_error"></span>
</div>
@error('email')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="form-group">
    <label for="password">Password<span>*</span></label>
    <input id="password" type="password" name="password" class="form-control required">
    <span class="text-danger error-text  password_error"></span>
</div>
@error('password')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="form-group">
    <label for="password">Confirm Password<span>*</span></label>
    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control required">
    <span class="text-danger error-text  password_confirmation_error"></span>
</div>
@error('password_confirmation')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror


