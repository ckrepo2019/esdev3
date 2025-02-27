@extends('admission.layouts.app2')

@section('pagespecificscripts')
    <script src='https://meet.jit.si/external_api.js'></script>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card container-fluid bg-primary">
                        <div class="card-body text-center ">
                            <b>Welcome to Admission's Portal</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <div class="content">
        @if (DB::table('schoolinfo')->first()->admission == 1)
            <div class="container-fluid">
                <div class="card shadow">
                    <div class="card-header p-0 border-bottom-0">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-layer-group mr-1"></i>
                                ADMISSION RESULTS
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-valign-middle" id="tbl_results"
                                style="width: 100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Exam Date</th>
                                        <th>Applicant</th>
                                        <th>Score</th>
                                        <th>Desired Course</th>
                                        <th>Fitted Course</th>
                                        <th>Final Course</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="container-fluid">
                <div class="card shadow">
                    <div class="card-header p-0 border-bottom-0">
                        <div class="card-header">
                            <h5 class="card-title text-danger">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                ADMISSION IS DISABLED IN THIS SCHOOL
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('footerjavascript')
    <script>
        $('body').addClass('sidebar-collapse')
        $(document).ready(function() {
            admissionGetAllResults()
        })

        function admissionGetAllResults() {
            $.ajax({
                type: 'GET',
                url: '/admission/getallresults',
                success: function(respo) {
                    console.log(respo);
                    load_results(respo.result)
                },
                error: function(xhr, status, error) {
                    // Handle error response from the server if needed
                    console.error('Error updating server:', error);
                }
            })
        }

        function load_results22(data) {
            $('#tbl_results').DataTable({
                destroy: true,
                data: data,
                columns: [{
                        data: "formatted_examdate"
                    },
                    {
                        data: "studname",
                        render: (type, data, row) =>
                            `<span style="font-weight: 600;"> ${ row.studname || 'Not Specified'} </span>`
                    },
                    {
                        data: 'totalScore',
                        render: (type, data, row) =>
                            `<span class="${row.passedOverall ? "text-success" : "text-danger"}" style="font-weight: 600;">${row.totalScore}% (${row.passedOverall ? "Passed" : "Failed"})</span>`
                    },
                    {
                        data: 'courseabrv',
                        render: (data, type, row) =>
                            `<span> <strong> ${row.courseabrv} </strong> - ${row.courseDesc} </span>`
                    },
                    {
                        data: 'fitted_course',
                        render: (data, type, row) =>
                            row.fitted_course_id ?
                            `<span> <strong class="text-success"> ${row.fitted_courseAbrv} </strong> - ${row.fitted_courseDesc} </span>` :
                            `<span> Not Applicable </span>`
                    },
                    {
                        data: 'final_courseDesc',
                        render: (data, type, row) =>
                            data ?
                            `<span class="text-purple font-weight-bold"> ${data} </span>` :
                            '<span class="text-muted">Not Specified</span>'
                    },
                    {
                        data: 'status',
                        render: (data, type, row) => {
                            const status = {
                                2: '<span class="badge bg-success">Accepted</span>',
                                3: '<span class="badge bg-danger">Rejected</span>',
                                default: '<span class="badge bg-warning">Pending</span>'
                            };
                            return status[row.status] || status.default;
                        }
                    },
                    //   {
                    //       data: null,
                    //       className: 'text-center',
                    //       render: (data, type, row) => {
                    //           if (type !== 'display' || !Array.isArray(row.recommendedcourse)) return '';

                    //           const recommendedCourses = row.recommendedcourse.map(
                    //               (element) =>
                    //               `<a class="dropdown-item btn_accept" href="#" data-id="${row.id}" data-courseid="${element.id}"><strong>${element.courseabrv}</strong> - ${element.courseDesc}</a>`
                    //           ).join('');

                    //           return `
                //               <div class="btn-group">
                //                   <div class="input-group-append">
                //                       <button type="button" class="btn btn-default dropdown-toggle btn_custom_group" data-toggle="dropdown" aria-expanded="false" data-toggle="tooltip" title="Accept">
                //                           <i class="far fa-hand-pointer"></i>
                //                       </button>
                //                       <div class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                //                           <a class="dropdown-item" href="#"><strong>Recommended Courses</strong></a>
                //                           <div class="dropdown-divider"></div>
                //                           ${recommendedCourses}
                //                       </div>
                //                   </div>
                //                   <a data-toggle="tooltip" title="View Result" data-id="${row.id}" type="button" href="javascript:void(0)" class="btn btn-default btn_view btn_custom_group"> <i class="far fa-eye text-info"></i> </a>
                //                   <a data-toggle="tooltip" title="Retake" data-id="${row.id}" type="button" href="javascript:void(0)" class="btn btn-default btn_retake btn_custom_group"> <i class="fas fa-sync text-primary"></i> </a>     
                //                   <a data-toggle="tooltip" data-id="${row.id}" title="Decline" type="button" href="javascript:void(0)" class="btn btn-default btn_decline btn_custom_group"> <i class="fas fa-times text-danger"></i> </a>
                //               </div>`;
                    //       }
                    //   }

                ]
            })
        }

        function load_results(data) {
            $('#tbl_results').DataTable({
                destroy: true,
                data: data,
                columns: [{
                        data: "formatted_examdate"
                    },
                    {
                        data: "studname",
                        render: (type, data, row) =>
                            `<span style="font-weight: 600;"> ${ row.studname || 'Not Specified'} </span>`
                    },
                    {
                        data: 'totalScore',
                        render: (type, data, row) =>
                            `<span class="${row.passedOverall ? "text-success" : "text-danger"}" style="font-weight: 600;">${row.totalScore}% (${row.passedOverall ? "Passed" : "Failed"})</span>`
                    },
                    {
                        data: 'courseabrv',
                        render: (data, type, row) =>
                            row.acadprog_id == 7 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                             <span class="badge badge-secondary"> Technical Vocational </span>` : row
                            .acadprog_id == 6 ?
                            `<span> ${row.courseabrv} </span>
                             <span class="badge badge-primary"> College </span>` : row
                            .acadprog_id == 5 ?
                            `<span> <strong> ${row.strandcode} </strong> - ${row.strandname} </span>  
                            <span class="badge badge-danger" > SHS Strand </span>` : row.acadprog_id == 4 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                             <span class="badge badge-secondary"> High School </span>` : row.acadprog_id == 3 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                             <span class="badge badge-secondary"> Elementary </span>` : row.acadprog_id == 2 ?
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>
                             <span class="badge badge-secondary"> Pre-School </span>` :
                            `<span class="text-muted"> <strong> Not Applicable </strong> </span>`
                    },
                    {
                        data: 'fitted_course',
                        render: (data, type, row) =>
                            row.fitted_course_id ?
                            `<span> ${row.fitted_courseAbrv} </span>` :
                            `<span class="text-muted"> Not Specified </span>`
                    },
                    {
                        data: 'final_courseabrv',
                        render: (data, type, row) =>
                            row.final_courseabrv && row.acadprog_id == 6 ?
                            `<button type="button" class="btn btn-outline-success btn-sm" title="${row.final_courseDesc}">${row.final_courseabrv}</button>` :
                            row.final_strandcode && row.acadprog_id == 5 ?
                            `<button type="button" class="btn btn-outline-success btn-sm" title="${row.final_strandname}">${row.final_strandcode}</button>` :
                            '<button type="button" class="btn btn-outline-secondary btn-sm" title="Not Specified">Not Specified</button>'
                    },
                    {
                        data: 'status',
                        render: (data, type, row) => {
                            const status = {
                                2: '<span class="badge bg-success">Accepted</span>',
                                3: '<span class="badge bg-danger">Rejected</span>',
                                default: '<span class="badge bg-warning">Pending</span>'
                            };
                            return status[row.status] || status.default;
                        }
                    },
                    // {
                    //     data: null,
                    //     className: 'text-center',
                    //     render: (data, type, row) => {
                    //         if (type !== 'display' || !Array.isArray(row.recommendedcourse)) return '';

                    //         const recommendedCourses = row.recommendedcourse.map(
                    //             (element) => element !== null && element !== '' ?
                    //             `<a class="dropdown-item btn_accept" href="#" data-id="${row.id}" data-courseid="${element.id}"><strong>${element.courseabrv}</strong> - ${element.courseDesc}</a>` :
                    //             ''
                    //         ).join('');

                    //         return `
                //             <div class="btn-group">
                //                 <div class="input-group-append">
                //                     <button type="button" class="btn btn-default dropdown-toggle btn_custom_group" data-toggle="dropdown" aria-expanded="false" data-toggle="tooltip" title="Accept">
                //                         <i class="far fa-hand-pointer"></i>
                //                     </button>
                //                     <div class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                //                         <a class="dropdown-item" href="#"><strong>Recommended Courses</strong></a>
                //                         <div class="dropdown-divider"></div>
                //                         ${recommendedCourses}
                //                     </div>
                //                 </div>
                //                 <a data-toggle="tooltip" title="View Result" data-id="${row.id}" type="button" href="javascript:void(0)" class="btn btn-default btn_view btn_custom_group"> <i class="far fa-eye text-info"></i> </a>
                //                 <a data-toggle="tooltip" title="Retake" data-id="${row.id}" type="button" href="javascript:void(0)" class="btn btn-default btn_retake btn_custom_group"> <i class="fas fa-sync text-primary"></i> </a>     
                //                 <a data-toggle="tooltip" data-id="${row.id}" title="Decline" type="button" href="javascript:void(0)" class="btn btn-default btn_decline btn_custom_group"> <i class="fas fa-times text-danger"></i> </a>
                //             </div>`;
                    //     }
                    // }

                ]
            })
        }
    </script>
@endsection
