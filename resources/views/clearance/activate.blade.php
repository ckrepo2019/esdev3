@extends($extends)
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Clearance Activation</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Clearance Activation</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="card">
    <div class="card-header">
        <div class="row mb-2">
            <div class="col-md-3">
                <label>School Year</label>
                <select class="form-control form-control-sm" id="select-syid">
                    @foreach(DB::table('sy')->get() as $eachsy)
                        <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Semester</label>
                <select class="form-control form-control-sm" id="select_sem">
                    <option value="0">All</option>
                    @foreach(DB::table('semester')->get() as $eachsemester)
                        <option value="{{$eachsemester->id}}" @if($eachsemester->isactive == 1) selected @endif>{{$eachsemester->semester}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Academic Prog.</label>
                <select class="form-control form-control-sm" id="select-acadprogid">
                    <option value="0">All</option>
                    @foreach(DB::table('academicprogram')->get() as $eachacadprog)
                        <option value="{{$eachacadprog->id}}">{{$eachacadprog->acadprogcode}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label>Grade Level</label>
                <select class="form-control form-control-sm" id="select-levelid"></select>
            </div>
            <div class="col-md-3">
                <label>Section</label>
                <select class="form-control form-control-sm" id="select_sec">
                    <option value="0">All</option>
                    @foreach(DB::table('sections')->get() as $eachsection)
                        <option value="{{$eachsection->id}}">{{$eachsection->sectionname}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-primary btn-sm" id="btn-getsignatories"><i class="fa fa-sync"></i> Generate</button>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="row">
                <h3>Enrolled Student</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table border-0" id="student_table" width="100%" >
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Section</th>
                            <th>Action</th>
                        </tr>                    
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerscripts')
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>//
<script>
    $(document).ready(function(){

        $('#select-acadprogid').on('change', function(){
            getlevels()
        })

        function getacadprogs()
        {
            $.ajax({
                url: '/setup/student/clearance/getacadprogs',
                type:'GET',
                dataType: 'json',
                data: {
                    formid       :  $('#select-formid').val()
                },
                success:function(data) {
                    $('#select-acadprogid').empty()
                    if(data.length > 0)
                    {
                            $('#select-acadprogid').append(
                                '<option value="0">All</option>'
                            )
                        $.each(data, function(key,value){
                            $('#select-acadprogid').append(
                                '<option value="'+value.id+'">'+value.acadprogcode+'</option>'
                            )
                        })
                    }
                }
            })
            getlevels()
        }

        function getlevels()
        {
            $('#select-levelid').empty()
            // console.log($('#select-acadprogid').val())
            $.ajax({
                url: '/setup/student/clearance/getlevelids',
                type:'GET',
                dataType: 'json',
                data: {
                    acadprogid  :  $('#select-acadprogid').val()
                },
                success:function(data) {
                    console.log(data)
                    $('#select-levelid').append(
                        '<option value="0" selected >All</option>'
                    )
                    if(data.length > 0)
                    {
                        $.each(data, function(key,value){
                            $('#select-levelid').append(
                                '<option value="'+value.id+'">'+value.levelname+'</option>'
                            )
                        })
                    }
                }
            })
            // getsection()
        }

        get_student_table()

        function get_student_table(){
            var url = '/setup/student/clearance/activation/list';

            var table = $("#student_table").DataTable({
                destroy: true,
                lengthChange: false,
                deferRender: true,
                autoWidth: false,
                stateSave: true,
                serverSide: true,
                processing: true,
                ajax:{
                    url: url,
                    type: 'GET',
                    data: {
                        syid:$('#select-syid').val(),
                    },
                },
                columns: [
                { "data": null},
                { "data": null},
                { "data": null},
                { "data": null},
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            // if(rowData.picurl == null){
                            //     var text = '<img width="75%" src="'+onerror_url+'" alt="" class="img-circle img-fluid view_image" data-id="'+rowData.id+'">'
                            // }else{
                            //     var text = '<img width="75%" src="'+rowData.picurl+'" onerror="this.src=\''+onerror_url+'\'" alt="" class="img-circle img-fluid view_image" data-id="'+rowData.id+'">'
                            // }
                            console.log(rowData);
                            var text = "rowData.id"

                            $(td)[0].innerHTML =  text 
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = ''
                            var status = ''
                            
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {

                            $(td)[0].innerHTML =  text 
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            
                            $(td)[0].innerHTML =  text 
                        }
                    }
            //     {
            //         'targets': 4,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             var utype = rowData.utype+ ' '+ acad
            //             var length = rowData.faspriv.length 
            //             if(length > 0){
            //                 utype += '<p class="text-muted mb-0" style="font-size:.7rem">'
            //                 $.each(rowData.faspriv,function(a,b){
            //                 utype += b.utype
            //                 if(length > a+1){
            //                     utype += ' , '
            //                 }
            //             })
            //             utype += '</p>'
            //             }
                        
            //             $(td)[0].innerHTML =  utype 

            //         }
            //     },
            //     {
            //         'targets': 5,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             var buttons = '<a href="#" class="view_fas_info" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
            //             $(td)[0].innerHTML =  buttons
            //             $(td).addClass('text-center')
            //             $(td).addClass('align-middle')
            //         }
            //     },
            //     {
            //         'targets': 6,
            //         'orderable': false, 
            //         'createdCell':  function (td, cellData, rowData, row, col) {
            //             var more_data_icon = '<a data-id="'+rowData.id+'"><i class="fas fa-info-circle text-primary"></i></a>';

            //             $(td)[0].innerHTML =  more_data_icon
            //             $(td).addClass('text-center')
            //             $(td).addClass('align-middle')
            //         }
            //     },
                ]            
            });
        }

    })
</script>
@endsection