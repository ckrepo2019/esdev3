<div class="modal fade" id="collegeModal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">COLLEGE FORM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <form method="GET" action="{{Request::url() == url('/colleges')?route('college.create'):route('college.update', [Str::slug($collegeInfo[0]->collegeDesc)]) }}">
                        @csrf
                        <div class="modal-body">
                              <label>COLLEGE DESCRIPTION</label>
                              <input value="{{Request::url() == url('/colleges')?'':$collegeInfo[0]->collegeDesc }}" placeholder="COLLEGE DESCRIPTION" class="form-control" name="collegeDesc" onkeyup="this.value = this.value.toUpperCase();" id="collegeDesc">
                        </div>

                        <div class="modal-footer justify-content-between">
                              <button 
                              onClick="this.form.submit this.disabled=true;"
                              class="btn {{Request::url() == url('/colleges')?'btn-primary':'btn-success' }} ">
                              {{Request::url() == url('/colleges')?'CREATE':'UPDATE' }}
                              </button>
                        </div>
                  <form>
            </div>
      </div>
</div>