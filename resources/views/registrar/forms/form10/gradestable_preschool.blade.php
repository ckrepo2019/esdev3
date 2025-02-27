<div class="card mt-2">
    <div class="card-header">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label>School ID</label>
                <input type="text" class="form-control" id="input-schoolid" value="{{$schoolinfo->schoolid}}"/>
            </div>
            <div class="col-md-3 mb-2">
                <label>School Name</label>
                <input type="text" class="form-control" id="input-schoolname" value="{{$schoolinfo->schoolname}}"/>
            </div>
            <div class="col-md-3 mb-2">
                <label>Grade Level</label>
                <input type="text" class="form-control" id="input-levelname" value="{{$schoolinfo->levelname}}"/>
            </div>
            <div class="col-md-3 mb-2">
                <label>Section</label>
                <input type="text" class="form-control" id="input-section" value="{{$schoolinfo->sectionname}}"/>
            </div>
            <div class="col-md-9 mb-2">
                <label>Adviser</label>
                <input type="text" class="form-control" id="input-adviser" value="{{$schoolinfo->teachername}}"/>
            </div>
            <div class="col-md-3 mb-2 text-right">
                <label>&nbsp;</label><br/>
                <button type="button" class="btn btn-success" id="btn-submit-levelinfo"><i class="fa fa-share"></i> Save changes</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>Action Taken</th>
                        </tr>
                    </thead>
                    <tr style="background-color:#8dcf5f; font-weight: bold;">
                        <td style="padding-left:9px;">I. GROSS MOTOR</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Coordination of leg movements</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1B')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1B')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1B')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1B')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Coordination of arm movements</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1C')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1C')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1C')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1C')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Movement of body parts as instructed.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1D')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1D')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1D')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','1D')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr style="background-color:#8dcf5f; font-weight: bold;">
                        <td style="padding-left:9px;">II. FINE MOTOR</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Coordination in the use of fingers in picking objects</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2B')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2B')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2B')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2B')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Coordination of fingers for scribbling and drawing.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2C')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2C')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2C')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2C')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Display of definite hand preference (either left or right)</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2D')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2D')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2D')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','2D')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr style="background-color:#8dcf5f; font-weight: bold;">
                        <td style="padding-left:9px;">IV. RECEPTIVE LANGUAGE</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Following instructions correctly.</td>
                       <td class="text-center">{{collect($checkGrades)->where('sort','3B')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3B')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3B')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3B')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Pointing family members correctly when ask to do so</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3C')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3C')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3C')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3C')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Pointing named objects correctly when ask to do so</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3D')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3D')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3D')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','3D')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr style="background-color:#8dcf5f; font-weight: bold;">
                        <td style="padding-left:9px;">V. EXPRESSIVE LANGUAGE</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Using recognizable words correctly.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4B')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4B')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4B')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4B')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Naming objects and pictures correctly</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4C')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4C')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4C')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4C')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Asking questions appropriately (who, what, when, why, how?)</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4D')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4D')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4D')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4D')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Telling account of recent experiences.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4E')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4E')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4E')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','4E')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr style="background-color:#8dcf5f; font-weight: bold;">
                        <td style="padding-left:9px;">VI. COGNITIVE DEVELOPMENT</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr style="background-color:#cbedaf; font-weight: bold;">
                        <td style="padding-left:9px;">A. WRITING READINESS</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;"> Exhibition of left to right progression.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA2')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA2')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA2')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA2')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Writing name correctly</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA3')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA3')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA3')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA3')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;"> Writing upper case or lower case letters from memory</td>
                         <td class="text-center">{{collect($checkGrades)->where('sort','FA4')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA4')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA4')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA4')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;"> Correctly copying shapes</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA5')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA5')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA5')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FA5')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr style="background-color:#cbedaf; font-weight: bold;">
                        <td style="padding-left:9px;">B. READING READINESS</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Correct Identification of objects and pictures</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB2')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB2')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB2')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB2')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Correctly identifies similarities and differences of objects and pictures</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB3')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB3')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB3')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB3')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Correct identification of upper or lower case letters from memory </td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB4')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB4')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB4')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB4')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Correctly matching objects or pictures with the alphabet</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB5')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB5')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB5')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB5')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Correctly sorting out pictures, alphabet, or shapes</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB6')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB6')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB6')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB6')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Correctly following signs and symbols</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB7')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB7')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB7')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FB7')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                   
                    <tr style="background-color:#cbedaf; font-weight: bold;">
                        <td style="padding-left:9px;">C. LANGUAGE</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Listening attentively to someone who speaks.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC2')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC2')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC2')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC2')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;"> Correctly distinguish different type of sounds.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC3')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC3')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC3')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC3')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Responding correctly to different type of sounds.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC4')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC4')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC4')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC4')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Recalling significant facts in a story.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC5')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC5')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC5')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC5')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Expressing own thoughts, feelings and ideas.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC6')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC6')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC6')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC6')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Exhibiting comprehension of learned concepts.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC7')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC7')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC7')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC7')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Responding correctly to questions asked.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC8')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC8')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC8')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC8')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Reciting poems and verses.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC9')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC9')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC9')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FC9')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Reciting correctly numbers 1 to 10.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD2')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD2')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD2')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD2')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;"> Writing numerals 1 to 10.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD3')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD3')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD3')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD3')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Identifying correctly the number of animals, objects, or pictures</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD4')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD4')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD4')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD4')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Correct identification of shapes </td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD5')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD5')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD5')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD5')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Showing understanding on the concept of length, mass, volume/capacity</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD6')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD6')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD6')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD6')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Exhibiting interests and curiosity about the environment</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD7')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD7')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD7')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD7')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Showing interests and curiosity about living organism.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD8')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD8')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD8')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FD8')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr style="background-color:#cbedaf; font-weight: bold;">
                        <td style="padding-left:9px;">E. MUSIC AND ARTS</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Participation in music and art related activities</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE2')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE2')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE2')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE2')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Skill in drawing, singing, dancing, and/or acting.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE3')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE3')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE3')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE3')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Exhibiting interests in music and rhythm</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE4')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE4')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE4')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE4')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">Exhibiting ideas and feelings through print or art media</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE5')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE5')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE5')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FE5')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr style="background-color:#8dcf5f; font-weight: bold;">
                        <td style="padding-left:9px;">VII. SOCIAL, EMOTIONAL AND SPIRITUAL DEVELOPMENT</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">1.Exhibiting concepts and feelings about self, family, school and community</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG2')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG2')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG2')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG2')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">2. Willingness to be with peers, adults and strangers.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG3')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG3')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG3')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG3')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">3. Demonstration of courtesy and respect</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG4')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG4')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG4')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG4')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">4. Correct identification of feelings of others</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG5')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG5')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG5')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG5')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">5. Showing cooperation in group situations.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG6')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG6')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG6')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG6')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-left:9px;">6. Expressing own feelings.</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG7')->first()->q1eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG7')->first()->q2eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG7')->first()->q3eval ?? ''}}</td>
                        <td class="text-center">{{collect($checkGrades)->where('sort','FG7')->first()->q4eval ?? ''}}</td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
