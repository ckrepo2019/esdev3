

@extends('layouts.app')

@section('content')
      <section class="content ">
            <div class="row justify-content-center">
                  <div class="col-md-6">
                        <div class="card">
                              <div class="card-header bg-danger">
                                    Pre-registration already exist.
                              </div>
                              <div class="card-body">
                                    <div class="callout callout-danger">
                                          <ul style="font-size:15px">

                                                <li>Visit <a href="/prereg/inquiry/form">{{Request::root()}}/prereginquiry</a> to view your pre-enrollment/pre-registration and payment status</li>
                                                
                                                <li>Upload payment receipt at <a href="/payment/online">{{Request::root()}}/payment/online</a>.</li>

                                                <li>Recover code at <a href="/coderecovery">{{Request::root()}}/coderecovery</a>.</li>

                                                <li>Return to login page <a href="/login">{{Request::root()}}/login</a>.</li>
                                          </ul>
                                    </div>
                              </div>
                        </div>
                  </div>
               
            </div>
      </section>

@endsection
      
            