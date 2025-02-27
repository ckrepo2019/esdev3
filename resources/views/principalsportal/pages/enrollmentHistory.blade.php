@extends('principalsportal.layouts.app')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <ul class="list-group">
                        <a href="#" class="list-group-item active">
                            <h5 class="list-group-item-heading">2018 - 2019</h5>
                            <p class="list-group-item-text">Grade 7 - St. Peter<br>Delson John A Balbuena</p>
                        </a>
                    @for ($i = 0; $i < 5; $i++)
                        <a href="#" class="list-group-item">
                            <h5 class="list-group-item-heading text-muted">2018 - 2019</h5>
                            <p class="list-group-item-text text-muted">Grade 7 - St. Peter<br>Delson John A Balbuena</p>
                        </a>
                    @endfor
                </ul>
            </div>
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="mb-0 table table-bordered">
                        <thead>
                            <tr>
                                <td rowspan="2" >SUBJECTS</td>
                                <td align="center" colspan="4" width="40%">PERIODIC RATINGS</td>
                                <td align="center" rowspan="2" width="10%">FINAL RATING</td>
                                <td align="center" rowspan="2" width="10%">ACTION TAKEN</td>

                            </tr>
                            <tr align="center">
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                            </tr>
                            @for ($i = 0; $i < 10; $i++)
                                <tr>
                                    <td >CHRISIAN LIVING</td>
                                    <td align="center">99</td>
                                    <td align="center">99</td>
                                    <td align="center">99</td>
                                    <td align="center">99</td>
                                    <td align="center">99</td>
                                    <td align="center">PASSED</td>
                                </tr>
                            @endfor
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>
@endsection
