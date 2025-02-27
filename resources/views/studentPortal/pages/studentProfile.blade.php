@extends('studentPortal.layouts.app')

@section('content')

<div class="mb-3 card">
    <div class="card-header card-header-tab-animation">
        <ul class="nav nav-justified">
            <li class="nav-item"><a data-toggle="tab" href="#tab-eg115-0" class="nav-link show active">Student Information</a></li>
            <li class="nav-item"><a data-toggle="tab" href="#tab-eg115-1" class="nav-link show">Contact Information</a></li>
            <li class="nav-item"><a data-toggle="tab" href="#tab-eg115-2" class="nav-link show">Login Information</a></li>
            
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane show active" id="tab-eg115-0" role="tabpanel">
        
                    <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">ID Number</label>
                        <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="2011100088" type="email" class="form-control"></div>
                    </div>
                    <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="JOHN MARKT ABISO" type="email" class="form-control"></div>
                    </div>
                    <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Gender</label>
                        <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="Male" type="email" class="form-control"></div>
                    </div>
            </div>
            <div class="tab-pane " id="tab-eg115-1" role="tabpanel">
                <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Fathers Name</label>
                    <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="Nancy D. Mitchel" type="email" class="form-control"></div>
                </div>
                <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Contact Number</label>
                    <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="561-835-1963" type="email" class="form-control"></div>
                </div>
                <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Mothers Name</label>
                    <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="Thomas J. Vines" type="email" class="form-control"></div>
                </div>
                <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Contact Number</label>
                    <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="916-901-1673" type="email" class="form-control"></div>
                </div>
                <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Guardian</label>
                    <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="Susie Z. McKillip" type="email" class="form-control"></div>
                </div>
                <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Contact Number</label>
                    <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="605-797-7588" type="email" class="form-control"></div>
                </div>
            </div>
            <div class="tab-pane " id="tab-eg115-2" role="tabpanel">
                <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">User Name</label>
                    <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="Againgly1972
                        " type="email" class="form-control"></div>
                </div>
                <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10"><input disabled name="email" id="exampleEmail" value="Ahci8muer" type="password" class="form-control"></div>
                </div>
                
            </div>
            
    
        </div>
    </div>
</div>
@endsection
