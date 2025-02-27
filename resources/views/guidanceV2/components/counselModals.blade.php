<!-- MODAL ADD APPOINT -->
<div class="modal fade" id="modalAddAppointment" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">Counseling Appointment Slip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="mb-1">Date</label>
                    <input type="date" class="form-control" id="filleddate" placeholder="">
                    <span class="invalid-feedback" role="alert">
                        <strong>Date is required!</strong>
                    </span>
                </div>
                <div class="form-group">
                    <label class="mb-1">Student Name</label>
                    {{-- <input type="text" class="form-control" id="studname" placeholder=""> --}}
                    <select class="form-control" id="select-student" style="width: 100%;">
                        @foreach (DB::table('studinfo')->where('studisactive', 1)->get() as $item)
                            <option value="{{ $item->id }}">{{ $item->lastname }} {{ $item->firstname }} </option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong>Name is required!</strong>
                    </span>
                </div>
                <div class="form-group">
                    <label class="mb-1">Reason</label>
                    <textarea class="form-control" id="reason"></textarea>
                    <span class="invalid-feedback" role="alert">
                        <strong>Reason is required!</strong>
                    </span>
                </div>
                <div class="form-group">
                    <label class="mb-1">Apointment Date</label>
                    <input type="datetime-local" class="form-control" id="counselingdate" placeholder="">
                    <span class="invalid-feedback" role="alert">
                        <strong>Appointment Date is required!</strong>
                    </span>
                </div>
                <div class="form-group">
                    <label class="mb-1">Counselor</label>
                    <select class="form-control" id="select-counselor" style="width: 100%;">
                        @foreach (DB::table('teacher')->where('usertypeid', 45)->get() as $item)
                            <option value="{{ $item->id }}">{{ $item->lastname }} {{ $item->firstname }} </option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong>Counselor is required!</strong>
                    </span>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_save_appointment">Submit</button>
            </div>
        </div>
    </div>
</div>
