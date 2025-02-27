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

    <div class="modal fade" id="update_subject_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog ">
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
                        <div class="col-md-12 form-group">
                            <label for="">Subject Code</label>
                            <input id="input_subjcode" class="form-control">
                        </div>
                    </div>
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
                        <button class="btn btn-primary btn-sm btn-block" id="update_subject_button"><i class="fas fa-edit"></i> Edit Subject</button>
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

    <div class="modal fade" id="subject_component_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-primary p-1">
                </div>
                <div class="modal-body">
                   <div class="row">
                       <div class="col-md-12 form-group">
                           <label for="">Subject</label>
                           <select class="form-control select2" id="input_component_subject">
                               <option value="">Select Subject</option>
                           </select>
                       </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm btn-block" id="add_component_button">Add</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btn-sm btn-block" data-dismiss="modal">Close</button>
                        </div>
                   </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_grade_setup_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary p-1">
                </div>
                <div class="modal-body">
                   <div class="row">
                       <div class="col-md-12 form-group">
                           <label for="">Grad Level</label>
                           <input class="form-control" id="input_gradelevel" readonly>
                       </div>
                   </div>
                   <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Written Works</label>
                            <input id="input_ww" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Performance Task</label>
                            <input id="input_pt" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Quarterly Assesment</label>
                            <input id="input_qa" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline ">
                                    <input type="checkbox" id="apply_to_all">
                                    <label for="apply_to_all">APPLY TO ALL SUBJECTS
                                    </label>
                                </div>
                            </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm btn-block" id="update_grade_setup_button">Update</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btn-sm btn-block" data-dismiss="modal">Close</button>
                        </div>
                </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_percentage_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-primary p-1">
                </div>
                <div class="modal-body">
                   <div class="row">
                       <div class="col-md-12 form-group">
                           <label for="">Percentage</label>
                           <input class="form-control" id="input_percentage">
                       </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm btn-block" id="update_percentaget_button">Update</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btn-sm btn-block" data-dismiss="modal">Close</button>
                        </div>
                   </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_strand_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-primary p-1">
                </div>
                <div class="modal-body">
                   <div class="row">
                       <div class="col-md-12 form-group">
                           <label for="">Strand</label>
                           <select name="input_strand" id="input_strand" class="form-control select2">
                                <option value="">Select Strand</option>
                                @php
                                    $strands = DB::table('sh_strand')->where('deleted',0)->select('id','strandname','strandcode')->get();
                                @endphp
                                @foreach($strands as $item)
                                    <option value="{{$item->id}}">{{$item->strandname}}</option>

                                @endforeach
                           </select>
                           
                       </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm btn-block" id="add_strand_button">Add</button>
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
                <li class="breadcrumb-item active">
                    @php
                        $link = '';
                        if($acadprog == 'pre-school'){
                            $link = '/principal/subjects?acadprog=pre-school';
                        }elseif($acadprog == 'gradeschool'){
                            $link = '/principal/subjects?acadprog=gradeschool';
                        }
                        elseif($acadprog == 'juniorhighschool'){
                            $link = '/principal/subjects?acadprog=juniorhighschool';
                        }
                        elseif($acadprog == 'seniorhighschool'){
                            $link = '/principal/subjects?acadprog=seniorhighschool';
                        }
                    @endphp
                    <a href="{{$link}}">Subjects</a>
                </li>
                <li class="breadcrumb-item active subject_description" ></li>
              
            </ol>
            </div>
        </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-1 bg-primary"></div>
                    <div class="card-body">
                        <div class="row text-right">
                            <div class="col-md-12">
                                <button class="btn btn-sm btn-primary" id="update_subject"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-sm btn-danger" id="remove_subject"><i class="fas fa-trash-alt"></i> Delete</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-5">
                                <strong>Description</strong>
                                <p class="text-muted subject_description">--</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Code</strong>
                                <p class="text-muted" id="subject_code">--</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Sort</strong>
                                <p class="text-muted" id="subject_sort">--</p>
                            </div>
                            <div class="col-md-2" id="per_holder">
                                <strong>Percentage</strong>
                                <p class="text-muted" id="subject_percentage">--</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Academic Program</strong>
                                <p class="text-muted" id="subject_academic">--</p>
                            </div>
                            <div class="col-md-2 pt-3">
                                <div class="icheck-primary d-inline pt-2">
                                    <input type="checkbox" id="sf9">
                                    <label for="sf9">SF9
                                    </label>
                                </div>
                            </div>
                          
                            @if($acadprog == 'seniorhighschool')
                                <div class="col-md-2">
                                    <strong>Type</strong>
                                    <p class="text-muted" id="subject_type">--</p>
                                </div>
                                <div class="col-md-2">
                                    <strong>Semester</strong>
                                    <p class="text-muted" id="subject_semester">--</p>
                                </div>
                            @else
                                <div class="col-md-4 mt-3">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="isCon" >
                                        <label for="isCon">CONSOLIDATED SUBJECT
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="isVisible">
                                        <label for="isVisible">VISIBLE
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="isSP">
                                        <label for="isSP">SPECIALIZED
                                        </label>
                                    </div>
                                </div>
                            @endif
                           
                        </div>
                        {{-- <div class="row">
                            @if($acadprog != 'seniorhighschool')
                                <div class="col-md-3">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline ">
                                            <input type="checkbox" id="mapeh" class="setup">
                                            <label for="mapeh">MAPEH COMPONENT
                                            </label>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="tle" class="setup">
                                        <label for="tle">TLE COMPONENT
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="mapeh_con" class="setup">
                                        <label for="mapeh_con">MAPEH CONSOLIDATED
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="icheck-primary d-inline pt-2">
                                        <input type="checkbox" id="tle_con" class="setup">
                                        <label for="tle_con">TLE CONSOLIDATED
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="grade_setup_card" hidden>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-1 bg-secondary"></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <strong>Grade Setup</strong>
                            </div>
                            <div class="col-md-2 text-right">
                                <select name="sy_id" id="sy_id" class="form-control form-control-sm">
                                    @foreach (DB::table('sy')->select('sydesc','id','isactive')->get() as $item)
                                            <option value="{{$item->id}}" {{$item->isactive == 1?'selected':''}}>{{$item->sydesc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                               <table class="table table-sm" id="grade_setup" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="30%">Grade Level</th>
                                            <th width="5%" class="text-center">Q1</th>
                                            <th width="5%" class="text-center">Q2</th>
                                            <th width="5%" class="text-center">Q3</th>
                                            <th width="5%" class="text-center">Q4</th>
                                            <th width="10%" class="text-center">WW</th>
                                            <th width="10%" class="text-center">PT</th>
                                            <th width="10%" class="text-center">QA</th>
                                            <th width="20%" class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                               </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="strand_list_holder" hidden>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success p-1"></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 pt-2"><strong>Strand List</strong></div>
                            <div class="col-md-4 text-right"><button class="btn btn-primary btn-sm" id="add_strand"><i class="fas fa-plus"></i> Add Strand</button></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="subject_strand_list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="subject_component_holder" hidden>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success p-1"></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 pt-2"><strong>Subject Components</strong></div>
                            <div class="col-md-4 text-right"><button class="btn btn-primary btn-sm" id="add_subject_component"><i class="fas fa-plus"></i> Add Subject</button></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="80%">Subject</th>
                                            <th width="20%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="subject_component_list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
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
            var subjid = @json($subjid);
            var all_subjects
            var selected_sub

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            $('.select2').select2()

            if(acadprog == 'pre-school'){
                $('#subject_academic').text('Pre-School')
                $('#sidenav_subject_ps').addClass('active')
            } else if(acadprog == 'gradeschool'){
                $('#subject_academic').text('Grade School')
                $('#sidenav_subject_gs').addClass('active')
            }else if(acadprog == 'juniorhighschool'){
                $('#subject_academic').text('Junior High School')
                $('#sidenav_subject_hs').addClass('active')
            }else if(acadprog == 'seniorhighschool'){
                $('#subject_academic').text('Senior High School')
                 $('#sidenav_subject_sh').addClass('active')
            }

            get_subjects()

            function get_subjects() {
                $.ajax({
                    type:'GET',
                    url:'/principal/subjects/view',
                    data:{
                        acadprog:acadprog,
                        subjid:subjid
                    },
                    success:function(data) {
                        all_subjects = data
                        if(all_subjects.length != 0){
                            load_subject_info(all_subjects[0])
                            get_grade_setup()
                        }

                        if(acadprog == 'seniorhighschool'){
                            $('#subject_component_holder').remove()
                            $('#strand_list_holder').removeAttr('hidden')
                        }else{
                            $('#strand_list_holder').remove()
                            if(all_subjects[0].isCon == 1){
                                $('#subject_component_holder').removeAttr('hidden')
                            }
                        }
                    }
                })
            }

           
            function load_subject_info(subject){

                $('.subject_description').text(subject.subjdesc)
                $('#subject_code').text(subject.subjcode)

                if(acadprog == 'seniorhighschool'){
                    if(subject.type == 1){
                        $('#subject_type').text('CORE')
                    }else if(subject.type == 2){
                        $('#subject_type').text('SPECIALIZED')
                    }else{
                        $('#subject_type').text('APPLIED')
                    }
                    
                    if(subject.semid == 1){
                        $('#subject_semester').text('1st Semester')
                    }else if(subject.semid == 2){
                        $('#subject_semester').text('2nd Semester')
                    }
                }

                if(subject.subj_sortid != null){
                    $('#subject_sort')[0].innerHTML = '<a href="#" class="update_sort" data-id="'+subject.id+'">'+subject.subj_sortid+'</a>'
                }else{
                    $('#subject_sort')[0].innerHTML = '<a href="#" class="update_sort" data-id="'+subject.id+'">Not Assigned</a>'
                }

                // if(subject.inMAPEH == 1){
                //     $('#mapeh').attr('checked','checked')
                // }

                // if(subject.isTLECon == 1){
                //     $('#tle_con').attr('checked','checked')
                // }


                // if(subject.isMAPEHCon == 1){
                //     $('#mapeh_con').attr('checked','checked')
                // }

                if(subject.isCon == 1){
                    $('#isCon').attr('checked','checked')
                }


                if(subject.isVisible == 1){
                    $('#isVisible').attr('checked','checked')
                }

                if(subject.isSP == 1){
                    $('#isSP').attr('checked','checked')
                }



                if(subject.inSF9 == 1){
                    $('#grade_setup_card').removeAttr('hidden')
                    $('#sf9').attr('checked','checked')
                }else{
                    $('#grade_setup_card').attr('hidden','hidden')
                }

                
               
                // if(subject.inTLE == 1){
                //     $('#per_holder').removeAttr('hidden')
                //     $('#tle').attr('checked','checked')

                if(subject.subj_per == 0){
                    $('#subject_percentage')[0].innerHTML = '<a href="#" class="subj_per" data-id="'+subject.id+'">N/A</a>'
                }
                else{
                    $('#subject_percentage')[0].innerHTML = '<a href="#" class="subj_per" data-id="'+subject.id+'">'+subject.subj_per+' %</a>'
                }
                   
                // }else{
                //     $('#per_holder').attr('hidden','hidden')
                // }
               
               
            }


            $(document).on('click','.update_sort',function(){
                $('#update_sort').modal();
                selected_sub = $(this).attr('data-id')
                var temp_data = all_subjects.filter(x=>x.id== selected_sub)
                $('#sort_value').val(temp_data[0].subj_sortid)
            })



            $(document).on('click','.subj_per',function(){
                $('#update_percentage_modal').modal();
                selected_sub = $(this).attr('data-id')
                var temp_data = all_subjects.filter(x=>x.id== selected_sub)
                $('#input_percentage').val(temp_data[0].subj_per)
             
            })


            

            $(document).on('click','#update_percentaget_button',function(){
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/update/percentage',
                    data:{
                        acadprog:acadprog,
                        percentage:$('#input_percentage').val(),
                        subjid:selected_sub
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })
                            $('.subj_per[data-id="'+selected_sub+'"]').text($('#input_percentage').val() + '%')
                            var subj_index = all_subjects.findIndex(x => x.id == selected_sub)
                            all_subjects[subj_index].subj_sortid = $('#input_percentage').val()
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
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

                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                            
                        }else{

                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })
                          
                            $('.update_sort[data-id="'+selected_sub+'"]').text($('#sort_value').val())
                            var subj_index = all_subjects.findIndex(x => x.id == selected_sub)
                            all_subjects[subj_index].subj_sortid = $('#sort_value').val()
                           
                        }
                        
                       
                    }
                })
            })

            $(document).on('change','#sy_id',function(){
                get_grade_setup()
            })

            

            //update subject
            $(document).on('click','#update_subject',function(){
                $('#update_subject_modal').modal();
                $('#input_subjdesc').val(all_subjects[0].subjdesc)
                $('#input_subjcode').val(all_subjects[0].subjcode)
                $('#input_type').val(all_subjects[0].type).change()
                $('#input_semester').val(all_subjects[0].semid).change()
            })

            $(document).on('click','#update_subject_button',function(){
                
                var temp_type = $('#input_type').val()
                
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/update',
                    data:{
                        acadprog:acadprog,
                        subjid:all_subjects[0].id,
                        subjcode:$('#input_subjcode').val(),
                        subjdesc:$('#input_subjdesc').val(),
                        semid: $('#input_semester').val(),
                        type: $('#input_type').val(),
                    },
                    success:function(data) {
                        if(data[0].status == 0){

                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })

                            return false;
                            
                        }else{

                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })
                          
                        }
                        all_subjects[0].subjdesc = $('#input_subjdesc').val()
                        all_subjects[0].subjcode = $('#input_subjcode').val()
                        all_subjects[0].type = $('#input_type').val()
                        all_subjects[0].semid = $('#input_semester').val()

                        if(acadprog == 'seniorhighschool'){
                            if(temp_type == 1){
                                $('#subject_type').text('CORE')
                            }else if(temp_type == 2){
                                $('#subject_type').text('SPECIALIZED')
                            }else{
                                $('#subject_type').text('APPLIED')
                            }
                            
                            if($('#input_semester').val() == 1){
                                $('#subject_semester').text('1st Semester')
                            }else if($('#input_semester').val() == 2){
                                $('#subject_semester').text('2nd Semester')
                            }
                        }

                        load_subject_info(all_subjects[0])
                        $('#update_subject_modal').modal('hide');
                    }
                })
            })


            // $(document).on('click','#mapeh',function(){
            //     var mapeh = 0;
            //     if($(this).prop('checked') == true){
            //         mapeh = 1
            //     }
            //     $.ajax({
            //         type:'GET',
            //         url:'/principal/subject/update/mapeh',
            //         data:{
            //             acadprog:acadprog,
            //             mapeh:mapeh,
            //             subjid:all_subjects[0].id,
            //         },
            //         success:function(data) {
            //             if(data[0].status == 1){
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: 'Updated successfully!'
            //                 })
            //                 var subj_index = all_subjects.findIndex(x => x.id == all_subjects[0].id)
            //                 all_subjects[subj_index].inMAPEH = mapeh
            //                 load_subject_info(all_subjects[0])
            //             }else{
            //                 Toast.fire({
            //                     type: 'error',
            //                     title: 'Something went wrong!'
            //                 })
            //             }
            //         }
            //     })
            // })

            $(document).on('click','#sf9',function(){
                var sf9 = 0;
                if($(this).prop('checked') == true){
                    sf9 = 1
                }
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/update/sf9',
                    data:{
                        acadprog:acadprog,
                        sf9:sf9,
                        subjid:all_subjects[0].id,
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })
                            var subj_index = all_subjects.findIndex(x => x.id == all_subjects[0].id)
                            all_subjects[subj_index].inSF9 = sf9
                            load_subject_info(all_subjects[0])
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            })

            $(document).on('click','#isVisible',function(){
                var visible = 0;
                if($(this).prop('checked') == true){
                    visible = 1
                }
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/update/visible',
                    data:{
                        acadprog:acadprog,
                        visible:visible,
                        subjid:all_subjects[0].id,
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })
                            var subj_index = all_subjects.findIndex(x => x.id == all_subjects[0].id)
                            all_subjects[subj_index].isVisible = visible
                            load_subject_info(all_subjects[0])
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            })

            $(document).on('click','#isSP',function(){
                var isSP = 0;
                if($(this).prop('checked') == true){
                    isSP = 1
                }
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/update/specialized',
                    data:{
                        acadprog:acadprog,
                        isSP:isSP,
                        subjid:all_subjects[0].id,
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })
                            var subj_index = all_subjects.findIndex(x => x.id == all_subjects[0].id)
                            all_subjects[subj_index].isSP = isSP
                            load_subject_info(all_subjects[0])
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            })

            $(document).on('click','#isCon',function(){
                var consolidated = 0;
                if($(this).prop('checked') == true){
                    consolidated = 1
                }
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/update/consolidated',
                    data:{
                        acadprog:acadprog,
                        consolidated:consolidated,
                        subjid:all_subjects[0].id,
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })
                            var subj_index = all_subjects.findIndex(x => x.id == all_subjects[0].id)
                            all_subjects[subj_index].isCon = consolidated
                            
                            load_subject_info(all_subjects[0])
                            if(consolidated == 1){
                                $('#subject_component_holder').removeAttr('hidden')
                            }else{
                                $('#subject_component_holder').attr('hidden','hidden')
                            }
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            })

            // $(document).on('click','#tle',function(){
            //     var tle = 0;
            //     if($(this).prop('checked') == true){
            //         tle = 1
            //     }
            //     $.ajax({
            //         type:'GET',
            //         url:'/principal/subject/update/tle',
            //         data:{
            //             acadprog:acadprog,
            //             tle:tle,
            //             subjid:all_subjects[0].id,
            //         },
            //         success:function(data) {
            //             if(data[0].status == 1){
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: 'Updated successfully!'
            //                 })
            //                 var subj_index = all_subjects.findIndex(x => x.id == all_subjects[0].id)
            //                 all_subjects[subj_index].inTLE = tle
            //                 load_subject_info(all_subjects[0])
            //             }else{
            //                 Toast.fire({
            //                     type: 'error',
            //                     title: 'Something went wrong!'
            //                 })
            //             }
            //         }
            //     })
            // })


            // $(document).on('click','#tle_con',function(){
            //     var tlecon = 0;
            //     if($(this).prop('checked') == true){
            //         tlecon = 1
            //     }
            //     $.ajax({
            //         type:'GET',
            //         url:'/principal/subject/update/tlecon',
            //         data:{
            //             acadprog:acadprog,
            //             tlecon:tlecon,
            //             subjid:all_subjects[0].id,
            //         },
            //         success:function(data) {
            //             if(data[0].status == 1){
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: 'Updated successfully!'
            //                 })
            //                 var subj_index = all_subjects.findIndex(x => x.id == all_subjects[0].id)
            //                 all_subjects[subj_index].isTLECon = tlecon
            //                 load_subject_info(all_subjects[0])
            //             }else{
            //                 Toast.fire({
            //                     type: 'error',
            //                     title: 'Something went wrong!'
            //                 })
            //             }
            //         }
            //     })
            // })

            // $(document).on('click','#mapeh_con',function(){
            //     var mapehcon = 0;
            //     if($(this).prop('checked') == true){
            //         mapehcon = 1
            //     }
            //     $.ajax({
            //         type:'GET',
            //         url:'/principal/subject/update/mapehcon',
            //         data:{
            //             acadprog:acadprog,
            //             mapehcon:mapehcon,
            //             subjid:all_subjects[0].id,
            //         },
            //         success:function(data) {
            //             if(data[0].status == 1){
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: 'Updated successfully!'
            //                 })
            //                 var subj_index = all_subjects.findIndex(x => x.id == all_subjects[0].id)
            //                 all_subjects[subj_index].isMAPEHCon = mapehcon
            //                 load_subject_info(all_subjects[0])
            //             }else{
            //                 Toast.fire({
            //                     type: 'error',
            //                     title: 'Something went wrong!'
            //                 })
            //             }
            //         }
            //     })
            // })


            // $(document).on('click','.setup',function(){
            //     if($(this).prop('checked') == true){
            //         isChecked = false;
            //     }else{
            //         isChecked = true;
            //     }
            //     $('.setup').prop('checked',false)
            //     if(isChecked){
            //         $(this).prop('checked',false)
            //     }else{
            //         $(this).prop('checked',true)
            //     }
            // })



            //grade setup

            var selected_gradesetup = []
            var gradesetup_levelid

            $(document).on('click','.update_grade_setup',function(){
                var gsid = $(this).attr('data-id')
                selected_gradesetup = []
                $('#update_grade_setup_modal').modal()
                gradesetup_levelid = $(this).attr('data-levelid')
                var temp_grade_setup = grade_setup.filter(x=>x.id == $(this).attr('data-id'))

                if(temp_grade_setup.length != 0){
                    selected_gradesetup = temp_grade_setup
                    $('#input_gradelevel').val(temp_grade_setup[0].levelname)
                    $('#input_ww').val(temp_grade_setup[0].writtenworks)
                    $('#input_pt').val(temp_grade_setup[0].performancetask)
                    $('#input_qa').val(temp_grade_setup[0].qassesment)

                }else {
                    var gradesetup_index = grade_setup.findIndex(x => x.levelid == gradesetup_levelid)
                    $('#input_gradelevel').val(grade_setup[gradesetup_index].levelname)
                    $('#input_ww').val("")
                    $('#input_pt').val("")
                    $('#input_qa').val("")
                }
                
            })

            $(document).on('click','.delete_grade_setup',function(){
                var gsid = $(this).attr('data-id')
                $.ajax({
                        type:'GET',
                        url:'/principal/gradesetup/delete',
                        data:{
                            gsid:gsid,
                        },
                        success:function(data) {
                            if(data[0].status == 1){
                                Toast.fire({
                                    type: 'success',
                                    title: 'Deleted successfully!'
                                });
                                var gradesetup_index = grade_setup.findIndex(x => x.id == gsid)
                                grade_setup[gradesetup_index].id = null
                                grade_setup[gradesetup_index].writtenworks = null
                                grade_setup[gradesetup_index].performancetask = null
                                grade_setup[gradesetup_index].qassesment = null
                                grade_setup[gradesetup_index].first = null
                                grade_setup[gradesetup_index].second = null
                                grade_setup[gradesetup_index].third = null
                                grade_setup[gradesetup_index].fourth = null
                                load_gradesetup_datatable()

                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                })
                            }
                        }
                    })
             

            })
            

            $(document).on('click','#update_grade_setup_button',function(){

                var subjid = all_subjects[0].id
                var gsid = null
                if(selected_gradesetup.length != 0){
                    gsid = selected_gradesetup[0].id
                }

                if($('#apply_to_all').prop('checked') == true){

                    var proccess_count = 0;
                    $.each(grade_setup,function(a,b){

                        var gsid = b.id
                        var levelid = b.levelid

                        $.ajax({
                            type:'GET',
                            url:'/principal/gradesetup/update',
                            data:{
                                gsid:gsid,
                                ww:$('#input_ww').val(),
                                pt:$('#input_pt').val(),
                                qa:$('#input_qa').val(),
                                syid:$('#sy_id').val(),
                                levelid:levelid,
                                subjid:subjid
                            },
                            success:function(data) {
                                proccess_count += 1;
                                if(data[0].status == 1){
                                    if(gsid != null){
                                        var gradesetup_index = grade_setup.findIndex(x => x.id == gsid)
                                        grade_setup[gradesetup_index].writtenworks = $('#input_ww').val()
                                        grade_setup[gradesetup_index].performancetask = $('#input_pt').val()
                                        grade_setup[gradesetup_index].qassesment = $('#input_qa').val()
                                        grade_setup[gradesetup_index].first = 1
                                        grade_setup[gradesetup_index].second = 1
                                        grade_setup[gradesetup_index].third = 1
                                        grade_setup[gradesetup_index].fourth = 1
                                        load_gradesetup_datatable()
                                    }else{
                                        var gradesetup_index = grade_setup.findIndex(x => x.levelid == levelid)
                                        grade_setup[gradesetup_index].writtenworks = $('#input_ww').val()
                                        grade_setup[gradesetup_index].performancetask = $('#input_pt').val()
                                        grade_setup[gradesetup_index].qassesment = $('#input_qa').val()
                                        grade_setup[gradesetup_index].id = data[0].id
                                        grade_setup[gradesetup_index].first = 1
                                        grade_setup[gradesetup_index].second = 1
                                        grade_setup[gradesetup_index].third = 1
                                        grade_setup[gradesetup_index].fourth = 1
                                        load_gradesetup_datatable()
                                    }
                                    if(proccess_count == grade_setup.length){
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Updated successfully!'
                                        })
                                    }
                                }else{
                                    if(proccess_count == grade_setup.length){
                                        Toast.fire({
                                            type: 'error',
                                            title: 'Something went wrong!'
                                        })
                                    }
                                }
                            }
                        })
                    })

                    

                }
                else{

                    var levelid = gradesetup_levelid

                    $.ajax({
                        type:'GET',
                        url:'/principal/gradesetup/update',
                        data:{
                            gsid:gsid,
                            ww:$('#input_ww').val(),
                            pt:$('#input_pt').val(),
                            qa:$('#input_qa').val(),
                            syid:$('#sy_id').val(),
                            levelid:levelid,
                            subjid:subjid
                        },
                        success:function(data) {
                            if(data[0].status == 1){
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated successfully!'
                                })

                                if(gsid != null){
                                    var gradesetup_index = grade_setup.findIndex(x => x.id == gsid)
                                    grade_setup[gradesetup_index].writtenworks = $('#input_ww').val()
                                    grade_setup[gradesetup_index].performancetask = $('#input_pt').val()
                                    grade_setup[gradesetup_index].qassesment = $('#input_qa').val()
                                    grade_setup[gradesetup_index].first = 1
                                    grade_setup[gradesetup_index].second = 1
                                    grade_setup[gradesetup_index].third = 1
                                    grade_setup[gradesetup_index].fourth = 1
                                    load_gradesetup_datatable()
                                }else{
                                    var gradesetup_index = grade_setup.findIndex(x => x.levelid == levelid)
                                    grade_setup[gradesetup_index].writtenworks = $('#input_ww').val()
                                    grade_setup[gradesetup_index].performancetask = $('#input_pt').val()
                                    grade_setup[gradesetup_index].qassesment = $('#input_qa').val()
                                    grade_setup[gradesetup_index].id = data[0].id
                                    grade_setup[gradesetup_index].first = 1
                                    grade_setup[gradesetup_index].second = 1
                                    grade_setup[gradesetup_index].third = 1
                                    grade_setup[gradesetup_index].fourth = 1
                                    load_gradesetup_datatable()
                                }
                                

                            }else{
                                Toast.fire({
                                    type: 'success',
                                    title: 'Something went wrong!'
                                })
                            }
                        }
                    })

                }

               
            })

          
            var grade_setup = []
            
            function get_grade_setup() {
                $.ajax({
                    type:'GET',
                    url:'/principal/get/gradesetup',
                    data:{
                        acadprog:acadprog,
                        subjid:subjid,
                        syid:$('#sy_id').val()
                    },
                    success:function(data) {
                        grade_setup = data
                        load_gradesetup_datatable()
                        
                    }
                })
            }


            var gradessetupid = null;
            var quarter = null;
            var status = null;
            $(document).on('click','.quarter_setup',function(){
                gradessetupid = $(this).attr('data-id');
                quarter = $(this).attr('data-q');
                status = $(this).attr('data-stat');
                update_gradessetup_quarter()
            })

            function update_gradessetup_quarter() {
                $.ajax({
                    type:'GET',
                    url:'/principal/gradestatus/update/quarter',
                    data:{
                        setupid:gradessetupid,
                        quarter:quarter,
                        status:status,
                    },
                    success:function(data) {
                        if(data[0].status == 1){

                            var temp_grade_setup = grade_setup.findIndex(x=>x.id == gradessetupid)
                            if(quarter == 1){
                                grade_setup[temp_grade_setup].first = status
                            }else if(quarter == 2){
                                grade_setup[temp_grade_setup].second = status
                            }else if(quarter == 3){
                                grade_setup[temp_grade_setup].third = status
                            }else if(quarter == 4){
                                grade_setup[temp_grade_setup].fourth = status
                            }

                            load_gradesetup_datatable()

                            Toast.fire({
                                type: 'success',
                                title: 'Updated successfully!'
                            })
                        }else{
                            Toast.fire({
                                type: 'success',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            }

            function load_gradesetup_datatable(){
               
                $("#grade_setup").DataTable({
                        destroy: true,
                        data:grade_setup,
                        "columns": [
                                    { "data": "levelname" },
                                    { "data": "first" },
                                    { "data": "second" },
                                    { "data": "third" },
                                    { "data": "fourth" },
                                    { "data": "writtenworks" },
                                    { "data": "performancetask" },
                                    { "data": "qassesment" },
                                    { "data": null },
                            ],
                        columnDefs: [
                               {
                                    'targets': 0,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 1,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            if(rowData.first == 1){
                                                $(td)[0].innerHTML = '<a href="#" class="quarter_setup" data-q="1" data-id="'+rowData.id+'"  data-stat="0"><i class="far fa-check-circle text-success" ></i></a>'
                                            }else if(rowData.first == 0){
                                                $(td)[0].innerHTML = '<a href="#" class="quarter_setup" data-q="1" data-id="'+rowData.id+'"  data-stat="1"><i class="far fa-times-circle text-danger" ></i></a>'
                                            }
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 2,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            if(rowData.second == 1){
                                                $(td)[0].innerHTML = '<a href="#" class="quarter_setup" data-q="2" data-id="'+rowData.id+'"  data-stat="0"><i class="far fa-check-circle text-success" ></i></a>'
                                            }else if(rowData.second == 0){
                                                $(td)[0].innerHTML = '<a href="#" class="quarter_setup" data-q="2" data-id="'+rowData.id+'"  data-stat="1"><i class="far fa-times-circle text-danger" ></i></a>'
                                            }
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 3,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            if(rowData.third == 1){
                                                $(td)[0].innerHTML = '<a href="#" class="quarter_setup" data-q="3" data-id="'+rowData.id+'" data-stat="0"><i class="far fa-check-circle text-success" ></i></a>'
                                            }else if(rowData.third == 0){
                                                $(td)[0].innerHTML = '<a href="#" class="quarter_setup" data-q="3" data-id="'+rowData.id+'" data-stat="1"><i class="far fa-times-circle text-danger"  ></i></a>'
                                            }
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 4,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            if(rowData.fourth == 1){
                                                $(td)[0].innerHTML = '<a href="#" class="quarter_setup" data-q="4" data-id="'+rowData.id+'" data-stat="0"><i class="far fa-check-circle text-success"  ></i></a>'
                                            }else if(rowData.fourth == 0){
                                                $(td)[0].innerHTML = '<a href="#" class="quarter_setup" data-q="4" data-id="'+rowData.id+'"  data-stat="1"><i class="far fa-times-circle text-danger" ></i></a>'
                                            }
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 5,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            if(acadprog != 5){
                                                if(all_subjects[0].isCon == 1){
                                                    $(td).text('N/A')
                                                }
                                            }
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 6,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            if(acadprog != 5){
                                                if(all_subjects[0].isCon == 1){
                                                    $(td).text('N/A')
                                                }
                                            }
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 7,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                            if(acadprog != 5){
                                                if(all_subjects[0].isCon == 1){
                                                    $(td).text('N/A')
                                                }
                                            }
                                            $(td).addClass('text-center')
                                            $(td).addClass('align-middle')
                                    }
                                },
                                {
                                    'targets': 8,
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                       
                                        $(td).addClass('text-center')
                                        var delete_button = '<a href="#" class="ml-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>'

                                        if(rowData.id != null){
                                            delete_button = '<a href="#" data-id="'+rowData.id+'" class="delete_grade_setup btn-sm ml-2"><i class="fas fa-trash-alt text-danger"></i></a>'
                                        }

                                        $(td)[0].innerHTML = '<a href="#" data-id="'+rowData.id+'" data-levelid="'+rowData.levelid+'" class=" update_grade_setup btn-sm"><i class="fas fa-edit text-primary"></i></a>'+delete_button
                                    
                                    }
                                },
                            ]
                        
                });
           
            }

        })
    </script>

    <script>
        $(document).ready(function(){

            var subjid = @json($subjid);
            var strand = @json($strands)
            
            var all_subj_strand = []

            $('#add_strand').on('click',function(a,b){
                $('#add_strand_modal').modal()
            })

            $('#add_strand_button').on('click',function(a,b){

                var check_strand = all_subj_strand.filter(x=>x.strandid == $('#input_strand').val()).length
              
                if(check_strand == 0){
                    $.ajax({
                        type:'GET',
                        url:'/principal/subject/strand/create',
                        data:{
                            strandid:$('#input_strand').val(),
                            subjid:subjid,
                        },
                        success:function(data) {
                            if(data[0].status == 1){

                                var temp_strand = strand.filter(x=>x.id==$('#input_strand').val())

                                all_subj_strand.push({
                                    'id':data[0].id,
                                    'strandname':temp_strand[0].strandname,
                                    'strandcode':temp_strand[0].strandcode
                                })

                                loaddatatable()

                                Swal.fire({
                                    type: 'success',
                                    title: data[0].data,
                                });
                            }else{
                                Swal.fire({
                                    type: 'error',
                                    title: data[0].data,
                                });
                            }
                        }
                    })
                }else{
                    Swal.fire({
                        type: 'warning',
                        title: 'Strand already exist!',
                    });
                }

             
            })

            $(document).on('click','.delete_subj_strand',function(a,b){
                var subj_strand = $(this).attr('data-id')
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/strand/delete',
                    data:{
                        subj_strand_id:subj_strand,
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            Swal.fire({
                                type: 'success',
                                title: data[0].data,
                            });
                            all_subj_strand = all_subj_strand.filter(x=>x.id != subj_strand)
                            loaddatatable()
                        }else{
                            Swal.fire({
                                type: 'error',
                                title: data[0].data,
                            });
                        }
                    }
                })
            })

            get_subject_strand()

            function get_subject_strand() {
                $.ajax({
                    type:'GET',
                    url:'/principal/subjects/strand',
                    data:{
                        subjid:subjid
                    },
                    success:function(data) {
                        all_subj_strand = data
                        loaddatatable()
                    }
                })
            }

            function loaddatatable(){

                $('#subject_strand_list').empty()
                
                $.each(all_subj_strand,function(a,b){
                    $('#subject_strand_list').append('<tr><td>'+b.strandcode+'</td><td>'+b.strandname+'</td><td><a href="#" data-id="'+b.id+'" class="delete_subj_strand btn-sm ml-2"><i class="fas fa-trash-alt text-danger"></i></a></td></tr>')
                })

            }



            

        })
    </script>

    <script>
        $(document).ready(function(){

            var acadprog = @json($acadprog);
            var subjid = @json($subjid);
            var selected_sub
            var acadid = 0
            var selected_subj = null

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            if(acadprog == 'pre-school'){
                acadid = 2
            } else if(acadprog == 'gradeschool'){
                acadid = 3
            }else if(acadprog == 'juniorhighschool'){
                acadid = 4
            }else if(acadprog == 'seniorhighschool'){
                acadid = 5
            }

            var all_subjects = []
            get_subject_nocomp()
            function get_subject_nocomp() {
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/component/list/notassigned',
                    data:{
                        acadprog:acadid  
                    },
                    success:function(data) {
                        all_subjects = data
                        $("#input_component_subject").select2({
                                data: all_subjects,
                                placeholder: "Select a subject",
                        })
                    }
                })
            }

            var subject_components = []
            get_subject_components()
            function get_subject_components() {
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/component/list',
                    data:{
                        subjid:subjid
                    },
                    success:function(data) {
                        subject_components = data
                        plot_subject_components()
                    }
                })
            }

            function plot_subject_components(){
                $('#subject_component_list').empty()
                $.each(subject_components,function(a,b){
                    $('#subject_component_list').append('<tr><td class="align-middle">'+b.subjdesc+'</td><td ><button  class="btn-sm btn btn-primary remove_subject_component btn-danger" data-id="'+b.id+'">Remove</button></td></tr>')
                })
            }

            
            $(document).on('click','#add_component_button',function(){
                if($('#input_component_subject').val() == null){
                    Toast.fire({
                        type: 'info',
                        title: 'No subject selected!'
                    })
                    return false
                }
                add_subject_component()
            })

            var selected_subject_component = null
            $(document).on('click','.remove_subject_component',function(){
                selected_subject_component = $(this).attr('data-id')
                remove_subject_component()
            })

            
            function add_subject_component() {
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/component/update',
                    data:{
                        selectedsubj:$('#input_component_subject').val(),
                        subjid:subjid
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            var temp_subjects = all_subjects.filter(x=>x.id == $('#input_component_subject').val())

                            subject_components.push({
                                'id':temp_subjects[0].id,
                                'subjdesc':temp_subjects[0].subjdesc
                            })

                            all_subjects = all_subjects.filter(x=>x.id != $('#input_component_subject').val())
                            console.log(all_subjects)
                            $("#input_component_subject").empty()
                            $("#input_component_subject").append('<option value="">Select a subject</option>')
                            $("#input_component_subject").select2({
                                data: all_subjects,
                                placeholder: "Select a subject",
                            })

                            plot_subject_components()

                            Toast.fire({
                                type: 'success',
                                title: 'Added Successfully!'
                            })
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went worng!'
                            })
                        }
                    }
                })
            }

            function remove_subject_component() {
                $.ajax({
                    type:'GET',
                    url:'/principal/subject/component/remove',
                    data:{
                        subjid:selected_subject_component
                    },
                    success:function(data) {
                        if(data[0].status == 1){
                            var temp_subjects = subject_components.filter(x=>x.id == selected_subject_component)
                            subject_components = subject_components.filter(x=>x.id != selected_subject_component)
                          




                            all_subjects.push({
                                'id':temp_subjects[0].id,
                                'subjdesc':temp_subjects[0].subjdesc,
                                'text':temp_subjects[0].subjdesc
                            })

                            $("#input_component_subject").empty()
                            $("#input_component_subject").append('<option value="">Select a subject</option>')
                            $("#input_component_subject").select2({
                                data: all_subjects,
                                placeholder: "Select a subject",
                            })

                            plot_subject_components()

                            Toast.fire({
                                type: 'success',
                                title: 'Removed Successfully!'
                            })
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went worng!'
                            })
                        }
                    }
                })
            }


            $(document).on('click','#add_subject_component',function(){
                $('#subject_component_modal').modal()
            })

            

        })
    </script>
    

@endsection

