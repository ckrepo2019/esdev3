
@extends('deanportal.layouts.app2')

@section('pagespecificscripts')

@endsection

@section('content')

      @include('collegeportal.pages.forms.generalform')  

      <section class="content">
            <div class="card">
                  <div class="card-header">
                        COURSES
                        <button class="btn btn-primary btn-sm float-right" data-toggle="modal"  data-target="#schedDetailModal" title="Contacts" data-widget="chat-pane-toggle"><b>CREATE SECTION</b></button>
                  </div>
                  <div class="card-body p-0">
                        <table class="table table-striped">
                              <thead>
                                    <tr>
                                          <td>SECTIONS</>
                                          <td>COURSE</>
                                    </tr>
                              </thead>
                              <tbody>
                                    @foreach ($sections as $item)
                                          <tr>
                                                <td><a href="#">{{$item->sectionDesc}}<a></td>
                                                      
                                          </tr>
                                    @endforeach
                              </tbody>
                        </table>
                  </div>
            </div>
      </section>
@endsection

@section('footerscript')

@endsection

