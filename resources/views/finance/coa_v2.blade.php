@extends('finance.layouts.app')

@section('content')
  {{-- <style type="text/css">
    .table thead th  { 
                position: sticky !important; left: 0 !important; 
                width: 150px !important;
                background-color: #fff !important; 
                outline: 2px solid #fff !important;
                outline-offset: -1px !important;
            }
  </style> --}}
	{{-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Payment Items</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
            <h1 class="m-0 text-dark">Chart of Accounts</h1>
        </div>
        <div class="row">
            <div class="col-2">
                <button id="_class" class="btn btn-info btn-block">
                    Classification
                </button>
            </div>
            <div class="col-md-2">
                
            </div>
            <div class="col-md-4">
                <select id="coa_acctypefilter"  class="select2 filter" style="width:100%;">
                </select>
            </div>
            <div class="col-4">
                <div class="input-group mb-3">
                    <input id="coa_search" type="text" class="form-control filter" placeholder="Account Title" onkeyup="this.value = this.value.toUpperCase();">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="coa_groupcreate">Create</button>
                    </div>
                </div>
            </div>

        </div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <table class="table table-hover table-head-fixed table-sm text-sm">
                                <thead class="bg-warning p-0">
                                  <tr>
                                    <th>CODE</th>
                                    <th>Account Title</th>
                                    <th>Classification</th>
                                    <th>Mapping</th>
                                  </tr>
                                </thead> 
                                <tbody id="coa_list"></tbody>             
                            </table>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
  </section>
@endsection

@section('modal')
    <div class="modal fade show" id="modal-accounts" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content text-sm" style="">
                <div id="modalhead" class="modal-header bg-info">
                    <h5 class="modal-title"><span id="item_action"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input id="coa_code" class="form-control" placeholder="Account Code">  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input id="coa_account" class="form-control" placeholder="Account Title">  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <select id="coa_map" class="select2 form-control">
                              <option value="0">Select Mapping</option>
                              @foreach(db::table('acc_map')->get() as $map)
                                <option value="{{$map->id}}">{{$map->mapname}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" data-toggle="tooltip" title="Create Mapping"><i class="fas fa-external-link-alt"></i></button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="coa_save" type="button" class="btn btn-primary" data-id="0">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-groups" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content text-sm" style="">
                <div id="modalhead" class="modal-header bg-info">
                    <h4 class="modal-title"><span id="">Account Type</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input id="group_accounttype" class="form-control" placeholder="Account Type" style="text-transform: uppercase;">  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <select id="group_class" class="select2" style="width: 100%;">
                                <option value="0">ACCOUNT TYPE</option>
                                @foreach(db::table('acc_coaclass')->where('deleted', 0)->get() as $class)
                                    <option value="{{$class->id}}">{{strtoupper($class->classification)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <input id="group_sort" class="form-control" type="number">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-warning btn-block" data-toggle="tooltip" title="View Sorting">Sort</i></button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="coa_groupsave" type="button" class="btn btn-primary" data-id="0" data-action="">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>


    <div class="modal fade show" id="modal-classifications" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-sm" style="">
                <div id="modalhead" class="modal-header bg-info">
                    <h4 class="modal-title">Classifications</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="height: 33em;">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <input id="class_name" class="form-control" placeholder="Classification">
                        </div>
                        <div class="col-md-4">
                            <button id="class_save" class="btn btn-primary" data-toggle="tooltip" title="Save">
                                <i class="fa fa-save"></i>
                            </button>
                            <button id="class_remove" class="btn btn-danger" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button id="class_create" class="btn btn-success" data-toggle="tooltip" title="Create">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="row form-group">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-sm text-sm" cellpadding="0" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>CLASSIFICATION</th>
                                    </tr>
                                </thead>
                                <tbody id="classlist" style="cursor: pointer;">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="coa_save" type="button" class="btn btn-primary" data-id="0">Save</button>
                </div> --}}
            </div>
        </div> {{-- dialog --}}
    </div>
  

  
@endsection

@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 300);
            // $('.screen-adj').css('height', screen_height - 223);
        }

        coa_group();
        coa_view();

        function coa_group()
        {
            $.ajax({
                url: '{{route('coa_group')}}',
                type: 'GET',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                // data: {param1: 'value1'},
                success:function(data)
                {
                    $('#coa_acctypefilter').html(data);
                }
            }); 
        }

        function coa_view()
        {
            filter = $('#coa_search').val();
            acctype = $('#coa_acctypefilter').val();

            $.ajax({
                url: '{{route('coa_view')}}',
                type: 'GET',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {
                    filter:filter,
                    acctype:acctype
                },
                success:function(data)
                {
                    $('#coa_list').html(data);
                }

            });
            
        }

        $(document).on('change', '.filter', function(){
            coa_view();
        });

        $(document).on('click', '#coa_list tr', function(){
            // console.log($(this).attr('data-value'));

            $('.subfunction').removeClass('d-inline-table-cell');
            $('.subfunction').addClass('d-none');
            $(this).find('.subfunction').removeClass('d-none');
            $(this).find('.subfunction').addClass('d-inline-table-cell');
            $('#coa_save').attr('data-id', 0);
            $('#coa_save').attr('subid', 0);
        });

        $(document).on('click', '.group_addchild', function(){
            var groupid = $(this).closest('tr').attr('data-id');

            $.ajax({
                url: '{{route('coa_viewgroup')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    groupid:groupid
                },
                success:function(data)
                {
                    $('#coa_account').val('');
                    $('#coa_code').val('');
                    $('#item_action').text(data.groupname);
                    $('#coa_code').val(data.maxcode);
                    $('#modal-accounts').modal('show');
                    $('#coa_save').attr('data-value', 'group');
                    $('#coa_save').attr('groupid', groupid);
                    $('#coa_save').attr('groupname', data.groupname);
                    
                }
            });
        });

        $(document).on('click', '#coa_save', function(){
            var groupid = $('#coa_save').attr('groupid');
            var groupname = $('#coa_save').attr('groupname');
            var dataval = $('#coa_save').attr('data-value');
            var code = $('#coa_code').val();
            var account = $('#coa_account').val();
            var mapid = $('#coa_map').val();
            var dataid = $(this).attr('data-id');
            var subid = $(this).attr('subid');

            console.log('map: ' + mapid)

            $.ajax({
                url: '{{route('coa_saveaccount')}}',
                type: 'GET',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {
                    dataid:dataid,
                    groupid:groupid,
                    groupname:groupname,
                    dataval:dataval,
                    code:code,
                    account:account,
                    mapid:mapid,
                    subid:subid
                },
                success:function(data)
                {
                    if(data != 'exist')
                    {
                        if(data == 'update')
                        {
                            coa_view();

                            const Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                              toast.addEventListener('mouseenter', Swal.stopTimer)
                              toast.addEventListener('mouseleave', Swal.resumeTimer)
                              }
                            })

                            Toast.fire({
                              type: 'success',
                              title: 'Account successfully updated.'
                            });

                            $('#modal-accounts').modal('hide');
                        }
                        else
                        {
                            coa_view();
                            const Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                              toast.addEventListener('mouseenter', Swal.stopTimer)
                              toast.addEventListener('mouseleave', Swal.resumeTimer)
                              }
                            })

                            Toast.fire({
                              type: 'success',
                              title: 'Account successfully saved.'
                            });

                            $('#coa_code').val(data);
                            $('#coa_account').val('');
                            $('#coa_account').focus();

                        }

                    }
                    else
                    {
                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                          toast.addEventListener('mouseenter', Swal.stopTimer)
                          toast.addEventListener('mouseleave', Swal.resumeTimer)
                          }
                        })

                        Toast.fire({
                          type: 'error',
                          title: 'Account already exist.'
                        });                        
                    }
                }
            }); 
        });

        $(document).on('click', '.account_remove', function(){
            var accountid = $(this).closest('tr').attr('data-id');

            Swal.fire({
                title: 'Delete Account?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        url: '{{route('coa_removeaccount')}}',
                        type: 'GET',
                        // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                        data: {
                            accountid:accountid
                        },
                        success:function(data)
                        {       
                            if(data == 'done')    
                            {
                                Swal.fire(
                                    'Deleted!',
                                    'Account has been deleted.',
                                    'success'
                                );
                                coa_view();
                            }
                            else
                            {
                                Swal.fire(
                                    'Error',
                                    'Account has a sub accounts',
                                    'error'
                                );
                            }
                        }
                    });        
                }
            }) 
        });

        $(document).on('click', '.account_edit', function(){
            var dataid = $(this).closest('tr').attr('data-id');
            console.log(dataid);

            $.ajax({
                url: '{{route('coa_editaccount')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    dataid:dataid
                },
                success:function(data) 
                {
                    $('#item_action').text(data.group);
                    $('#coa_save').attr('data-id', dataid);
                    $('#coa_code').val(data.code);
                    $('#coa_account').val(data.account);
                    $('#coa_map').val(data.mapid).trigger('change')
                    $('#modal-accounts').modal('show');
                }
            });            
        });

        $(document).on('click', '.account_addchild', function(){
            var subid = $(this).closest('tr').attr('data-id');

            $.ajax({
                url: '{{route('coa_addsubaccount')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    subid:subid
                },
                success:function(data)
                {
                    $('#coa_save').attr('subid', subid);
                    $('#item_action').text(data.account);
                    $('#coa_account').val('');
                    $('#coa_save').attr('data-value', 'account')
                    $('#coa_code').val(data.subcode);
                    $('#modal-accounts').modal('show');
                }
            });
            
        });

        $(document).on('click', '#_class', function(){
            $.ajax({
                url: '{{route('coa_classcreate')}}',
                type: 'GET',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                // data: {param1: 'value1'},
                success:function(data)
                {
                    $('#classlist').html(data);
                    $('#modal-classifications').modal('show');
                }
            });
        });

        $(document).on('mouseenter', '#classlist tr', function(){
            $(this).addClass('bg-primary');
        });

        $(document).on('mouseout', '#classlist', function(){
            $('#classlist tr').removeClass('bg-primary');
        });

        $(document).on('click', '#classlist tr', function(){
            var dataid = $(this).attr('data-id');
            // $(this).removeClass('bg-primary');
            $('#classlist tr').removeClass('bg-success');
            $(this).addClass('bg-success');

            $.ajax({
                url: '{{route('coa_class_load')}}',
                type: 'GET',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {
                    dataid:dataid
                },
                success:function(data)
                {
                    $('#class_name').val(data);
                    $('#class_save').attr('data-id', dataid);
                }
            });
        });

        $(document).on('click', '#class_save', function(){
            var dataid = $(this).attr('data-id');
            var classification = $('#class_name').val();

            $.ajax({
                url: '{{route('coa_class_update')}}',
                type: 'GET',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {
                    dataid:dataid,
                    class:classification
                },
                success:function(data)
                {
                    if(data == 'exist')
                    {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                          type: 'error',
                          title: 'Classification already exist.'
                        });   
                    }
                    else
                    {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                          type: 'success',
                          title: 'Classification updated.'
                        });   

                        $('#_class').trigger('click');
                    }
                }
            }); 
        });

        $(document).on('click', '#class_create', function(){
            var classification = $('#class_name').val();

            if(classification != '')
            {
                $.ajax({
                    url: '{{route('coa_class_create')}}',
                    type: 'GET',
                    // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                    data: {
                        class:classification
                    },
                    success:function(data)
                    {
                        if(data ==  'done')
                        {
                            const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    onOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                  type: 'success',
                                  title: 'Classification saved.'
                                });   

                            $('#_class').trigger('click');
                        }
                        else
                        {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                onOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                              type: 'error',
                              title: 'Classification already exist.'
                            });  
                        }

                        
                    }
                });
            }
        });

        $(document).on('click', '#class_remove', function(){
            var dataid = $('#class_save').attr('data-id');

            Swal.fire({
                title: 'Delete Classification?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        url: '{{route('coa_class_remove')}}',
                        type: 'GET',
                        // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                        data: {
                            dataid:dataid
                        },
                        success:function(data)
                        {
                            if(data == 'done')
                            {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    onOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                  type: 'success',
                                  title: 'Classification has been deleted.'
                                });   

                                $('#_class').trigger('click');
                            }
                            else
                            {
                                Swal.fire(
                                    'There are accounts assigned to this Classification',
                                    'Please remove all assigned accounts to this Classification to continue',
                                    'error'
                                )
                            }
                        }
                    });
                }
            })
        });

        $(document).on('click', '#coa_groupcreate', function(){
            $.ajax({
                url: '{{route('coa_coaclass_load')}}',
                type: 'GET',
                dataType: 'json',
                // data: {param1: 'value1'},
                success:function(data)
                {
                    $('#group_class').html(data.list);
                    $('#group_sort').val(data.sortid);
                    $('#group_accounttype').val('');
                    $('#group_class').val(0).change();
                    $("#coa_groupsave").attr('data-action', 'create');

                    setTimeout(function(){
                        $('#group_accounttype').focus();
                    }, 300)

                    $('#modal-groups').modal('show'); 
                }
            });            
        });

        $(document).on('click', '#coa_groupsave', function(){
            var acctype = $('#group_accounttype').val();
            var classid = $('#group_class').val();
            var sortid = $('#group_sort').val();
            var action = $(this).attr('data-action');
            var dataid = $(this).attr('data-id');

            if(acctype != '')
            {
                if(classid > 0)
                {
                    if(action == 'create')
                    {
                        $.ajax({
                            url: '{{route('coa_acctype_create')}}',
                            type: 'GET',
                            // dataType: 'json',
                            data: {
                                acctype:acctype,
                                classid:classid,
                                sortid:sortid
                            },
                            success:function(data)
                            {
                                if(data == 'done')
                                {
                                    coa_group();
                                    coa_view();
                                    $('#coa_groupcreate').trigger('click');
                                    // $('#modal-groups').modal('hide');
                                }
                                else
                                {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        onOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                        }
                                    })

                                    Toast.fire({
                                      type: 'error',
                                      title: 'Account type already exist'
                                    });
                                }
                            }
                        });
                    }
                    else
                    {
                        $.ajax({
                            url: '{{route('coa_acctype_update')}}',
                            type: 'GET',
                            // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                            data: {
                                dataid:dataid,
                                acctype:acctype,
                                classid:classid,
                                sortid:sortid
                            },
                            success:function(data)
                            {
                                coa_view();
                                $('#modal-groups').modal('hide');
                            }
                        });
                    }
                }
                else
                {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                      type: 'error',
                      title: 'Please select classification'
                    });  

                    setTimeout(function(){
                        $('#group_class').focus();
                    }, 300)
                }
            }
            else
            {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Please Input Account Type.'
                });  

                setTimeout(function(){
                    $('#group_accounttype').focus();
                }, 300)
            }
        });

        $(document).on('click', '.group_edit', function(){
            var dataid = $(this).closest('tr').attr('data-id');

            $.ajax({
                url: '{{route('coa_acctype_read')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    dataid:dataid
                },
                success:function(data)
                {
                    $('#group_accounttype').val(data.group);
                    $('#group_class').val(data.classid).change();
                    $('#group_sort').val(data.sortid);
                    $('#coa_groupsave').attr('data-id', dataid);
                    $('#coa_groupsave').attr('data-action', 'update');
                    $('#modal-groups').modal('show');
                }
            });
        });

        $(document).on('click', '.group_remove', function(){
            var dataid = $(this).closest('tr').attr('data-id');

            Swal.fire({
                title: 'Delete Account Type?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        url: '{{route('coa_acctype_delete')}}',
                        type: 'GET',
                        // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                        data: {
                            dataid:dataid
                        },
                        success:function(data)
                        {
                            if(data == 'done')
                            {
                                coa_group();
                                coa_view();
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    onOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                  type: 'success',
                                  title: 'Account type has been deleted'
                                });  
                            }
                            else
                            {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    onOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                  type: 'error',
                                  title: 'Something went wrong'
                                });     
                            }
                        }
                    });
                }
            })


            
            
        });
    });

  </script>
  
@endsection