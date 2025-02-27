
@extends('registrar.layouts.app')
@section('content')
<style>
    
    #modal-edit-view .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
    }

    #modal-edit-view .modal-dialog {
        position: fixed;
        margin: 0;
        width: 100%;
        height: 100%;
        padding: 0;
    }
    @media (min-width: 576px)
    {
        #modal-edit-view .modal-dialog {
            max-width:  unset !important;
            margin: unset !important;
        }
    }
    #modal-edit-view .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border: 2px solid #3c7dcf;
        border-radius: 0;
        box-shadow: none;
    }

    #modal-edit-view .modal-header {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 50px;
        padding: 10px;
        background: #6598d9;
        border: 0;
    }

    #modal-edit-view .modal-title {
        font-weight: 300;
        font-size: 2em;
        color: #fff;
        line-height: 30px;
    }

    #modal-edit-view .modal-body {
        position: absolute;
        top: 50px;
        bottom: 60px;
        width: 100%;
        font-weight: 300;
        overflow: auto;
        background-color: rgba(0,0,0,.0001) !important;
    }
    #modal-edit-view .modal-footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 60px;
        padding: 10px;
        background: #f1f3f5;
    }

</style>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-default" id="btn-import-grades"><i class="fa fa-share"></i> Import Grades</button>
    </div>
</div>
    
<div class="modal fade" id="modal-edit-view">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Import Grades</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" >
          <div class="row mb-2">
              <div class="col-md-4">
                  <label>Grade Level</label>
                  <select class="form-control">
                      @foreach(DB::table('gradelevel')->where('deleted','0')->orderBy('sortid','asc')->get() as $gradelevel)
                        <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-2">
                  <label>Download Template</label>
                  <br/>
                  <button type="button" class="btn btn-default btn-block"><i class="fa fa-file-excel"></i> Template</button>
              </div>
              <div class="col-md-6">
                <label>Upload CSV</label>
                <input type="file" class="form-control" accept=".csv"/>
              </div>
          </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary" id="btn-edit-submit" disabled>We're still working on this page!</button> --}}
        <button type="button" class="btn btn-primary" id="btn-edit-submit">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@endsection
@section('footerjavascript')
    <script>
        $(document).ready(function(){
            $('#btn-import-grades').on('click', function(){
                $('#modal-edit-view').modal('show');
            })
        })
    </script>
@endsection

                                        

                                        
                                        