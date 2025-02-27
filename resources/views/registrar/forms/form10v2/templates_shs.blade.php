<style>
    td{
        padding: 0px !important;
    }
</style>


{{-- <div class="row mb-2">
    <div class="col-md-4">
        <label>Select Template</label>
        <select class="form-control" id="template-id">
            <option value="0">1</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>&nbsp;</label><br/>
        <button type="button" class="btn btn-primary">View Template</button>
    </div>
</div> --}}
@foreach($gradelevels as $eachlevel)
    <div class="row each-level-setup mb-2" data-levelid="{{$eachlevel->id}}">
        <div class="col-md-12">
            <table class="table table-bordered" style="table-layout: fixed;">
                <tr>
                    <th colspan="6" class="bg-warning p-2"><p class="m-0">{{$eachlevel->levelname}} - @if($eachlevel->semid == 1) 1st Semester @elseif($eachlevel->semid ==2) 2nd Semester @endif</p></th>
                    <th colspan="2" class="p-2 text-right" style="vertical-align: middle;">
                        <div class="form-group clearfix m-0">
                        <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary{{$eachlevel->id}}1{{$eachlevel->semid}}" name="{{$eachlevel->levelname}}{{$eachlevel->semid}}" @if(count($eachlevel->subjects) == 0)checked=""@else @if($eachlevel->subjects[0]->sheetnum == 1) checked="" @endif @endif>
                        <label for="radioPrimary{{$eachlevel->id}}1{{$eachlevel->semid}}">
                            Front 
                        </label>
                        </div>
                        <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary{{$eachlevel->id}}2{{$eachlevel->semid}}" name="{{$eachlevel->levelname}}{{$eachlevel->semid}}" @if(count($eachlevel->subjects) > 0) @if($eachlevel->subjects[0]->sheetnum == 2) checked="" @endif @endif>
                        <label for="radioPrimary{{$eachlevel->id}}2{{$eachlevel->semid}}">
                            Back
                        </label>
                        </div>
                        </div>
                    </th>
                </tr>
                {{-- <tr>
                    <th colspan="6" rowspan="3" style="vertical-align: middle;">Header subjects (School Information Section)</th>
                    <th colspan="2" class="p-0 text-center">Row</td>
                    <th class="p-0 text-center">Column</th>
                </tr>
                <tr class="text-center">
                    <td>Starting</td>
                    <td>Ending</td>
                    <td></td>
                </tr>
                @if(count($eachlevel->headers) == 0)
                <tr>
                    <td><input type="number" class="form-control form-control-sm input-row-header-start"/></td>
                    <td><input type="number" class="form-control form-control-sm input-row-header-end"/></td>
                    <td><input type="text" class="form-control form-control-sm  input-col-header" name="cols-header"/></td>
                </tr>
                @else
                <tr>
                    <td><input type="number" class="form-control form-control-sm input-row-header-start" value="{{$eachlevel->headers[0]->rowstart}}"/></td>
                    <td><input type="number" class="form-control form-control-sm input-row-header-end" value="{{$eachlevel->headers[0]->rowend}}"/></td>
                    <td><input type="text" class="form-control form-control-sm  input-col-header" name="cols-header" value="{{$eachlevel->headers[0]->col1}}"/></td>
                </tr>
                @endif --}}
                <tr>
                    <th colspan="8">Grade subjects</th>
                </tr>
                <tr class="text-center">
                    <th colspan="2" class="p-0">Row</td>
                    <th colspan="6" class="p-0">Column</th>
                </tr>
                <tr class="text-center">
                    <td>Starting</td>
                    <td>Ending</td>
                    <td>Indication</td>
                    <td>Subject</td>
                    <td>Q1</td>
                    <td>Q2</th>
                    <td>Sem Final Grade</td>
                    <td>Remarks</th>
                </tr>
                <tr class="text-center">
                    @if(count($eachlevel->subjects) == 0)
                    <td><input type="number" class="form-control form-control-sm input-row-start"/></td>
                    <td><input type="number" class="form-control form-control-sm input-row-end"/></td>
                    <td><input type="text" class="form-control form-control-sm  input-col" name="cols"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols"/></td>
                    @else
                    <td><input type="number" class="form-control form-control-sm input-row-start" value="{{$eachlevel->subjects[0]->rowstart}}"/></td>
                    <td><input type="number" class="form-control form-control-sm input-row-end" value="{{$eachlevel->subjects[0]->rowend}}"/></td>
                    <td><input type="text" class="form-control form-control-sm  input-col" name="cols" value="{{$eachlevel->subjects[0]->col1}}"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols" value="{{$eachlevel->subjects[0]->col2}}"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols" value="{{$eachlevel->subjects[0]->col3}}"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols" value="{{$eachlevel->subjects[0]->col4}}"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols" value="{{$eachlevel->subjects[0]->col5}}"/></td>
                    <td><input type="text" class="form-control form-control-sm input-col" name="cols" value="{{$eachlevel->subjects[0]->col6}}"/></td>
                    @endif
                </tr>
                <tr>
                    <td colspan="8" class="text-right"><button type="button" class="btn btn-sm btn-success btn-save-level-setup" data-levelid="{{$eachlevel->id}}" data-semid="{{$eachlevel->semid}}"><i class="fa fa-share"></i> Save changes</button></td>
                </tr>
            </table>
            {{-- <div class="alert alert-success" role="alert">
            
            </div> --}}
        </div>
        {{-- <div class="col-md-12 text-right">
            <button type="button" class="btn btn-outline-primary"><i class="fa fa-share"></i> Save changes</button>
        </div> --}}
    </div>
@endforeach

<script>
    $('.btn-save-level-setup').on('click', function(){
        var levelid = $(this).attr('data-levelid')
        var semid = $(this).attr('data-semid')
        var templateid = $('#template-id').val();
        var thislevel = $(this).closest('.each-level-setup');
        var checkvalidation = 0;

        //grades
        var rowstart = thislevel.find('.input-row-start').val()
        var rowend = thislevel.find('.input-row-end').val()
        var datacols = thislevel.find('.input-col')
        var cols = [];
        var sheetnum = 1;
        if($('#radioPrimary'+levelid+'2').is(':checked')) {
            sheetnum = 2;
        }
        if(rowstart.replace(/^\s+|\s+$/g, "").length == 0)
        {
            checkvalidation=1;
            thislevel.find('.input-row-start').css('border','1px solid red')
        }
        if(rowend.replace(/^\s+|\s+$/g, "").length == 0)
        {
            checkvalidation=1;
            thislevel.find('.input-row-end').css('border','1px solid red')
        }
        for(var i = 0; i < datacols.length; i++){
            if($(datacols[i]).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                checkvalidation=1;
                $(datacols[i]).css('border','1px solid red')
            }
            cols.push($(datacols[i]).val())
        }
        
        if(checkvalidation > 0)
        {
            toastr.warning('Please fill in required fields!')
        }else{
            
            $.ajax({
                url: '/reports_schoolform10v2/template',
                type: 'GET',
                data: {
                    action          : 'update',
                    templateid              : templateid,
                    acadprogid      : 5,
                    levelid      : levelid,
                    templateid      : templateid,
                    rowstart      : rowstart,
                    rowend      : rowend,
                    semid      : semid,
                    cols      : JSON.stringify(cols),
                    sheetnum      : sheetnum
                },
                success:function(data){
                    if(data == 1)
                    {
                        toastr.success('Deleted successfully!')
                        $('#btn-getrecords').click();

                    }else{
                        toastr.error('Something went wrong!')
                    }
                }
            })
        }

    })
</script>