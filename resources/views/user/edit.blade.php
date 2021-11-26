<h2>Edit User</h2>
<form action="{{ route('user.update',['user'=>$user->id]) }}" class="isautovalid"  method="POST" id="form_add_user" enctype="multipart/form-data">

    @csrf
    @method('PUT')
    @include('user.form')

    <div><input type="submit" value="Update" id="update" class="btn btn-primary"></div>

</form>
