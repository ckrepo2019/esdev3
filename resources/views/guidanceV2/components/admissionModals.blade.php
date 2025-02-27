<!-- MODAL ADD COURSE SETUP -->
<div class="modal fade" id="modalAddCourse">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title "> New Passing Rate Setup </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">

                        <div class="form-group col-md-12">
                            <label class="mb-1">Description</label>
                            <input type="text" class="form-control" id="description" placeholder="">
                            <span class="invalid-feedback" role="alert">
                                <strong>Description is required!</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="mb-1">Please choose one or multiple grade levels.</label>
                            <select class="form-control" id="select-level" style="width: 100%;">
                                @foreach (DB::table('gradelevel')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->levelname }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong>Grade Level is required!</strong>
                            </span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="" class="mb-1">JHS GWA %</label>
                            <input type="number" min="0" id="jhs_percent" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="" class="mb-1">SHS GWA %</label>
                            <input type="number" min="0" id="shs_percent" class="form-control">
                        </div>


                        <label class="col-md-12"> Category of Test & Duration </label>
                        <div class="input-group col-md-12">
                            <input type="text" class="form-control" placeholder="Category Name" id="category_name">
                            <input type="number" class="form-control" placeholder="Hours" id="timelimit_hrs">
                            <input type="number" class="form-control" placeholder="Minutes" id="timelimit_min">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" id="btn_add_category">
                                    <i class="far fa-paper-plane mr-1"></i> Add Category
                                </button>
                            </div>
                        </div>

                        <div class="list_category">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer justify-content-start college_modal" hidden>
                <h5 class="modal-title "> Course Percentage Setup </h5>
            </div>
            <div style="border-bottom: 1px solid rgb(235, 233, 233)" hidden></div>
            <div class="modal-body college_modal" hidden>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="mb-1">Choose one or multiple courses.</label>
                        <select class="form-control" id="select-course" style="width: 100%;">
                            @foreach (DB::table('college_courses')->where('deleted', 0)->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->courseabrv }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" role="alert">
                            <strong>Course is required!</strong>
                        </span>
                    </div>
                    <div class="table-responsive col-md-12">
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr class="container_categoryhead">


                                </tr>
                            </thead>
                            <tbody id="tbl_listCoursePercentage">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary add_user" id="btn_save_course">Save</button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL ADD ENTRANCE EXAM SETUP -->
<div class="modal fade" id="modalAddExamDate">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">Add Examination Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="mb-1">School Year</label>
                            <select class="form-control form-control-sm select2" id="select-year"
                                style="width: 100%;">
                                @foreach (DB::table('sy')->where('ended', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong>School Year is required!</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-1">Academic Program</label>
                            <select class="form-control form-control-sm " id="acadprog" style="width: 100%;">
                                <option value="">Select Acad Prog</option>
                                @foreach (DB::table('academicprogram')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong>Acad Prog is required!</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-1">Select Exam</label>
                            <select class="form-control form-control-sm" id="select-exam" style="width: 100%;">
                                <option value="">Select Exam</option>
                                {{-- @foreach (DB::table('guidance_passing_rate')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->description }}</option>
                                @endforeach --}}
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong>Exam Setup is required!</strong>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Examination Date</label>
                                <input type="datetime-local" class="form-control form-control-sm "
                                    id="examinationdate" placeholder="">
                                <span class="invalid-feedback" role="alert">
                                    <strong>ExamDate is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Venue or Campus</label>
                                <input type="text" class="form-control form-control-sm " id="venue"
                                    onkeyup="this.value=this.value.toUpperCase();" placeholder="Enter Campus">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Venue is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">No. of Participants</label>
                                <input type="number" min="0" class="form-control form-control-sm "
                                    id="takers" placeholder="">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Participant is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Building</label>
                                <input type="text" class="form-control form-control-sm " id="building"
                                    onkeyup="this.value=this.value.toUpperCase();" placeholder="Enter Building">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Building is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Room</label>
                                <input type="text" class="form-control form-control-sm " id="room"
                                    onkeyup="this.value=this.value.toUpperCase();" placeholder="Enter Room">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Room is required!</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_save_examdate">Create</button>
            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="modalEditExamDate">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">Edit Examination Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <input type="text" id="idToUpdateExamDate" hidden>
                        <div class="form-group col-md-6">
                            <label class="mb-1">School Year</label>
                            <select class="form-control form-control-sm" id="edit-select-year" style="width: 100%;">
                                @foreach (DB::table('sy')->where('ended', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong>School Year is required!</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-1">Academic Program</label>
                            <select class="form-control form-control-sm" id="edit-acadprog" style="width: 100%;">
                                <option value="">Select Acad Prog</option>
                                @foreach (DB::table('academicprogram')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong>Acad Prog is required!</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-1">Select Exam</label>
                            <select class="form-control form-control-sm " id="edit_examid" style="width: 100%;">
                                <option value="">Select Exam</option>
                                @foreach (DB::table('guidance_passing_rate')->where('deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->description }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong>Exam Setup is required!</strong>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Examination Date</label>
                                <input type="datetime-local" class="form-control form-control-sm "
                                    id="edit-examinationdate" placeholder="">
                                <span class="invalid-feedback" role="alert">
                                    <strong>ExamDate is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Venue or Campus</label>
                                <input type="text" class="form-control form-control-sm " id="edit-venue"
                                    onkeyup="this.value=this.value.toUpperCase();" placeholder="Enter Campus">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Venue is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">No. of Participants</label>
                                <input type="number" min="0" class="form-control form-control-sm "
                                    id="edit-takers" placeholder="">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Participant is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Building</label>
                                <input type="text" class="form-control form-control-sm " id="edit-building"
                                    onkeyup="this.value=this.value.toUpperCase();" placeholder="Enter Building">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Building is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-1">Room</label>
                                <input type="text" class="form-control form-control-sm " id="edit-room"
                                    onkeyup="this.value=this.value.toUpperCase();" placeholder="Enter Room">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Room is required!</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_update_examdate">Update</button>
            </div>

        </div>

    </div>

</div>
