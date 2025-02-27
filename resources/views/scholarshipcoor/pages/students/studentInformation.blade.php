<div class="row">
      <h5 for="">Student Name</h5>
</div>
<div class="row">
      <div class="col-md-4">
            <div class="form-group">
                  <label for="">First Name</label>
                  <input type="text" class="form-control" value="{{$student->firstname}}" disabled>
            </div>
      </div>
      <div class="col-md-4">
            <div class="form-group">
                  <label for="">Middle Name</label>
                  <input type="text" class="form-control" value="{{$student->lastname}}" disabled>
            </div>
      </div>
      <div class="col-md-4">
            <div class="form-group">
                  <label for="">Last Name</label>
                  <input type="text" class="form-control" value="{{$student->firstname}}" disabled>
            </div>
      </div>
</div>
<div class="row">
      <h5 for="">Address</h5>
     

</div>
<div class="row">
      <div class="col-md-10">
            <div class="form-group">
                  <label for="">Street</label>
                  <input type="text" class="form-control" value="{{$student->firstname}}" disabled>
            </div>
      </div>
</div>
<div class="row">
      <div class="col-md-4">
            <div class="form-group">
                  <label for="">Barangay</label>
                  <select name="" id="">
                        @foreach (DB::table('') as $item)
                            
                        @endforeach
                  </select>
            </div>
      </div>
      <div class="col-md-4">
            <div class="form-group">
                  <label for="">City</label>
                  <input type="text" class="form-control" value="{{$student->firstname}}" disabled>
            </div>
      </div>
      <div class="col-md-4">
            <div class="form-group">
                  <label for="">Province</label>
                  <input type="text" class="form-control" value="{{$student->firstname}}" disabled>
            </div>
      </div>

</div>