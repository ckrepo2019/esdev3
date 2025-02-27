<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form> 
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">@sm</span>
                            </div>
                            <input type="text" class="form-control">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <div id="calendarModal" class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div> --}}
                <div class="modal-body">
                    
                    <div class="position-relative form-group">
                    <label for="eventDate" class="">Date</label>
                    <input disabled name="eventDate" id="eventDate" type="date" class="form-control">
                    </div>
                    <div class="position-relative form-group">
                        <label for="eventTitle" class="">Event Title</label>
                        <input disabled name="eventTitle" id="eventTitle" placeholder="Event Title" class="form-control">
                    </div>
                    <div class="position-relative form-group">
                        <label  for="eventType" class="">Event Type</label>
                        <input disabled name="eventTitle" id="eventType" placeholder="Event Type" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closemodal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
