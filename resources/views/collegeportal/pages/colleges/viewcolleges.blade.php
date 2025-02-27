
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')

@endsection

@section('content')
      @include('collegeportal.pages.forms.generalform')


    <div class="card">
            <div class="card-header">
                  <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#{{$modalInfo->modalName}}" title="Contacts" data-widget="chat-pane-toggle"><b>CREATE COLLEGE</b></button>
            </div>
            <div class="card-body">
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th>COLLEGE DESCRIPTION </th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach($colleges as $college)
                                    <tr>
                                          <td><a href="/colleges/{{Str::slug($college->collegeDesc, '-')}}">{{$college->collegeDesc}}</a></td>
                                    </tr>
                              @endforeach
                        </tbody>
                  </table>
          </div>
    </div>
    
@endsection

@section('footerscript')
      {{-- <script>
      $(document).ready(function(){
           
            $(document).on('click','.edit',function(){
                 
                  $('#collegeModal').modal('show')

                  // fetch('http://sppv2.ck/college/colleges/'+$(this).attr('id'))
                  //       .then(res => res.json())
                  //       .then((res) => { 
                  //             $('#collegeDesc').val(res[0].collegeDesc)
                  //             $('#collegeForm').attr('action','college/colleges/'+res[0].id)
                  //       })
                        

                  
                  // $('#collegForm').attr('action','college/colleges/'+$(this).attr('id'))
                  
            })
                  
         
      })
</script> --}}
@endsection

