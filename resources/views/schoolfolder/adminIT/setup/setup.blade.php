@extends('adminPortal.layouts.app2')


@php

$usertypes = DB::table('usertype')->where('deleted',0)->get();

@endphp



@section('pagespecificscripts')
<style>
.afinance .card-body, .aregistrar .card-body, .aprincipal .card-body{
  height: 120px;
  /* margin: auto; */
}
.card-header {
  text-shadow: 1px 1px 3px gray;
}
.afacstaff .card-body, .astudentlist .card-body{
  height: 158px;
  overflow-y: scroll;
  font-size: 14px;
}
.aschoolcalendar .card-body{
  height: 95px;
}
.afacstaff .card-header {
  background-color: #fd7e14;
  color: #fff;
}
.astudentlist .card-header {
  background-color: #d81b60;
  color: #fff;
}

.btn-app {
  color: #fff;
  border: none;
  width: 90px;
  border-radius: 3px;
  background-color: transparent!important;
  font-size: 12px;
  height: 60px;
  margin: 0 0 10px 10px;
  min-width: 80px;
  padding: 15px 5px;
  position: relative;
  text-align: center;
  border: 0 !important;
} 
.btn-app span{
  color: #fff;
  font-size: 13px;
  font-weight: 600;
}
.btn-app i {
  -webkit-text-stroke-width: .3px;
  -webkit-text-stroke-color: #713333;
  transition: .3s;
  color: #ffc107;
 
}
.btn-app:hover i{
  font-size: 35px;
  transition: .3s;
}
.small-box-footer {
  color: #fff;
}
</style>

@endsection


@section('content')


<section class="content-header">
    <div class="container-fluid">
        <div class="row">
          <div class="col-12">
              <div class="input-group mb-3">
                <input type="text" id="search_usertype" class="form-control" placeholder="Search...">
                <div class="input-group-append">
                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
              </div>
          </div>
          <div class="col-12"> 
              <div class="row" id="usertypes">
              </div>
          </div>
        </div>
    </div>
</section>

@endsection

@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
<script>

    const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });
    const csrfToken = "{{ csrf_token() }}";

    function getusertypes(id) {

            var search = $('#search_usertype').val();
            
            return $.ajax({
                type: "GET",
                data:{
                    search: search,
                    id: id
                },
                url: '/schoolfolderv2/getUsertypes',
            });
    }

    function renderUsertypes(id) {

        getusertypes(id).then(function (data) {

            var renderHtml = data.length > 0 ? data.map(entry => {
                    return `  <div class="col-lg-3 col-xs-6">
                                <div class="small-box ${entry.isChecked ? 'bg-success' : 'bg-info'}" style="border-radius: 10px;">
                                    <div class="inner" style="padding: 10px;">
                                      <p style="font-size: 16px;color: #fff;">${entry.utype}</p>
                                      <div class="icheck-primary d-inline pt-2">
                                          <input type="checkbox" id="usertype_${entry.id}" class="usertype" data-id="${entry.id}" name="usertype[]" value="${entry.id}" ${entry.isChecked ? 'checked' : ''}>
                                          <label for="usertype_${entry.id}" style="color: #fff;">Folder Creator</label>
                                      </div>
                                    </div>
                                </div>
                              </div>`;

            }).join('') : ``;
            $('#usertypes').html(renderHtml);
        })
    }


    $(document).ready(function(){

        renderUsertypes();

    });

    $(document).on('input', '#search_usertype', function () {

        renderUsertypes();
    })

    $(document).on('change', '.usertype', function () {
        var id = $(this).data('id');
        var isChecked = $(this).is(':checked');
        console.log('id: ' + id + ', isChecked: ' + isChecked);

        var formData = new FormData();
        formData.append('id', id);
        formData.append('isChecked', isChecked);
        
        $.ajax({
                type: "POST",
                url: "/schoolfolderv2/setasCreator",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                },
                success: function (data) {
                      renderUsertypes();


                }
            });
    })



</script>

@endsection
