
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-2 text-right">
                            {{-- <button type="button" class="btn btn-default" id="btn-exportpdf"><i class="fa fa-file-pdf"></i> PDF</button> --}}
                            <button type="button" class="btn btn-default" id="btn-exportexcel"><i class="fa fa-file-pdf"></i> EXCEL</button>
                        </div>
                        <div class="col-md-12">
                            <table class="table" style="font-size: 14px;" id="table-results">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Grade Level</th>
                                        <th>School Last Attended</th>
                                        {{-- <th></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr class="eachstudent">
                                            <td><strong>{{$student->lastname}}</strong>, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}<br/>
                                                <small>LRN: {{$student->lrn}}</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>SID: {{$student->sid}}</small>
                                            </td>
                                            <td>{{$student->levelname}}</td>
                                            <td><input type="text" class="form-control lastschoolatt" value="{{$student->lastschoolatt}}" readonly data-id="{{$student->id}}"/></td>
                                            {{-- <td><button type="button" class="btn btn-success btn-save" ><i class="fa fa-share"></i></button></td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>

            <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
            <script>
                $(document).ready(function(){
                    $('.lastschoolatt').dblclick(function(){
                        $(this).removeAttr('readonly');
                        console.log($(this))
                    })
                        $("#table-results").DataTable({
                                    pageLength : 10
                        });
                })
                </script>