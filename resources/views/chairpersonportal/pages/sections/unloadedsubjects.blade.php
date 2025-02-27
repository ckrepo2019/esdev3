@if(count($unloadedSubjects) > 0)
      <div class="col-md-6">
            <div class="card">
                  <div class="card-header bg-success">
                        <h4 class="card-title">Unloaded Subjects</h4>
                  </div>
                  <div class="card-body p-0">
                        <table class="table table-sm ">
                              <thead>
                                    <tr>
                                          <td colspan="2" class="pt-4 pb-4 text-info">
                                                <em>
                                                Subjects listed here are the subjects that are added in the prospectus after this section is created. Click the add subject button to add the subject in this section.</em>
                                          </td>
                                    </tr>
                                    <tr>
                                          <th width="75%">Subject Name</th>
                                          <th width="25%"></th>
                                    </tr>
                              </thead>
                              <tbody>
                                    @foreach ($unloadedSubjects as $item)
                                          <tr>
                                                <td class="align-middle">{{$item->subjDesc}}</td>
                                                <td><button class="btn btn-success btn-sm load_subject" data-id="{{$item->id}}">Add Subject</button></td>
                                          </tr>
                                    @endforeach
                              </tbody>
                        </table>
                        
                  </div>
            </div>
      </div>
@else
      <div class="col-md-6">
            <div class="card">
                  <div class="card-header bg-success p-1">
                  </div>
                  <div class="card-body ">
                        <h5>Unloaded Subjects</h5>
                        <p class="mb-0 text-info"><em>All subjects from prospectus are already added to this section.</em></p>
                  </div>
            </div>
      </div>


@endif