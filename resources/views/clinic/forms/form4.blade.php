<div class="card-header bg-info">
    <h6 class="card-title">Teacher's Health Card</h6>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table" style="font-size: 14px;">
                <thead>
                    <tr>
                        <th><button type="button" class="btn btn-primary btn-sm" id="btn-addfamhistoryillness" data-toggle="modal" data-target="#modal-addillness"><i class="fa fa-plus"></i></button> Family History: (pls. check)</th>
                        <th>Y</th>
                        <th>N</th>
                        <th class="text-center">Specify Relationship</th>
                    </tr>
                </thead>
                @if(count($famhislist)>0)
                    @foreach($famhislist as $famhis)
                        <tr>
                            <td class="pl-4"><i class="fa fa-trash-alt form4-deletefamhisoption" data-id="{{$famhis->id}}"></i> {{$famhis->description}}</td>
                            <td>
                                <div class="icheck-primary d-inline">
                                  <input type="radio" id="radioyes{{$famhis->id}}" name="radio{{$famhis->id}}">
                                  <label for="radioyes{{$famhis->id}}">
                                  </label>
                                </div>
                            </td>
                            <td>
                                <div class="icheck-primary d-inline">
                                  <input type="radio" id="radiono{{$famhis->id}}" name="radio{{$famhis->id}}" checked="">
                                  <label for="radiono{{$famhis->id}}">
                                  </label>
                                </div>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" style="border: none;" placeholder="Relationship"/></td>
                        </tr>
                    @endforeach
                @endif
            </table>
            <div class="modal fade" id="modal-addillness">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Add</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label></label>
                                <input type="text" class="form-control" id="input-form4-addillnes-option-description"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-addillness">Close</button>
                      <button type="button" class="btn btn-primary" id="btn-submit-addillness">Submit</button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <label>Other Remarks:</label>
        </div>
        <div class="col-md-10">
            <input type="text" class="form-control form-control-sm" style="border: none; border-bottom: 1px solid #ddd;"/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary btn-sm" id="btn-addpastmedhisillness" data-toggle="modal" data-target="#modal-addpastmedhisillness"><i class="fa fa-plus"></i></button> Past Medical History: (pls. check)
        </div>
    </div>
    <div class="row mb-2">
        @if(count($pastmedhislist)>0)
            @foreach($pastmedhislist as $pastmedhis)
                <div class="col-md-6 mb-2">
                    <div class="row">
                        <div class="col-md-8 pl-5">
                            <i class="fa fa-trash-alt form4-deletepastmedhisoption" data-id="{{$pastmedhis->id}}"></i> {{$pastmedhis->description}}
                        </div>
                        <div class="col-md-4">
        
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="pastmedhisradioyes{{$pastmedhis->id}}" name="radio{{$pastmedhis->id}}">
                                <label for="pastmedhisradioyes{{$pastmedhis->id}}">
                                    Yes
                                </label>
                              </div>
        
                              <div class="icheck-primary d-inline">
                                <input type="radio" id="pastmedhisradiono{{$pastmedhis->id}}" name="radio{{$pastmedhis->id}}" checked="">
                                <label for="pastmedhisradiono{{$pastmedhis->id}}">
                                    No
                                </label>
                              </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        <div class="modal fade" id="modal-addpastmedhisillness">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Add</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label></label>
                            <input type="text" class="form-control" id="input-form4-addpastmedhisillness-option-description"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-addpastmedhisillness">Close</button>
                  <button type="button" class="btn btn-primary" id="btn-submit-addpastmedhisillness">Submit</button>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table" style="font-size: 14px;">
                <thead>
                    <tr>
                        <th style="width: 40%;"><button type="button" class="btn btn-primary btn-sm" id="btn-addlasttaken" data-toggle="modal" data-target="#modal-addlasttaken"><i class="fa fa-plus"></i></button> Last Taken</th>
                        <th class="text-center">Date</th>
                        <th class="text-center" style="width: 40%;">Result</th>
                    </tr>
                </thead>
                @if(count($lasttakenexams)>0)
                    <tbody>
                        @foreach($lasttakenexams as $lasttakenexam)
                            <tr>
                                <td class="pl-4"><i class="fa fa-trash-alt form4-deletelasttaken" data-id="{{$lasttakenexam->id}}"></i>&nbsp; {{$lasttakenexam->description}}</td>
                                <td><input type="date" class="form-control form-control-sm" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                                <td><input type="text" class="form-control form-control-sm" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
            <div class="modal fade" id="modal-addlasttaken">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Add</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label></label>
                                <input type="text" class="form-control" id="input-form4-addlasttaken-option-description"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-addlasttaken">Close</button>
                      <button type="button" class="btn btn-primary" id="btn-submit-addlasttaken">Submit</button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on('click','#btn-submit-addillness', function(){
        var newillnessoption = $('#input-form4-addillnes-option-description').val();
// btn-close-addillness
        var userid = $('#select-user').val();
        if(newillnessoption.replace(/^\s+|\s+$/g, "").length == 0)
        {
            $('#input-form4-addillnes-option-description').css('border','1px solid red');
            Toast.fire({
                type: 'warning',
                title: 'Please fill in required field!'
            })
        }else{
            $.ajax({
                url: '/clinic/records/form4/addnewfamhisoption',
                type:'GET',
                dataType: 'json',
                data: {
                    userid              :  userid,
                    newillnessoption    :  newillnessoption
                },
                success:function(data) {
                    if(data == 1)
                    {
                        Toast.fire({
                            type: 'success',
                            title: 'Add successfully!'
                        })
                        $('#btn-close-addillness').click();
                        $('#button-generate').click()
                    }
                    else if(data == 2)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: 'Already exist!'
                        })
                        $('#btn-close-addillness').click();
                    }else{
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                }
            })
        }
    })
    $(document).on('click','.form4-deletefamhisoption', function(){
        var id = $(this).attr('data-id')
        var thistr = $(this).closest('tr');
        Swal.fire({
            title: 'Are you sure you want to delete this?',
            html: 'You won\'t be able to revert this!',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete'
        })
        .then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/clinic/records/form4/deletefamhisoption',
                    type:'GET',
                    dataType: 'json',
                    data: {
                        id    :  id
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            Toast.fire({
                                type: 'success',
                                title: 'Deleted successfully!'
                            })
                            thistr.remove()
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            }
        })
    })
    $(document).on('click','#btn-submit-addpastmedhisillness', function(){
        var newillnessoption = $('#input-form4-addpastmedhisillness-option-description').val();
        var userid = $('#select-user').val();
        if(newillnessoption.replace(/^\s+|\s+$/g, "").length == 0)
        {
            $('#input-form4-addpastmedhisillness-option-description').css('border','1px solid red');
            Toast.fire({
                type: 'warning',
                title: 'Please fill in required field!'
            })
        }else{
            $.ajax({
                url: '/clinic/records/form4/addnewpastmedhisoption',
                type:'GET',
                dataType: 'json',
                data: {
                    userid              :  userid,
                    newillnessoption    :  newillnessoption
                },
                success:function(data) {
                    if(data == 1)
                    {
                        Toast.fire({
                            type: 'success',
                            title: 'Add successfully!'
                        })
                        $('#btn-close-addpastmedhisillness').click();
                        $('#button-generate').click()
                    }
                    else if(data == 2)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: 'Already exist!'
                        })
                    }else{
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                }
            })
        }
    })
    $(document).on('click','.form4-deletepastmedhisoption', function(){
        var id = $(this).attr('data-id')
        var thisdiv = $(this).closest('.col-md-6');
        Swal.fire({
            title: 'Are you sure you want to delete this?',
            html: 'You won\'t be able to revert this!',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete'
        })
        .then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/clinic/records/form4/deletepastmedhisoption',
                    type:'GET',
                    dataType: 'json',
                    data: {
                        id    :  id
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            Toast.fire({
                                type: 'success',
                                title: 'Deleted successfully!'
                            })
                            thisdiv.remove()
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            }
        })
    })
    $(document).on('click','#btn-submit-addlasttaken', function(){
        var description = $('#input-form4-addlasttaken-option-description').val();
        var userid = $('#select-user').val();
        if(description.replace(/^\s+|\s+$/g, "").length == 0)
        {
            $('#input-form4-addlasttaken-option-description').css('border','1px solid red');
            Toast.fire({
                type: 'warning',
                title: 'Please fill in required field!'
            })
        }else{
            $.ajax({
                url: '/clinic/records/form4/addlasttaken',
                type:'GET',
                dataType: 'json',
                data: {
                    description    :  description
                },
                success:function(data) {
                    if(data == 1)
                    {
                        Toast.fire({
                            type: 'success',
                            title: 'Add successfully!'
                        })
                        $('#btn-close-addlasttaken').click();
                        $('#button-generate').click()
                    }
                    else if(data == 2)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: 'Already exist!'
                        })
                    }else{
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                }
            })
        }
    })
    $(document).on('click','.form4-deletelasttaken', function(){
        var id = $(this).attr('data-id')
        var thistr = $(this).closest('tr');
        Swal.fire({
            title: 'Are you sure you want to delete this?',
            html: 'You won\'t be able to revert this!',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete'
        })
        .then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/clinic/records/form4/deletelasttaken',
                    type:'GET',
                    dataType: 'json',
                    data: {
                        id    :  id
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            Toast.fire({
                                type: 'success',
                                title: 'Deleted successfully!'
                            })
                            thistr.remove()
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            }
        })
    })
</script>