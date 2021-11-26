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
            <div class="col-sm-5">
                <div id="form_view">
                    <h2>Add User</h2>
                    <form action="{{ route('user.store') }}" method="POST" class="isautovalid" id="form_add_user" enctype="multipart/form-data">

                        @csrf
                        @include('user.form')

                        <div><input type="submit" value="Create" id="create" class="btn btn-primary"></div>

                    </form>
                </div>

              </div><!-- /.col -->
          <div class="col-sm-7">
            <div class="table-wrap m-top-20">
                <table class="table responsive nowrap" cellpadding="0" cellspacing="0" width="100%" id="datatable_ajax">
                    <thead>
                        <tr class="heading">
                            <th >#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        <tr class="filter">
                            @php $i=0; @endphp
                            <th class="th_{{ $i++; }}"><input type="hidden" class="form-control form-control-sm form-filter kt-input" data-col-index="1" name="id"></th>
                            <th class="th_{{ $i++; }}"><input type="text" class="form-control form-control-sm form-filter kt-input" data-col-index="1" name="name"></th>
                            <th class="th_{{ $i++; }}"><input type="text" class="form-control form-control-sm form-filter kt-input" data-col-index="1" name="email"></th>

                            <th class="th_{{ $i++; }}">
                                <button class="btn btn-success btn-brand kt-btn btn-sm filter-submit" id="search" type="button">
                                    <span><i class="fas fa-search"></i><span>&nbsp;Search</span></span>
                                </button>
                                <button class="btn btn-success btn-sm btn-secondary" id="reset" type="button">
                                    <span><i class="fas fa-undo"></i><span>&nbsp;Reset</span></span>
                                </button>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->

      </div><!-- /.container-fluid -->
    </div>
</div>





@endsection
@section('footerAsset')
 <script src="{{ asset('assets/datatable-1.11.3/js/jquery.dataTables.min.js') }}"></script>
 <script src="{{ asset('assets/datatable-1.11.3/js/dataTables.responsive.min.js') }}"></script>

 <script type="text/javascript">
    toastr.options.preventDuplicates = true;
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        }
    });

        //Form-validation
        $(".isautovalid").validate({
            rules: {
            name : {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
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
           //Datatable ---
           const tableId ='#datatable_ajax';
           var bSortable = true;
           const table = $(tableId).DataTable({
           "aoColumns": [
           { "bSortable": bSortable,sWidth: '5%' },
           { "bSortable": bSortable,sWidth: '0%' },
           { "bSortable": bSortable,sWidth: '3%' },
           { "bSortable": false,sWidth: '1%' }
           ],
           processing: true,
           serverSide: true,
           responsive: true,
           lengthMenu: [11, 50, 100, 200],
           orderCellsTop: true,
           dom: `<'row'<'col-sm-12'tr>>
           <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
           ajax: "{{ route('user.index') }}",
           order: [0,'DESC'],
           'columnDefs': [
               { 'responsivePriority': 1, 'targets': 0 },
               { 'responsivePriority': 2,'orderable': false, 'targets': -1 },
           ],
           columns: [
               {data: 'id', name: 'id',searchable: false},
               {data: 'name', name: 'name'},
               {data: 'email', name: 'email'},
               {data: 'action', name: 'action', orderable: false, searchable: false},
           ]
       });
       table.on( 'responsive-resize', function ( e, datatable, columns ) {
           var count = columns.reduce( function (a,b) {
               return b === false ? a+1 : a;
           }, 0 );

           $.each(columns, function( index, value ) {
             if (value === false) {
               $('.th_'+index).hide();
             }
             else
             {
               $('.th_'+index).show();
             }
           });
       });
       //search in datatable
       $('input.form-filter, select.form-filter').keydown(function(e)
       {
           if (e.keyCode == 13)
           {
              e.preventDefault();
              search_result();
           }
       });
       $('#search').on('click', function(e) {
           e.preventDefault();
           search_result();
       });
       function search_result()
       {
           var params = {};
           $('.filter').find('.kt-input').each(function(i) {
               params[i] = $(this).val();
           });
           $.each(params, function(i, val) {
               // apply search params to datatable
               $(tableId).DataTable().column(i).search(val ? val : '', false, false);
           });
           $(tableId).DataTable().table().draw();
       }

       //reset datatable
       $('#reset').on('click', function(e) {
           e.preventDefault();
           $('.kt-input').val('');
           $(tableId).DataTable().columns().search( '' ).draw();
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

            AddEdit();
            function AddEdit(){
                //ADD product
                $('#form_add_user').on('submit', function(e){
                    e.preventDefault();
                    // if($(this).valid()){
                        var form = this;
                        $.ajax({
                            method:$(form).attr('method'),
                            dataType:"json",
                            url:$(form).attr('action'),
                            data:new FormData(form),
                            processData:false,
                            dataType:'json',
                            contentType:false,
                            beforeSend:function(){
                                    $(form).find('span.error-text').text('');
                            },
                            success: function(data)
                            {

                                if(data.code == 0){
                                    $.each(data.error, function(prefix, val){
                                        $(form).find('span.'+prefix+'_error').text(val[0]);
                                    });
                                }else{
                                    // $(form).trigger('reset');
                                    $(form)[0].reset();
                                    imagPreviewClear();
                                    $(tableId).DataTable().ajax.reload(null, false);
                                    toastr.success(data.msg);
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert(errorThrown);
                            }
                        });

                    // }

                });
            };






        $(tableId).on('click','.edit-user',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var id = $(this).data('edit');
            var token = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                type:'GET',
                url:url,
                data:{"_token":token,"id":id},
                beforeSend:function(){

                },
                success: function(data)
                {
                    $("#form_view").empty().append(data);
                    AddEdit();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });

        });

        // delete record
        $(tableId).on('click','.delete-user', function(e){
            e.preventDefault();
            var url= $(this).attr('href');
            var id = $(this).data('delete');
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type:'DELETE',
                url:url,
                data:{"_token":token,"id":id},
                success: function(data){
                    $(tableId).DataTable().ajax.reload();
                },
                error:function(errorThrown){
                    alert(errorThrown);
                }
            });

        });


</script>
@endsection
