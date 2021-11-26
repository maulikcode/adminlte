@extends('layouts.app')
@section('assets')

<link rel="stylesheet" href="{{ asset('assets/datatable-1.11.3/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/datatable-1.11.3/css/responsive.dataTables.min.css') }}">
@endsection
@section('title', 'User')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>Update Profile</h2>
                <form action="{{ route('user.updateProfile',['user'=>$user->id]) }}" class="isautovalid"  method="POST" id="form_add_user" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')
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


                    <div><input type="submit" value="Update" id="update" class="btn btn-primary"></div>

                </form>

              </div><!-- /.col -->
          <div class="col-sm-7">

          </div><!-- /.col -->
        </div><!-- /.row -->

      </div><!-- /.container-fluid -->
    </div>
</div>





@endsection
@section('footerAsset')
 <script type="text/javascript">
        //Form-validation
        $(".isautovalid").validate({
            rules: {
            name : {
                    required: true,
                    minlength: 2
                },
                password: {
                    required: true,
                    minlength: 8
                },
                password_confirmation: {
                    required: true,
                    minlength: 8,
                    equalTo: "#password"
                },
                avatar: {
                    extension: "jpg|jpeg|png|JPG|JPEG|PNG"
                }
            }
        });

        //Reset input file
        $('input[type="file"][name="avatar"]').val('');
            //Image preview
            $('input[type="file"][name="avatar"]').on('change', function(){
                var img_path = $(this)[0].value;
                var img_holder = $('.img-holder');
                var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();

                if(extension == 'jpeg' || extension == 'jpg' || extension == 'png'){
                    if(typeof(FileReader) != 'undefined'){
                        img_holder.empty();
                        var reader = new FileReader();
                        reader.onload = function(e){
                            $('<img/>',{'src':e.target.result,'class':'img-fluid','style':'max-width:100px;margin-bottom:10px;'}).appendTo(img_holder);
                        }
                        img_holder.show();
                        reader.readAsDataURL($(this)[0].files[0]);
                    }else{
                        $(img_holder).html('This browser does not support FileReader');
                    }
                }else{
                    $(img_holder).empty();
                }
            });
            imagPreviewClear()
            function imagPreviewClear(){
                var the= $('input[type="file"][name="avatar"]');
                    var img_path = the[0].value;
                    var img_holder = $('.img-holder');
                    var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();

                    if(extension == 'jpeg' || extension == 'jpg' || extension == 'png'){
                        if(typeof(FileReader) != 'undefined'){
                            img_holder.empty();
                            var reader = new FileReader();
                            reader.onload = function(e){
                                $('<img/>',{'src':e.target.result,'class':'img-fluid','style':'max-width:100px;margin-bottom:10px;'}).appendTo(img_holder);
                            }
                            img_holder.show();
                            reader.readAsDataURL(the[0].files[0]);
                        }else{
                            $(img_holder).html('This browser does not support FileReader');
                        }
                    }else{
                        $(img_holder).empty();
                    }
            }

</script>
@endsection
