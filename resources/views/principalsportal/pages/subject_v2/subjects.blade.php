@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
          .select2-container .select2-selection--single {
                  height: 40px;
            }
    </style>
@endsection


@section('content')

    <div class="modal fade" id="create_subject_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary p-1">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Subject Description</label>
                            <input id="input_subjdesc" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="">Subject Code</label>
                            <input id="input_subjcode" class="form-control">
                          
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Subject Sort</label>
                            <input id="input_subjsort" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="icheck-primary d-inline pt-2">
                                <input type="checkbox" id="sf9" checked>
                                <label for="sf9">SF9
                                </label>
                            </div>
                        </div>
                        @if($acadprog != 'seniorhighschool')
                            <div class="col-md-6">
                                <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="isCon" >
                                    <label for="isCon">CONSOLIDATED SUBJECT
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row mt-3">
                        @if($acadprog != 'seniorhighschool')
                            <div class="col-md-4">
                                <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="isVisible" checked>
                                    <label for="isVisible">VISIBLE
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="isSP">
                                    <label for="isSP">SPECIALIZED
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- <div class="row mt-3">
                        @if($acadprog != 'seniorhighschool')
                            <div class="col-md-6">
                                <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="mapeh_con" class="setup">
                                    <label for="mapeh_con">MAPEH CONSOLIDATED
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="tle_con" class="setup">
                                    <label for="tle_con">TLE CONSOLIDATED
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row mt-3">
                        @if($acadprog != 'seniorhighschool')
                            <div class="col-md-6">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline ">
                                        <input type="checkbox" id="mapeh" class="setup">
                                        <label for="mapeh">MAPEH COMPONENT
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="tle" class="setup">
                                    <label for="tle">TLE COMPONENT
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div> --}}

                    @if($acadprog == 'seniorhighschool')
                        <div class="row form-group mt-3" id="input_type_holder">
                            <div class="col-md-12">
                                <label for="">Type</label>
                                <select name="" id="input_type" class="form-control">
                                    <option value="1">Core</option> 
                                    <option value="2">Specialized</option>
                                    <option value="3">Applied</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group" id="input_strand_holder">
                            <div class="col-md-12">
                                <label for="">Strand</label>
                                <select name="" id="input_strand" class="form-control select2" multiple="multiple">
                                   @foreach (DB::table('sh_strand')->where('deleted',0)->get() as $item)
                                        <option value="{{$item->id}}">{{$item->strandcode}}</option> 
                                   @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row form-group" id="input_semester_holder">
                            <div class="col-md-12">
                                <label for="">Semester</label>
                                <select name="" id="input_semester" class="form-control select2">
                                    <option value="1">1st Semester</option> 
                                    <option value="2">2nd Semester</option>
                                </select>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="modal-footer justify-content-between">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-sm btn-block" id="create_subject_buttton">Create</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-danger btn-sm btn-block" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_sort" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-primary p-1">
                </div>
                <div class="modal-body">
                   <div class="row">
                       <div class="col-md-12 form-group">
                           <label for="">Sort</label>
                           <input class="form-control" id="sort_value">
                       </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm btn-block" id="update_sort_button">Update</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btn-sm btn-block" data-dismiss="modal">Close</button>
                        </div>
                   </div>
                </div>
                
            </div>
        </div>
    </div>

    <section class="content-header">
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Subjects</li>
            </ol>
            </div>
        </div>
        </div>
    </section>
    <section>
        <div class="card main-card principalsubject">
            <div class="card-header bg-primary p-1">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if($acadprog == 'pre-school')
                            <h5> Pre-school School Subject</h5>
                        @elseif($acadprog == 'gradeschool')
                            <h5> Grade School Subject</h5>
                        @elseif($acadprog == 'juniorhighschool')
                            <h5> Junior High School Subject</h5>
                        @elseif($acadprog == 'seniorhighschool')
                            <h5> Senior High School Subject</h5>
                        @endif
                    </div>
                    <div class="col-md-6 text-right">
                        {{-- <button class="btn btn-secondary btn-sm" ><i class="fas fa-eye"></i> View SF9 Display</button> --}}
                        <button class="btn btn-primary btn-sm" id="create_subject"><i class="fas fa-plus"></i> Create Subject</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <table class="table table-sm" id="subject_list" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">Sort</th>
                                    <th width="56%">Description</th>
                                    <th width="20%">Code</th>
                                    <th width="7%" class="subj_header_c3"></th>
                                    <th width="7%" class="subj_header_c4"></th>
                                    <th width="7%" class="subj_header_c5"></th>
                                </tr>
                            </thead>
                            <tbody >
                                            
                            </tbody>
                        </table>
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
   
    <script>
        $(document).ready(function(){

            var acadprog = @json($acadprog);
            var all_subjects
            var selected_sub
            get_subjects()

            $('.select2').select2()

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            function get_subjects() {
                $.ajax({
                    type:'GET',
                    url:'/principal/subjects/view',
                    data:{
                        acadprog:acadprog,
                    },
                    success:function(data) {

                        all_subjects = data;
                        loaddattable()
                       
                    }
                })
            }

            if(acadprog == 'seniorhighschool'){
                $('.for_gs').attr('hidden','hidden')
            }

            $(document).on('click','.setup',function(){
                if($(this).prop('checked') == true){
                    isChecked = false;
                }else{
                    isChecked = true;
                }
                $('.setup').prop('checked',false)
                if(isChecked){
                    $(this).prop('checked',false)
                }else{
                    $(this).prop('checked',true)
                }
            })


            $(document).on('click','#create_subject_buttton',function(){

                var mapeh = 0;
                var insf9 = 0;
                var tle = 0
                var tlecon = 0;
                var mapehcon = 0
                var isCon = 0;
                var isVisible = 0
                var isSP = 0
                var strand = []

                if($('#mapeh').prop('checked') == true){
                    mapeh = 1
                }

                if($('#sf9').prop('checked') == true){
                    insf9 = 1
                }

                if($('#tle').prop('checked') == true){
                    tle = 1
                }

                if($('#tle_con').prop('checked') == true){
                    tlecon = 1
                }

                if($('#mapeh_con').prop('checked') == true){
                    mapehcon = 1
                }

                if($('#isCon').prop('checked') == true){
                    isCon = 1
                }

                if($('#isVisible').prop('checked') == true){
                    isVisible = 1
                }

                if($('#isSP').prop('checked') == true){
                    isSP = 1
                }
                
                $.ajax({
                    type:'GET',
                    url:'/principal/subjects/create',
                    data:{
                        acadprog:acadprog,
                        mapeh:mapeh,
                        insf9:insf9,
                        tle:tle,
                        tlecon:tlecon,
                        mapehcon:mapehcon,
                        isCon:isCon,
                        isSP:isSP,
                        isVisible:isVisible,
                        subjsort:$('#input_subjsort').val(),
                        subjdesc:$('#input_subjdesc').val(),
                        subjcode:$('#input_subjcode').val(),
                        semid: $('#input_semester').val(),
                        type: $('#input_type').val(),
                        strand: $('#input_strand').val(),
                    },
                    success:function(data) {

                        if(data[0].status == 1){

                            Toast.fire({
                                type: 'success',
                                title: 'Created Successfully!'
                            })

                            all_subjects.push({
                                id: data[0].id,
                                inMAPEH:mapeh,
                                inSF9: insf9,
                                inTLE: tle,
                                semid: $('#input_semester').val(),
                                type: $('#input_type').val(),
                                strand: $('#input_strand').val(),
                                subj_sortid: $('#input_subjsort').val(),
                                subjcode: $('#input_subjcode').val(),
                                subjdesc: $('#input_subjdesc').val()
                            })

                            $('#input_subjsort').val("")
                            $('#input_subjcode').val("")
                            $('#input_subjdesc').val("")
                            $('#mapeh').removeAttr('checked')
                            $('#insf9').removeAttr('checked')
                            $('#tle').removeAttr('checked')

                            $('#create_subject_modal').modal('hide')
                            loaddattable()
                            
                        }else{

                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                          
                        }
                        
                       
                    },
                    error:function(){
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            })


            $(document).on('click','.update_sort',function(){
                $('#update_sort').modal();
                selected_sub = $(this).attr('data-id')
                var temp_data = all_subjects.filter(x=>x.id== selected_sub)
                $('#sort_value').val(temp_data[0].subj_sortid)
            })

            $(document).on('click','#update_sort_button',function(){
                $.ajax({
                    type:'GET',
                    url:'/principal/subjects/update/sort',
                    data:{
                        acadprog:acadprog,
                        sort_val:$('#sort_value').val(),
                        subj_id:selected_sub
                    },
                    success:function(data) {
                        if(data[0].status == 0){

                            Swal.fire({
                                type: 'info',
                                title: data[0].data,
                            });
                            
                        }else{

                            Swal.fire({
                                type: 'info',
                                title: data[0].data,
                            });
                          
                            $('.update_sort[data-id="'+selected_sub+'"]').text($('#sort_value').val())
                            var subj_index = all_subjects.findIndex(x => x.id == selected_sub)
                            all_subjects[subj_index].subj_sortid = $('#sort_value').val()
                           
                        }
                        
                       
                    }
                })
            })



            
            $(document).on('click','#create_subject',function(){
                $('#create_subject_modal').modal()

            })

           

            $(document).on('click','.view_subject',function(){

                window.open("/principal/subject?acadprog="+acadprog+"&subjid="+$(this).attr('data-id'));
                
            })


            function loaddattable() {

                

                if(acadprog == 'seniorhighschool'){
                    $('.subj_header_c5').addClass('text-center')

                    $("#subject_list").DataTable({
                        destroy: true,
                        data:all_subjects,
                        "order": [
                            [ 4, "asc" ],
                            [ 3, "asc" ]
                        ],
                        "columns": [
                                    { "data": "subj_sortid" },
                                    { "data": "subjdesc" },
                                    { "data": "subjcode" },
                                    { "data": "type" },
                                    { "data": "semid" },
                                    { "data": "inSF9" },
                            ],
                        columnDefs: [
                            {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        if(rowData.subj_sortid != null){
                                            $(td)[0].innerHTML = '<a href="#" class="update_sort" data-id="'+rowData.id+'">'+rowData.subj_sortid+'</a>'
                                        }else{
                                            $(td)[0].innerHTML = '<a href="#" class="update_sort" data-id="'+rowData.id+'"><i class="fas fa-sort-alpha-down"></i></a>'
                                        }
                                    }
                            },
                            {
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td)[0].innerHTML = '<a href="#" class="view_subject" data-id="'+rowData.id+'">'+rowData.subjdesc+'</a>'
                                    }
                            },
                            {
                                    'targets': 4,
                                    'title':'Semester',
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        if(rowData.semid == 1){
                                            $(td).text('1st')
                                        }
                                        else{
                                            $(td).text('2nd')
                                        }
                                    }
                            },
                            {
                                    'targets': 3,
                                    'orderable': true, 
                                    'title':'Type',
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        if(rowData.type == 1){
                                            $(td).text('Core')
                                        }else if(rowData.type == 2){
                                            $(td).text('Specialized')
                                        }
                                        else if(rowData.type == 3){
                                            $(td).text('Applied')
                                        }
                                    }
                            },
                            {
                                    'targets': 5,
                                    'orderable': true, 
                                    'title':'SF9',
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td).addClass('text-center')
                                        if(rowData.inSF9 == 1){
                                            $(td).addClass('bg-success')
                                            $(td)[0].innerHTML = '<i class="fas fa-check"></i>'
                                        }
                                        else{
                                            $(td).addClass('bg-danger')
                                            $(td)[0].innerHTML = '<i class="fas fa-times"></i>'
                                        }
                                    }
                            },
                        ]
                    });

                }else{
                    $('.subj_header_c3').addClass('text-center')
                    $('.subj_header_c4').addClass('text-center')
                    $('.subj_header_c5').addClass('text-center')

                    $("#subject_list").DataTable({
                        destroy: true,
                        data:all_subjects,
                        "order": [
                            [ 3, "desc" ],
                            [ 0, "asc" ],
                            [ 4, "asc" ]
                            ],
                        "columns": [
                                    { "data": "subj_sortid" },
                                    { "data": "subjdesc" },
                                    { "data": "subjcode" },
                                    { "data": "inSF9" },
                                    { "data": "inMAPEH" },
                                    { "data": "inTLE" },
                            ],
                        columnDefs: [
                            {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        if(rowData.subj_sortid != null){
                                            $(td)[0].innerHTML = '<a href="#" class="update_sort" data-id="'+rowData.id+'">'+rowData.subj_sortid+'</a>'
                                        }else{
                                            $(td)[0].innerHTML = '<a href="#" class="update_sort" data-id="'+rowData.id+'"><i class="fas fa-sort-alpha-down"></i></a>'
                                        }
                                    }
                            },
                            {
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td)[0].innerHTML = '<a href="#" class="view_subject" data-id="'+rowData.id+'">'+rowData.subjdesc+'</a>'
                                    }
                            },
                            {
                                    'targets': 4,
                                    'title':'MAPEH',
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        if(acadprog == 'seniorhighschool'){
                                            $(td).attr('hidden','hidden')
                                        }
                                        else{
                                            $(td).addClass('text-center')
                                            if(rowData.inMAPEH == 1){
                                                $(td).addClass('bg-success')
                                                $(td)[0].innerHTML = '<i class="fas fa-check"></i>'
                                            }
                                            else{
                                                $(td).addClass('bg-danger')
                                                $(td)[0].innerHTML = '<i class="fas fa-times"></i>'
                                            }
                                        }
                                    }
                            },
                            {
                                    'targets': 3,
                                    'orderable': true, 
                                    'title':'SF9',
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td).addClass('text-center')
                                        if(rowData.inSF9 == 1){
                                            $(td).addClass('bg-success')
                                            $(td)[0].innerHTML = '<i class="fas fa-check"></i>'
                                        }
                                        else{
                                            $(td).addClass('bg-danger')
                                            $(td)[0].innerHTML = '<i class="fas fa-times"></i>'
                                        }
                                    }
                            },
                            {
                                    'targets': 5,
                                    'orderable': true, 
                                    'title':'TLE',
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                        if(acadprog == 'seniorhighschool'){
                                            $(td).attr('hidden','hidden')
                                        }
                                        else{
                                            $(td).addClass('text-center')
                                            if(rowData.inTLE == 1){
                                                $(td).addClass('bg-success')
                                                $(td)[0].innerHTML = '<i class="fas fa-check"></i>'
                                            }
                                            else{
                                                $(td).addClass('bg-danger')
                                                $(td)[0].innerHTML = '<i class="fas fa-times"></i>'
                                            }
                                        }
                                        
                                    }
                            },
                        ]
                    });

                }

                   
            }
              


                    

                  
               
        })
    </script>
    

@endsection

