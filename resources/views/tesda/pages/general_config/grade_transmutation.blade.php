@extends('tesda.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@php
    // $user = auth()->user()->id;
    // $type = auth()->user()->type;
    // if ($type != 3) {
    //     $collegeid = DB::table('teacher')
    //         ->join('teacherdean', 'teacherdean.teacherid', '=', 'teacher.id')
    //         ->where('teacher.userid', $user)
    //         ->where('teacher.deleted', 0)
    //         ->where('teacherdean.deleted', 0)
    //         ->pluck('teacherdean.collegeid')
    //         ->toArray();
    //     $course = DB::table('college_courses')->where('deleted', 0)->whereIn('collegeid', $collegeid)->get();
    // } else {
    //     $course = DB::table('college_courses')->where('deleted', 0)->get();
    // }

    // $terms = DB::table('college_gradingsetupecr')->where('deleted', 0)->get();
    $terms = DB::table('college_termgrading')->where('deleted', 0)->get();
    $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
    $semester = DB::table('semester')->get();
    $gradelevel = DB::table('gradelevel')->where('deleted', 0)->where('acadprogid', 6)->orderBy('sortid')->get();
    $course = DB::table('college_courses')->get();
    $schoolinfo = DB::table('schoolinfo')->value('abbreviation');
    // $StudentSection = DB::table('college_sections')->where('deleted', 0)->get();
    $studStatus = DB::table('studentstatus')->get();

    $teacher = DB::table('teacher')
        ->where('userid', auth()->user()->id)
        ->first();

    $courses = DB::table('teacherdean')
        ->where('teacherdean.deleted', 0)
        ->where('college_colleges.deleted', 0)
        ->where('college_courses.deleted', 0)
        ->where('teacherid', $teacher->id)
        ->join('college_colleges', function ($join) {
            $join->on('teacherdean.collegeid', '=', 'college_colleges.id')->where('college_colleges.deleted', 0);
        })
        ->join('college_courses', function ($join) {
            $join->on('college_colleges.id', '=', 'college_courses.collegeid')->where('college_courses.deleted', 0);
        })
        ->select('college_courses.*')
        ->get();

    $colleges = DB::table('college_colleges')->where('cisactive', 1)->where('deleted', 0)->get();

    $college_gradelevel = DB::table('gradelevel')->where('acadprogid', 6)->where('deleted', 0)->get();
@endphp

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2">
                <div class="">
                    <h1><i class="fa fa-cog"></i> Grading Transmutation Table</h1>
                </div>
                <div class="ml-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Grading Equivalence Table</li>
                    </ol>
                </div>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <ul class="nav nav-tabs" id="studentInfoTabs" role="tablist">

                <li class="nav-item mr-3" role="presentation">
                    <a class="nav-link active" href="{{ url('tesda/gradeTransmutation') }}" style="color: black;">Grading
                        Transmutation
                        Table</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ url('tesda/setup/gradetransmutation/index') }}">Grade
                        Point Equivalency</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box shadow">
                                <div class="info-box-content">
                                    <div><i class="fa fa-filter"></i> Filter</div>
                                    <div class="row pb-2 d-flex">
                                        <div class="col-md-2 col-sm-12 ml-3">
                                            <label for="" class="mb-0">Courses</label>
                                            <select class="form-control form-control-sm select2 course" id="course"
                                                style="width: 100%;">
                                                <option value="">All</option>

                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <label for="" class="mb-0">Courses Type</label>
                                            <select class="form-control form-control-sm select2 academic" id="academic"
                                                style="width: 100%;">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header  bg-secondary">
                            <h3 class="card-title fw-1000">Grading Transmutation Table</h3>

                        </div>

                        <div class="card-body p-2 ">
                            <br>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="">
                                    <button class="btn btn-success btn-sm" id="addGradePointEquivalency"
                                        data-toggle="modal">Add Grading Transmutation Table</button>
                                </div>
                                {{-- <div class="input-group" style="width: 200px;">
                                    <input type="text" class="form-control form-control-sm" placeholder="Search">
                                    <button class="btn btn-outline-secondary btn-sm" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div> --}}
                            </div>

                            <table class="table table-bordered table-hover" id="gradePointEquivalencyTable"
                                style="font-size:12px; width: 100%;">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col">
                                            <div class="d-flex justify-content-center">
                                                <span class="badge bg-success" style="font-size: 13px;">Active</span>
                                                <span class="badge bg-danger ml-2" style="font-size: 13px;">Inactive</span>
                                            </div>
                                        </th> --}}
                                        <th scope="col">Table Description</th>
                                        {{-- <th scope="col">Term Applied</th> --}}
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="gradePointEquivalencyAddModal" tabindex="-1" aria-labelledby="addECRModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addECRModalLabel"><strong>New Grading Transmutation Table</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalBtn">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <!-- Form Content -->
                    <form id="gradePointEquivalencyForm">
                        <!-- Grade Point Equivalency Description Field -->
                        <div class="row mb-3">
                            <label for="gradePointEquivalencyDescription" class="form-label mt-2 col-md-6"
                                style="font-size: 14px;">Grading Description</label>

                            <input type="text" class="form-control form-control-sm col-md-9 ml-2"
                                id="gradePointEquivalencyDescription" placeholder="Enter Grading Description" required>

                        </div>

                        <!-- Add Grading Components Button -->
                        <button type="button" id="addGradingComponentsButton" class="btn btn-success mb-3 btn-sm"
                            data-bs-toggle="modal">
                            Add Grading Transmutation
                        </button>
                        <!-- Grading Components Table -->
                        <div class="table-responsive">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered" id="gradingPointsTable" style="font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th>Initial Grade</th>
                                            {{-- <th>Letter Gading System</th> --}}
                                            <th>Transmutated Grade</th>
                                            {{-- <th>Adjectival Description</th> --}}
                                            <th colspan="2" style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="default-row">
                                            <td>100</td>
                                            {{-- <td>A+</td> --}}
                                            <td>100</td>
                                            {{-- <td>Excellent</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>98.40 - 99.99</td>
                                            {{-- <td>A</td> --}}
                                            <td>99</td>
                                            {{-- <td>Very Good</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>96.80 - 98.39</td>
                                            {{-- <td>A-</td> --}}
                                            <td>98</td>
                                            {{-- <td>Very Good</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>95.20 - 96.79</td>
                                            {{-- <td>B+</td> --}}
                                            <td>97</td>
                                            {{-- <td>Good</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>93.60 - 95.19</td>
                                            {{-- <td>B</td> --}}
                                            <td>96</td>
                                            {{-- <td>Good</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>92.00 - 93.59</td>
                                            {{-- <td>B-</td> --}}
                                            <td>95</td>
                                            {{-- <td>Good</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>90.40 - 91.99</td>
                                            {{-- <td>B-</td> --}}
                                            <td>94</td>
                                            {{-- <td>Fair</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>88.80 - 90.39</td>
                                            {{-- <td>C</td> --}}
                                            <td>93</td>
                                            {{-- <td>Fair</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>87.20 - 88.79</td>
                                            {{-- <td>C-</td> --}}
                                            <td>92</td>
                                            {{-- <td>Pass</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>85.60 - 87.19</td>
                                            {{-- <td>-</td> --}}
                                            <td>91</td>
                                            {{-- <td>Conditional</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>84.00 - 85.59</td>
                                            {{-- <td>F</td> --}}
                                            <td>90</td>
                                            {{-- <td>Fail</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>82.40 - 83.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>89</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>80.80 - 82.39</td>
                                            {{-- <td>-</td> --}}
                                            <td>88</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>79.20 - 80.79</td>
                                            {{-- <td>-</td> --}}
                                            <td>87</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>77.60 - 79.19</td>
                                            {{-- <td>-</td> --}}
                                            <td>86</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>76.00 - 77.59</td>
                                            {{-- <td>-</td> --}}
                                            <td>85</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>74.40 - 75.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>84</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>72.80 - 74.39</td>
                                            {{-- <td>-</td> --}}
                                            <td>83</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>71.20 - 72.79</td>
                                            {{-- <td>-</td> --}}
                                            <td>82</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>69.60 - 71.19</td>
                                            {{-- <td>-</td> --}}
                                            <td>81</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>

                                        <tr class="default-row">
                                            <td>68.00 - 69.59</td>
                                            {{-- <td>-</td> --}}
                                            <td>80</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>

                                        <tr class="default-row">
                                            <td>66.40 - 67.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>79</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>64.80 - 66.39</td>
                                            {{-- <td>-</td> --}}
                                            <td>78</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>63.20 - 64.79</td>
                                            {{-- <td>-</td> --}}
                                            <td>77</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>61.60 - 63.19</td>
                                            {{-- <td>-</td> --}}
                                            <td>76</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>60.00 - 61.59</td>
                                            {{-- <td>-</td> --}}
                                            <td>75</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>56.00 - 59.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>74</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>52.00 - 55.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>73</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>48.00 - 51.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>72</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>44.00 - 47.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>71</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>40.00 - 43.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>70</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>36.00 - 39.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>69</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>

                                        <tr class="default-row">
                                            <td>32.00 - 35.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>68</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>

                                        <tr class="default-row">
                                            <td>28.00 - 31.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>67</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>24.00 - 27.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>66</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>

                                        <tr class="default-row">
                                            <td>20.00 - 23.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>65</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>

                                        <tr class="default-row">
                                            <td>16.00 - 19.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>64</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>12.00 - 15.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>63</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>8.00 - 11.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>62</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>4.00 - 7.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>61</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>
                                        <tr class="default-row">
                                            <td>0.00 - 3.99</td>
                                            {{-- <td>-</td> --}}
                                            <td>60</td>
                                            {{-- <td>Incomplete</td> --}}
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 edit-row" id="edit-row"><i
                                                        class="far fa-edit text-primary"></i></a></td>
                                            <td><a href="javascript:void(0)"
                                                    class="text-center align-middle ml-2 remove-row"><i
                                                        class="far fa-trash-alt text-danger"></i></a></td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Modal Footer with Save Button -->
                {{-- <div class="modal-footer">
                <button type="submit" form="gradePointEquivalencyForm" id="createGradeEquivalencyBtn"
                    class="btn btn-success"><i class="fas fa-save fa-lg mr-1"></i> SAVE</button>
            </div> --}}
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="createGradeEquivalencyBtn">SAVE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="gradePointEquivalencyEditModal" tabindex="-1" aria-labelledby="addECRModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editECRModalLabel"><strong>Add Grading Transmutation</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <!-- Form Content -->
                    <form id="gradePointEquivalencyForm">
                        <!-- Grade Point Equivalency Description Field -->
                        <div class="row mb-3">
                            <label for="gradePointEquivalencyDescription" class="form-label mt-2 col-md-3">Grade Point
                                Description</label>

                            <input type="text" class="form-control col-md-9" id="gradePointEquivalencyID"
                                placeholder="Enter Description" required hidden>

                            <input type="text" class="form-control col-md-9"
                                id="edit_gradePointEquivalencyDescription" placeholder="Enter Description" required>

                        </div>

                        <!-- Add Grading Components Button -->
                        <button type="button" id="addGradingComponentsButton_edit" class="btn btn-success mb-3"
                            data-bs-toggle="modal">
                            <i class="fas fa-list fa-lg"></i> + Add Grade Point Scale
                        </button>
                        <!-- Grading Components Table -->
                        <div class="table-responsive">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered" id="editgradingPointsTable" style="font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th>Grading Point Equivalence</th>
                                            {{-- <th>Letter Grade Equivalence</th> --}}
                                            <th>% Equivalence</th>
                                            {{-- <th>Remarks</th> --}}
                                            <th colspan="2" style="text-align: center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>






                    </form>
                </div>

                <!-- Modal Footer with Save Button -->
                {{-- <div class="modal-footer">
             <button type="submit" form="gradePointEquivalencyForm" id="createGradeEquivalencyBtn"
                 class="btn btn-success"><i class="fas fa-save fa-lg mr-1"></i> SAVE</button>
         </div> --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="updateGradeEquivalencyBtn"> UPDATE</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal -->
    <div class="modal fade" id="gradingComponentsModal" tabindex="-1" aria-labelledby="gradingComponentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">New Grading Transmutation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Transmutated Grade</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" id="gradePointEquivalency"
                                    placeholder="94 - 100" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="percentEquivalency"
                                        placeholder="e.i 4.0"
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>


                    {{-- <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Letter Grade Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Remarks</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" id="letterGradeEquivalency"
                                    placeholder="A+" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="gradingRemarks"
                                        placeholder="Grading Remarks" required>

                                </div>
                            </div>
                        </div>
                    </section> --}}

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="addGradeEquivalencyBtn">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gradingComponentsModal2" tabindex="-1" aria-labelledby="gradingComponentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Add Grading Transmutation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Transmutated Grade</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" id="gradePointEquivalency2"
                                    placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="percentEquivalency2"
                                        placeholder="94 - 100"
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>


                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="addGradeEquivalencyBtn2">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editgradingComponentsModal" tabindex="-1" aria-labelledby="gradingComponentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade Transmutation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Transmutated Grade</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" id="editgradePointEquivalency"
                                    placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editletterGradeEquivalency" placeholder="A+" required>
                                </div>
                            </div>
                        </div>
                    </section>


                    {{-- <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Letter Grade Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Remarks</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editletterGradeEquivalency" placeholder="A+" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="editgradingRemarks"
                                        placeholder="Grading Remarks" required>

                                </div>
                            </div>
                        </div>
                    </section> --}}

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="editGradeEquivalencyBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAppendgradingComponentsModal" tabindex="-1"
        aria-labelledby="gradingComponentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade Transmutation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Transmutated Grade</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editappendgradePointEquivalency" placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editappendletterGradeEquivalency" placeholder="A+" required>

                                </div>
                            </div>
                        </div>
                    </section>




                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="editappendGradeEquivalencyBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editgradingComponentsModal2" tabindex="-1"
        aria-labelledby="gradingComponentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade point Scale</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Transmutated Grade</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editgradePointEquivalency2" placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editletterGradeEquivalency2" placeholder="A+" required>
                                </div>
                            </div>
                        </div>
                    </section>


                    {{-- <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Letter Grade Equivalence</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Remarks</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editletterGradeEquivalency2" placeholder="A+" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="editgradingRemarks2"
                                        placeholder="Grading Remarks" required>

                                </div>
                            </div>
                        </div>
                    </section> --}}

                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="editGradeEquivalencyBtn2">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAppendgradingComponentsModal2" tabindex="-1"
        aria-labelledby="gradingComponentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradingComponentsModalLabel">Edit Grade Transmutation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Components Row -->
                    <section>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label for="gradingDescription" class="form-label">Initial Grade</label>
                            </div>
                            <div class="col-6">
                                <label for="componentPercentage" class="form-label">Transmutated Grade</label>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm"
                                    id="editappendgradePointEquivalency2" placeholder="e.i 4.0" required>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm"
                                        id="editappendletterGradeEquivalency2" placeholder="A+" required>
                                    <span class="input-group-text"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>




                    <!-- Add button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="editappendGradeEquivalencyBtn2">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">Term</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-left mb-3">
                        <button type="button" class="btn btn-primary btn-sm" id="addTermButton" data-toggle="modal"
                            data-target="#addTermModal">+ Add Term</button>
                    </div>

                    <table class="table table-bordered" id="termTable">
                        <thead>
                            <tr>
                                <th>Term</th>
                                <th>Term Frequency</th>
                                <th>Grading</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows with data will go here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Term Modal Structure -->
    <div class="modal fade" id="addTermModal" tabindex="-1" role="dialog" aria-labelledby="addTermModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTermModalLabel">Add New Term</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for Adding a New Term -->
                    <form id="addTermForm">
                        <div class="form-group">
                            <label for="termName">Term Description</label>
                            <input type="text" class="form-control" id="termName" placeholder="Term Description"
                                name="termName" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="select2Field" class="form-label">Term Frequency</label>
                                <select class="form-select select2" id="termFrequency" style="width: 100%;">
                                    <option value="">Select Term Frequency</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="formControl" class="form-label">Grading Percentage</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="formControl"
                                        placeholder="Enter value">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="addTermForm" class="btn btn-primary" id="saveTerm">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            $('#filter_sy').select2()
            $('#filter_semester').select2()
            $('#termApplied').select2()
            $('#edittermApplied').select2()
            $('#columnsInECR').select2();
            $('#subcolumnsInECR').select2();
            $('#termFrequency').select2();

            academic_select()

            function academic_select() {
                $.ajax({
                    type: 'GET',
                    url: '/college/get/yearlevel',
                    success: function(data) {
                        $.each(data, function(index, item) {
                            $('#academic').append(`
                    <option value="${item.id}">${item.levelname}</option>
                `);
                        });
                    }
                })
            }
            var table = $("#gradingPointsTable");
            table.find("tbody").on('click', '.remove-row', function() {
                $(this).closest('tr').hide();
            });

            var table = $("#editgradingPointsTable");
            table.find("tbody").on('click', '.delete_gradepoint_equivalency', function() {
                $(this).closest('tr').hide();
            });



            // $('#gradePointEquivalencyAddModal').on('hidden.bs.modal', function() {

            //     // $("#subject_filter").val("").change();
            //     var table = $("#gradingPointsTable");
            //     table.find("tbody").on('click', '.remove-append-row', function() {
            //         $(this).closest('tr').remove();
            //     });
            // });


            // $('#addGradingComponentsButton').on('click', function() {
            //     // event.preventDefault();
            //     var table = $("#gradingPointsTable");
            //     table.find("tbody").empty();


            // });

            $("#addGradeEquivalencyBtn2").click(function(e) {
                e.preventDefault();
                var gradePointEquivalency = $("#gradePointEquivalency2").val();
                var percentEquivalency = $("#percentEquivalency2").val();
                // var letterGradeEquivalency = $("#letterGradeEquivalency2").val();
                // var gradingRemarks = $("#gradingRemarks2").val();

                if (gradePointEquivalency.trim() == "" || percentEquivalency.trim() == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'All fields are required',
                    });
                    return;
                }

                var table = $("#editgradingPointsTable");
                // var row = $("<tr data-id='" + (table.find("tbody tr").length + 1) + "'>");
                var row = $("<tr class='appended-row'>");
                row.append($("<td>").text(gradePointEquivalency));
                // row.append($("<td>").text(letterGradeEquivalency));
                row.append($("<td>").text(percentEquivalency));
                // row.append($("<td>").text(gradingRemarks));
                row.append($("<td>").html(
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 edit-append-row2"><i class="far fa-edit text-primary"></i></a>'
                ));
                row.append($("<td>").html(

                    // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 remove-append-row"><i class="far fa-trash-alt text-danger"></i></a>'
                ));

                table.find("tbody").on('click', '.remove-append-row', function() {
                    $(this).closest('tr').remove();
                });
                table.find("tbody").append(row);

                Toast.fire({
                    type: 'success',
                    title: 'New grade point scale added successfully'
                })

                $("#gradePointEquivalency2").val("");
                $("#percentEquivalency2").val("");
                $("#letterGradeEquivalency2").val("");
                $("#gradingRemarks2").val("");

                let currentAppendRow2; // Variable to store the currently selected row

                $('.edit-append-row2').on('click', function() {
                    // Get the current row
                    currentAppendRow2 = $(this).closest('tr');

                    // Extract data from the row
                    const gradePointEquivalence = currentAppendRow2.find('td:eq(0)').text().trim();
                    const letterGradeEquivalence = currentAppendRow2.find('td:eq(1)').text().trim();
                    const percentEquivalence = currentAppendRow2.find('td:eq(2)').text().trim();
                    const remarks = currentAppendRow2.find('td:eq(3)').text().trim();

                    // Populate modal inputs
                    $('#editappendgradePointEquivalency2').val(gradePointEquivalence);
                    $('#editappendletterGradeEquivalency2').val(letterGradeEquivalence);
                    $('#editappendpercentEquivalency2').val(percentEquivalence);
                    $('#editappendgradingRemarks2').val(remarks);

                    // Show the modal
                    $('#editAppendgradingComponentsModal2').modal({
                        keyboard: false
                    }).modal('show');
                });

                $('#editappendGradeEquivalencyBtn2').on('click', function() {
                    if (currentAppendRow2) {
                        // Get updated values from modal inputs
                        const updatedGradePointEquivalence = $('#editappendgradePointEquivalency2')
                            .val().trim();
                        const updatedLetterGradeEquivalence = $(
                                '#editappendletterGradeEquivalency2')
                            .val()
                            .trim();
                        // const updatedPercentEquivalence = $('#editappendpercentEquivalency2').val()
                        //     .trim();
                        // const updatedRemarks = $('#editappendgradingRemarks2').val().trim();

                        // Update only the selected table row with new data
                        currentAppendRow2.find('td:eq(0)').text(updatedGradePointEquivalence);
                        currentAppendRow2.find('td:eq(1)').text(updatedLetterGradeEquivalence);
                        // currentAppendRow2.find('td:eq(2)').text(updatedPercentEquivalence);
                        // currentAppendRow2.find('td:eq(3)').text(updatedRemarks);
                    }
                    Toast.fire({
                        type: 'success',
                        title: 'Successfully updated'
                    })
                    $('#editAppendgradingComponentsModal2').modal('hide');
                });

            });



            // let currentAppendRow; // Variable to store the currently selected row

            // $('.edit-append-row').on('click', function() {
            //     // Get the current row
            //     console.log('NA tuplokan niiiiiiii')
            //     currentAppendRow = $(this).closest('tr')[0];

            //     // Extract data from the row
            //     const gradePointEquivalence = $(currentAppendRow).find('td:eq(0)').text().trim();
            //     const letterGradeEquivalence = $(currentAppendRow).find('td:eq(1)').text().trim();
            //     const percentEquivalence = $(currentAppendRow).find('td:eq(2)').text().trim();
            //     const remarks = $(currentAppendRow).find('td:eq(3)').text().trim();

            //     // Populate modal inputs
            //     $('#editappendgradePointEquivalency').val(gradePointEquivalence);
            //     $('#editappendletterGradeEquivalency').val(letterGradeEquivalence);
            //     $('#editappendpercentEquivalency').val(percentEquivalence);
            //     $('#editappendgradingRemarks').val(remarks);

            //     // Show the modal
            //     $('#editAppendgradingComponentsModal').modal({
            //         keyboard: false
            //     }).modal('show');
            // });


            $("#addGradeEquivalencyBtn").click(function(e) {
                e.preventDefault();
                var gradePointEquivalency = $("#gradePointEquivalency").val();
                var percentEquivalency = $("#percentEquivalency").val();
                // var letterGradeEquivalency = $("#letterGradeEquivalency").val();
                // var gradingRemarks = $("#gradingRemarks").val();

                if (gradePointEquivalency.trim() == "" || percentEquivalency.trim() == "") {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'All fields are required',
                    });
                    return;
                }

                var table = $("#gradingPointsTable");
                // var row = $("<tr data-id='" + (table.find("tbody tr").length + 1) + "'>");
                var row = $("<tr class='appended-row'>");
                row.append($("<td>").text(gradePointEquivalency));
                // row.append($("<td>").text(letterGradeEquivalency));
                row.append($("<td>").text(percentEquivalency));
                // row.append($("<td>").text(gradingRemarks));
                row.append($("<td>").html(

                    // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 edit-append-row"><i class="far fa-edit text-primary"></i></a>'
                ));
                row.append($("<td>").html(

                    // '<button type="button" class="btn btn-danger btn-xs remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                    '<a href="javascript:void(0)" class="text-center align-middle ml-2 remove-append-row"><i class="far fa-trash-alt text-danger"></i></button>'
                ));

                table.find("tbody").on('click', '.remove-append-row', function() {
                    $(this).closest('tr').remove();
                });
                table.find("tbody").append(row);

                Toast.fire({
                    type: 'success',
                    title: 'New grade point scale added successfully'
                })

                $("#gradePointEquivalency").val("");
                $("#percentEquivalency").val("");
                $("#letterGradeEquivalency").val("");
                $("#gradingRemarks").val("");

                let currentAppendRow; // Variable to store the currently selected row

                $('.edit-append-row').on('click', function() {
                    // Get the current row
                    currentAppendRow = $(this).closest('tr');

                    // Extract data from the row
                    const gradePointEquivalence = currentAppendRow.find('td:eq(0)').text().trim();
                    const letterGradeEquivalence = currentAppendRow.find('td:eq(1)').text().trim();
                    const percentEquivalence = currentAppendRow.find('td:eq(2)').text().trim();
                    const remarks = currentAppendRow.find('td:eq(3)').text().trim();

                    // Populate modal inputs
                    $('#editappendgradePointEquivalency').val(gradePointEquivalence);
                    $('#editappendletterGradeEquivalency').val(letterGradeEquivalence);
                    $('#editappendpercentEquivalency').val(percentEquivalence);
                    $('#editappendgradingRemarks').val(remarks);

                    // Show the modal
                    $('#editAppendgradingComponentsModal').modal({
                        keyboard: false
                    }).modal('show');
                });

                $('#editappendGradeEquivalencyBtn').on('click', function() {
                    if (currentAppendRow) {
                        // Get updated values from modal inputs
                        const updatedGradePointEquivalence = $('#editappendgradePointEquivalency')
                            .val().trim();
                        const updatedLetterGradeEquivalence = $('#editappendletterGradeEquivalency')
                            .val()
                            .trim();
                        const updatedPercentEquivalence = $('#editappendpercentEquivalency').val()
                            .trim();
                        const updatedRemarks = $('#editappendgradingRemarks').val().trim();

                        // Update only the selected table row with new data
                        currentAppendRow.find('td:eq(0)').text(updatedGradePointEquivalence);
                        currentAppendRow.find('td:eq(1)').text(updatedLetterGradeEquivalence);
                        currentAppendRow.find('td:eq(2)').text(updatedPercentEquivalence);
                        currentAppendRow.find('td:eq(3)').text(updatedRemarks);
                    }
                    Toast.fire({
                        type: 'success',
                        title: 'Successfully updated'
                    })
                    $('#editAppendgradingComponentsModal').modal('hide');
                });

            });


            $('#updateGradeEquivalencyBtn').on('click', function(event) {
                // event.preventDefault();
                update_pointEquivalencyscale()

            });


            function update_pointEquivalencyscale() {

                // var setActivePointEquivalency = $('#edit_input_Active').is(':checked') ? 1 : 0;

                var gradePointEquivalencyID = $('#gradePointEquivalencyID').val();

                var gradePointEquivalencyDescription = $('#edit_gradePointEquivalencyDescription').val();

                // var gradePointEquivalencyTerms = $('#edittermApplied').val();


                var tableData = [];
                $("#editgradingPointsTable").find("tbody tr").each(function() {
                    if ($(this).is(":visible")) {
                        var gradePointEquivalency = $(this).find("td:eq(0)").text().trim();
                        // var letterGradeEquivalency = $(this).find("td:eq(1)").text().trim();
                        var percentEquivalency = $(this).find("td:eq(1)").text().trim();
                        // var gradingRemarks = $(this).find("td:eq(3)").text().trim();

                        if (gradePointEquivalency && percentEquivalency) {
                            tableData.push({
                                gradePointEquivalency: gradePointEquivalency,
                                percentEquivalency: percentEquivalency
                            });
                        }
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '/tesda/setup/selectedgradetransmutation/update',
                    data: {
                        gradePointDesc: gradePointEquivalencyDescription,
                        gradePointScaleData: tableData,
                        gradePointEquivalencyID: gradePointEquivalencyID,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                            editgradingPointsTable(gradePointEquivalencyID)
                            gradePointEquivalencyTable()
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })

                            editgradingPointsTable(gradePointEquivalencyID)
                            gradePointEquivalencyTable()

                        } else if (data[0].status == 3) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })

                            editgradingPointsTable(gradePointEquivalencyID)
                            gradePointEquivalencyTable()

                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }



            $('#createGradeEquivalencyBtn').on('click', function(event) {
                // event.preventDefault();
                create_pointEquivalencyscale()

            });

            $('#closeModalBtn').on('click', function() {
                resetGradepointEquivalence();
            });

            function resetGradepointEquivalence() {

                // Check if there is any attendance data in local storage with this key prefix
                let hasData = true;

                if (hasData) {
                    // Confirm with the user before deleting all attendance data
                    Swal.fire({
                        // title: 'Create Grade Point Equivalency Reset',
                        text: 'You have unsaved changes. Would you like to save your work before leaving?',
                        type: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Save Changes', // Close modal on cancel
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Discard Changes',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            // Show success message after reset
                            // Swal.fire({
                            //     title: 'Reset Successful',
                            //     text: 'Grade Point Equivalency has been reset.',
                            //     type: 'success',
                            //     confirmButtonText: 'OK'
                            // }).then(() => {
                            //     // Close the modal after the alert is acknowledged
                            //     $('#gradePointEquivalencyAddModal').modal('hide');
                            // });

                        } else {
                            // Save attendance data

                            Toast.fire({
                                type: 'success',
                                title: 'Grade Point Equivalency has been created.',
                                confirmButtonText: 'OK'
                            })



                            $('#gradePointEquivalencyAddModal').modal({
                                keyboard: false
                            });
                            // var table = $("#gradingPointsTable");
                            // // table.find("tbody tr:not(.default-row)").remove();
                            // table.find("tbody tr:not(.default-row)").remove();
                            // table.find("tbody .default-row").show();
                            // $("#gradePointEquivalencyDescription").val("");
                            $('#createGradeEquivalencyBtn').click();

                            gradePointEquivalencyAddModal.show();
                            // Toast.fire({
                            //     type: 'success',
                            //     title: 'Grade Point Equivalency has been created.',
                            //     confirmButtonText: 'OK'
                            // })

                            // .then(() => {
                            //     // Close the modal after saving
                            //     $('#gradePointEquivalencyAddModal').modal('show');
                            // });
                        }
                    });
                } else {
                    // No attendance data found, close the modal automatically
                    $('#attendancemodal').modal('hide');
                }
            }


            function create_pointEquivalencyscale() {
                // var termApplied = $('#termApplied').val();
                // var setActivePointEquivalency = $('#input_Active').is(':checked') ? 1 : 0;
                var gradePointEquivalencyDescription = $('#gradePointEquivalencyDescription').val();

                var tableData = [];
                $("#gradingPointsTable").find("tbody tr").each(function() {
                    if ($(this).is(":visible")) {
                        var gradePointEquivalency = $(this).find("td:eq(0)").text().trim();
                        // var letterGradeEquivalency = $(this).find("td:eq(1)").text().trim();
                        var percentEquivalency = $(this).find("td:eq(1)").text().trim();
                        // var gradingRemarks = $(this).find("td:eq(3)").text().trim();

                        if (gradePointEquivalency && percentEquivalency) {
                            tableData.push({
                                gradePointEquivalency: gradePointEquivalency,
                                percentEquivalency: percentEquivalency
                            });
                        }
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '/tesda/setup/gradetransmutation/create',
                    data: {
                        gradePointDesc: gradePointEquivalencyDescription,
                        gradePointScaleData: tableData,
                        // termApplied: termApplied,
                        // setActive: setActivePointEquivalency

                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully created'
                            })

                            var table = $("#gradingPointsTable");
                            table.find("tbody .appended-row").remove();
                            gradePointEquivalencyTable()
                            $("#gradePointEquivalencyDescription").val("");

                            $("#gradePointEquivalencyAddModal").modal('hide');
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

            gradePointEquivalencyTable()

            function gradePointEquivalencyTable() {

                $("#gradePointEquivalencyTable").DataTable({
                    destroy: true,
                    // data:temp_subj,
                    // bInfo: true,
                    autoWidth: false,
                    // lengthChange: true,
                    // stateSave: true,
                    // serverSide: true,
                    // processing: true,
                    ajax: {
                        url: '/tesda/setup/gradetransmutation/fetch',
                        type: 'GET',
                        dataSrc: function(json) {
                            return json;
                        }
                    },
                    columns: [
                        // {
                        //     "data": "isactive"
                        // },
                        {
                            "data": "grade_transmutation_desc"
                        },
                        // {
                        //     "data": "terms"
                        // },
                        {
                            "data": null
                        },
                    ],
                    columnDefs: [

                        // {
                        //     'targets': 0,
                        //     'orderable': false,
                        //     'createdCell': function(td, cellData, rowData, row, col) {
                        //         // Define the icon
                        //         var checkIcon =
                        //             '<i class="fa fa-check bg-success checked-active p-2" data-toggle="tooltip" title="Active"></i>';

                        //         var wrongIcon =
                        //             '<i class="fa fa-times bg-danger checked-active p-2" data-toggle="tooltip" title="Inactive"></i>';

                        //         // Determine whether to display the icon
                        //         var iconHtml = rowData.isactive == 1 ? checkIcon : wrongIcon;

                        //         // Construct the HTML with the conditional icon only
                        //         var text = '<div style="text-align:center;">' + iconHtml + '</div>';

                        //         // Apply the constructed HTML and initialize tooltips
                        //         $(td).html(text).addClass('align-middle');
                        //         $(td).find('[data-toggle="tooltip"]').tooltip();
                        //     }
                        // },
                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.grade_transmutation_desc).addClass(
                                    'align-middle');
                            }
                        },

                        // {
                        //     'targets': 2,
                        //     'orderable': false,
                        //     'createdCell': function(td, cellData, rowData, row, col) {
                        //         // $(td).html('');
                        //         var html = '';
                        //         $.each(rowData.terms.split(','), function(i, term) {
                        //             html +=
                        //                 '<span style="font-size: 0.6rem;" class="badge badge-primary mr-1">' +
                        //                 term + '</span>';
                        //         });
                        //         $(td).html(html).addClass('align-middle');
                        //     }
                        // },


                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                // var buttons =
                                //     '<a href="javascript:void(0)" class="delete_subject" data-id="' +
                                //     rowData.id + '"><i class="far fa-trash-alt text-danger"></i></a>';
                                // $(td)[0].innerHTML = buttons
                                // $(td).addClass('text-center')
                                // $(td).addClass('align-middle')
                                var link =
                                    '<a href="#" style="color: #blue; text-decoration: underline; font-weight: bold;" class="view_gradepoint_equivalency mr-2" data-id="' +
                                    rowData.id +
                                    '"><i class="fas fa-pencil-alt"></i></a> ' +
                                    '<a href="#" style="color: red; text-decoration: underline; font-weight: bold;" class="delete_gradepoint_equivalency" data-id="' +
                                    rowData.id +
                                    '"><i class="fas fa-trash-alt"></i></a>';
                                $(td)[0].innerHTML = link;
                                $(td).addClass('text-center align-middle');
                            }
                        },

                    ],
                    // lengthMenu: [
                    //     [10, 25, 50, 100],
                    //     [10, 25, 50, 100]
                    // ],
                    // pageLength: 10,
                    // dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    //     "<'row'<'col-sm-12'tr>>" +
                    //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                });
            }


            $(document).on('click', '.view_gradepoint_equivalency', function() {

                var grade_transmutation_id = $(this).attr('data-id')
                console.log(grade_transmutation_id, 'grade transmutation idddd')
                $('#gradePointEquivalencyEditModal').modal()
                editgradingPointsTable(grade_transmutation_id)

                document.getElementById('addGradingComponentsButton_edit').addEventListener('click',
                    function() {
                        var gradingComponentsModal = new bootstrap.Modal(document.getElementById(
                            'gradingComponentsModal2'), {
                            keyboard: false
                        });
                        gradingComponentsModal.show();
                    });

                $.ajax({
                    type: 'GET',
                    url: '/tesda/setup/selectedgradetransmutation/fetch',
                    data: {
                        grade_transmutation_id: grade_transmutation_id
                    },
                    success: function(response) {
                        console.log(response);
                        var grade_transmutation = response.grade_transmutation;
                        var grade_transmutation_scale = response.grade_transmutation_scale;

                        $("#gradePointEquivalencyID").val(grade_transmutation
                            .grade_transmutation_id);
                        $("#edit_gradePointEquivalencyDescription").val(grade_transmutation
                            .grade_transmutation_desc);

                    }
                });

            });
            //     var point_equivalency_id = $(this).attr('data-id')
            //     console.log(point_equivalency_id, 'poinnnttttt equiiivalency idddd')
            //     $('#gradePointEquivalencyEditModal').modal()
            //     editgradingPointsTable(point_equivalency_id)

            //     document.getElementById('addGradingComponentsButton_edit').addEventListener('click',
            //         function() {
            //             var gradingComponentsModal = new bootstrap.Modal(document.getElementById(
            //                 'gradingComponentsModal2'), {
            //                 keyboard: false
            //             });
            //             gradingComponentsModal.show();
            //         });



            //     $.ajax({
            //         type: 'GET',
            //         url: '/setup/selectedgradepointequivalency/fetch',
            //         data: {
            //             point_equivalency_id: point_equivalency_id
            //         },
            //         success: function(data) {
            //             grade_point_scale = data;

            //             $("#gradePointEquivalencyID").val(grade_point_scale[0]
            //                 .grade_point_equivalency_id);


            //             $("#edit_gradePointEquivalencyDescription").val(grade_point_scale[0]
            //                 .grade_description);

            //             console.log('grade_point_scale terms', grade_point_scale[0]
            //                 .terms)

            //             const gradeDescription = grade_point_scale[0].terms.split(",");

            //             $("#edittermApplied").empty();

            //             gradeDescription.forEach(function(item) {
            //                 $("#edittermApplied").append(new Option(item, item));
            //             });

            //             $("#edittermApplied")
            //                 .val(gradeDescription)
            //                 .trigger('change')
            //                 .data('select2')
            //                 .$container
            //                 .addClass('bg-primary');



            //             // const gradeDescription = grade_point_scale[0].terms.split(",");

            //             // $("#edittermApplied").empty();

            //             // gradeDescription.forEach(function(item) {
            //             //     $("#edittermApplied").append(new Option(item, item));
            //             // });
            //             // $("#edittermApplied")
            //             //     .val(gradeDescription)
            //             //     .trigger('change');





            //             if (parseFloat(grade_point_scale[0].isactive) === 1) {
            //                 $('#edit_input_Active').prop('checked', true);
            //             } else if (parseFloat(grade_point_scale[0].isactive) === 0) {
            //                 $('#edit_input_Active').prop('checked', false);
            //             }


            //         }
            //     })




            // });

            function editgradingPointsTable(grade_transmutation_id) {

                $("#editgradingPointsTable").DataTable({
                    destroy: true,
                    autoWidth: false,
                    ajax: {
                        url: '/tesda/setup/selectedgradetransmutation/fetch',
                        type: 'GET',
                        data: {
                            grade_transmutation_id: grade_transmutation_id
                        },
                        dataSrc: function(json) {
                            // return json.grade_point_scale;
                            return json.grade_transmutation_scale;
                        }
                    },
                    columns: [{
                            "data": "initial_grade"
                        },
                        // {
                        //     "data": "letter_equivalence"
                        // },
                        {
                            "data": "transmutated_grade"
                        },
                        // {
                        //     "data": "grade_remarks"
                        // },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        }
                    ],
                    order: [
                        [2, 'asc']
                    ],
                    columnDefs: [{
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.grade_point).addClass('align-middle');
                            }
                        },
                        // {
                        //     'targets': 1,
                        //     'orderable': false,
                        //     'createdCell': function(td, cellData, rowData, row, col) {
                        //         $(td).html(rowData.letter_equivalence).addClass('align-middle');
                        //     }
                        // },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(rowData.percent_equivalence).addClass('align-middle');
                            }
                        },
                        // {
                        //     'targets': 3,
                        //     'orderable': false,
                        //     'createdCell': function(td, cellData, rowData, row, col) {
                        //         $(td).html(rowData.grade_remarks).addClass('align-middle');
                        //     }
                        // },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="edit_gradepoint_scale" id="edit_gradepoint_scale" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-edit text-primary"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');
                            }
                        },

                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var buttons =
                                    '<a href="javascript:void(0)" class="delete_gradepoint_equivalency" data-id="' +
                                    rowData.id +
                                    '"><i class="far fa-trash-alt text-danger"></i></a>';
                                $(td)[0].innerHTML = buttons;
                                $(td).addClass('text-center align-middle');
                            }
                        }
                    ]
                });
            }

            // function create_pointEquivalencyscale() {

            //     var gradePointEquivalencyDescription = $('#gradePointEquivalencyDescription').val();

            //     var tableData = [];
            //     $("#gradingPointsTable").find("tbody tr").each(function() {
            //         tableData.push({
            //             gradePointEquivalency: $(this).find("td:eq(0)").text(),
            //             letterGradeEquivalency: $(this).find("td:eq(1)").text(),
            //             percentEquivalency: $(this).find("td:eq(2)").text(),
            //             gradingRemarks: $(this).find("td:eq(3)").text()
            //         });
            //     });

            //     $.ajax({
            //         type: 'GET',
            //         url: '/setup/gradepointequivalency/create',
            //         data: {
            //             gradePointDesc: gradePointEquivalencyDescription,
            //             gradePointScaleData: tableData
            //         },
            //         success: function(data) {
            //             if (data[0].status == 2) {
            //                 Toast.fire({
            //                     type: 'warning',
            //                     title: data[0].message
            //                 })
            //             } else if (data[0].status == 1) {
            //                 Toast.fire({
            //                     type: 'success',
            //                     title: data[0].message
            //                 })


            //             } else {
            //                 Toast.fire({
            //                     type: 'error',
            //                     title: data[0].message
            //                 })
            //             }
            //         }
            //     })
            // }



            $('#addGradePointEquivalency').click(function() {
                $('#gradePointEquivalencyAddModal').modal({
                    keyboard: false
                });
                var table = $("#gradingPointsTable");
                // table.find("tbody tr:not(.default-row)").remove();
                table.find("tbody tr:not(.default-row)").remove();
                table.find("tbody .default-row").show();
                $("#gradePointEquivalencyDescription").val("");

                gradePointEquivalencyAddModal.show();
            });

            $('#addGradingComponentsButton').click(function() {
                var gradingComponentsModal = new bootstrap.Modal($('#gradingComponentsModal')[0], {
                    keyboard: false
                });
                gradingComponentsModal.show();
            });

            $('#edit-row').click(function() {
                var editgradingComponentsModal = new bootstrap.Modal($('#editgradingComponentsModal')[0], {
                    keyboard: false
                });
                editgradingComponentsModal.show();
            });


            let currentRow; // Variable to store the currently selected row

            $('.edit-row').on('click', function() {
                // Get the current row
                currentRow = $(this).closest('tr')[0];

                // Extract data from the row
                const gradePointEquivalence = $(currentRow).find('td:eq(0)').text().trim();
                const letterGradeEquivalence = $(currentRow).find('td:eq(1)').text().trim();
                const percentEquivalence = $(currentRow).find('td:eq(2)').text().trim();
                const remarks = $(currentRow).find('td:eq(3)').text().trim();

                // Populate modal inputs
                $('#editgradePointEquivalency').val(gradePointEquivalence);
                $('#editletterGradeEquivalency').val(letterGradeEquivalence);
                $('#editpercentEquivalency').val(percentEquivalence);
                $('#editgradingRemarks').val(remarks);

                // Show the modal
                $('#editgradingComponentsModal').modal('show');
            });

            // Update the table row with the new data
            $('#editGradeEquivalencyBtn').on('click', function() {
                if (currentRow) {
                    // Get updated values from modal inputs
                    const updatedGradePointEquivalence = $('#editgradePointEquivalency').val().trim();
                    const updatedLetterGradeEquivalence = $('#editletterGradeEquivalency').val().trim();
                    // const updatedPercentEquivalence = $('#editpercentEquivalency').val().trim();
                    // const updatedRemarks = $('#editgradingRemarks').val().trim();

                    // Update the table row with new data
                    $(currentRow).find('td:eq(0)').text(updatedGradePointEquivalence);
                    $(currentRow).find('td:eq(1)').text(updatedLetterGradeEquivalence);
                    // $(currentRow).find('td:eq(2)').text(updatedPercentEquivalence);
                    // $(currentRow).find('td:eq(3)').text(updatedRemarks);

                }

                Toast.fire({
                    type: 'success',
                    title: 'Successfully updated'
                })

                // $('#editgradingComponentsModal').modal('hide');

            });


            $(document).on('click', '.edit_gradepoint_scale', function() {
                // var editgradingComponentsModal2 = new bootstrap.Modal($('#editgradingComponentsModal2')[
                //     0], {
                //     keyboard: false
                // });
                // editgradingComponentsModal2.show();
                $('#editgradingComponentsModal2').modal('show');
            });

            let updatecurrentRow; // Variable to store the currently selected row

            $(document).on('click', '.edit_gradepoint_scale', function() {
                // Get the current row
                updatecurrentRow = $(this).closest('tr')[0];

                // Extract data from the row
                const gradePointEquivalence = $(updatecurrentRow).find('td:eq(0)').text().trim();
                const letterGradeEquivalence = $(updatecurrentRow).find('td:eq(1)').text().trim();
                const percentEquivalence = $(updatecurrentRow).find('td:eq(2)').text().trim();
                const remarks = $(updatecurrentRow).find('td:eq(3)').text().trim();

                // Populate modal inputs
                $('#editgradePointEquivalency2').val(gradePointEquivalence);
                $('#editletterGradeEquivalency2').val(letterGradeEquivalence);
                $('#editpercentEquivalency2').val(percentEquivalence);
                $('#editgradingRemarks2').val(remarks);

                // Show the modal
                $('#editgradingComponentsModal2').modal('show');
            });

            // Update the table row with the new data
            $('#editGradeEquivalencyBtn2').on('click', function() {
                if (updatecurrentRow) {
                    // Get updated values from modal inputs
                    const updatedGradePointEquivalence = $('#editgradePointEquivalency2').val().trim();
                    const updatedLetterGradeEquivalence = $('#editletterGradeEquivalency2').val().trim();
                    // const updatedPercentEquivalence = $('#editpercentEquivalency2').val().trim();
                    // const updatedRemarks = $('#editgradingRemarks2').val().trim();

                    // Update the table row with new data
                    $(updatecurrentRow).find('td:eq(0)').text(updatedGradePointEquivalence);
                    $(updatecurrentRow).find('td:eq(1)').text(updatedLetterGradeEquivalence);
                    // $(updatecurrentRow).find('td:eq(2)').text(updatedPercentEquivalence);
                    // $(updatecurrentRow).find('td:eq(3)').text(updatedRemarks);

                }

                Toast.fire({
                    type: 'success',
                    title: 'Successfully updated'
                })

                // $('#editgradingComponentsModal2').modal('hide');

            });




            $('#settingsButton').on('click', function() {
                $('#settingsModal').modal('show');
                $.ajax({
                    url: '/terms',
                    method: 'GET',
                    success: function(response) {
                        let tbody = '';
                        response.forEach(term => {
                            tbody += `
                            <tr data-id="${term.id}">
                                <td>${term.description}</td>
                                <td>${term.Term_frequency}</td>
                                <td>${term.grading_perc}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-term" data-id="${term.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-term" data-id="${term.id}">Delete</button>
                                </td>
                            </tr>`;
                        });
                        $('#settingsModal tbody').html(tbody);
                    },
                    error: function(error) {
                        console.log("Error fetching terms:", error);
                    }
                });
            });

            $('#editsettingsButton').on('click', function() {
                $('#settingsModal').modal('show');
                $.ajax({
                    url: '/terms',
                    method: 'GET',
                    success: function(response) {
                        let tbody = '';
                        response.forEach(term => {
                            tbody += `
                            <tr data-id="${term.id}">
                                <td>${term.description}</td>
                                <td>${term.Term_frequency}</td>
                                <td>${term.grading_perc}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-term" data-id="${term.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-term" data-id="${term.id}">Delete</button>
                                </td>
                            </tr>`;
                        });
                        $('#settingsModal tbody').html(tbody);
                    },
                    error: function(error) {
                        console.log("Error fetching terms:", error);
                    }
                });
            });

            function fetchTermDetails(termId) {
                $.ajax({
                    url: '/edit/terms/' + termId,
                    type: 'GET',
                    success: function(data) {
                        $('#termName').val(data.description);
                        $('#termFrequency').val(data.Term_frequency);
                        $('#formControl').val(data.grading_perc);
                        $('#addTermModalLabel').text('Edit Term');
                        $('#saveTerm').text('Update');
                        $('#saveTerm').data('id', termId);
                        $('#addTermModal').modal('show');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            $('#addTermForm').on('submit', function(e) {
                e.preventDefault();
                let formData = {
                    termName: $('#termName').val(),
                    termFrequency: $('#termFrequency').val(),
                    gradingPercentage: $('#formControl').val(),
                    _token: '{{ csrf_token() }}'
                };

                $('.edit-term').prop('disabled', true);

                $.ajax({
                    url: '/add/terms',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#addTermModal').modal('hide');
                        Swal.fire({
                            title: 'Success!',
                            text: response.success,
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });

                        let newRow = `<tr data-id="${response.term.id}">
                        <td>${formData.termName}</td>
                        <td>${formData.termFrequency}</td>
                        <td>${formData.gradingPercentage}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-term" data-id="${response.term.id}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-term" data-id="${response.term.id}">Delete</button>
                        </td>
                    </tr>`;

                        $('#termTable tbody').append(newRow);
                        $('.edit-term').prop('disabled', false);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        $('.edit-term').prop('disabled', false);
                    }
                });
            });

            $('#saveTerm').on('click', function() {
                let termId = $(this).data('id');
                let formData = {
                    termName: $('#termName').val(),
                    termFrequency: $('#termFrequency').val(),
                    gradingPercentage: $('#formControl').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                if ($('#saveTerm').text() === 'Update') {
                    formData._method = 'PUT';

                    $.ajax({
                        url: '/update/terms/' + termId,
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            $('#addTermModal').modal('hide');
                            Swal.fire({
                                title: 'Updated!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            });

                            $(`tr[data-id="${termId}"]`).find('td').eq(0).text(formData
                                .termName);
                            $(`tr[data-id="${termId}"]`).find('td').eq(1).text(formData
                                .termFrequency);
                            $(`tr[data-id="${termId}"]`).find('td').eq(2).text(formData
                                .gradingPercentage);

                            $('#addTermModalLabel').text('Add New Term');
                            $('#saveTerm').text('Add');
                            $('#saveTerm').removeData('id');
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });

            function deleteTerm(termId) {
                $.ajax({
                    url: '/delete/terms/' + termId,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.success,
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });

                        $(`tr[data-id="${termId}"]`).remove();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            $(document).on('click', '.edit-term', function() {
                let termId = $(this).data('id');
                fetchTermDetails(termId);
            });

            $(document).on('click', '.delete-term', function() {
                let termId = $(this).data('id');
                deleteTerm(termId);
            });
        });

        $(document).on('click', '.delete_gradepoint_equivalency', function() {
            var grade_point_transmutation_id = $(this).attr('data-id')

            Swal.fire({
                text: 'Are you sure you want to remove grade point equivalency?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: '/tesda/setup/gradetransmutation/remove',
                        data: {
                            grade_point_transmutation_id: grade_point_transmutation_id,
                        },
                        success: function(data) {
                            if (data[0].status == 1) {
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                                $("#gradePointEquivalencyTable").DataTable({
                                    destroy: true,
                                    // data:temp_subj,
                                    // bInfo: true,
                                    autoWidth: false,
                                    // lengthChange: true,
                                    // stateSave: true,
                                    // serverSide: true,
                                    // processing: true,
                                    ajax: {
                                        url: '/tesda/setup/gradetransmutation/fetch',
                                        type: 'GET',
                                        dataSrc: function(json) {
                                            return json;
                                        }
                                    },
                                    columns: [
                                        // {
                                        //     "data": "isactive"
                                        // },
                                        {
                                            "data": "grade_transmutation_desc"
                                        },
                                        // {
                                        //     "data": "terms"
                                        // },
                                        {
                                            "data": null
                                        },
                                    ],
                                    columnDefs: [

                                        // {
                                        //     'targets': 0,
                                        //     'orderable': false,
                                        //     'createdCell': function(td, cellData, rowData, row, col) {
                                        //         // Define the icon
                                        //         var checkIcon =
                                        //             '<i class="fa fa-check bg-success checked-active p-2" data-toggle="tooltip" title="Active"></i>';

                                        //         var wrongIcon =
                                        //             '<i class="fa fa-times bg-danger checked-active p-2" data-toggle="tooltip" title="Inactive"></i>';

                                        //         // Determine whether to display the icon
                                        //         var iconHtml = rowData.isactive == 1 ? checkIcon : wrongIcon;

                                        //         // Construct the HTML with the conditional icon only
                                        //         var text = '<div style="text-align:center;">' + iconHtml + '</div>';

                                        //         // Apply the constructed HTML and initialize tooltips
                                        //         $(td).html(text).addClass('align-middle');
                                        //         $(td).find('[data-toggle="tooltip"]').tooltip();
                                        //     }
                                        // },
                                        {
                                            'targets': 0,
                                            'orderable': false,
                                            'createdCell': function(td, cellData,
                                                rowData, row, col) {
                                                $(td).html(rowData
                                                    .grade_transmutation_desc
                                                ).addClass(
                                                    'align-middle');
                                            }
                                        },

                                        // {
                                        //     'targets': 2,
                                        //     'orderable': false,
                                        //     'createdCell': function(td, cellData, rowData, row, col) {
                                        //         // $(td).html('');
                                        //         var html = '';
                                        //         $.each(rowData.terms.split(','), function(i, term) {
                                        //             html +=
                                        //                 '<span style="font-size: 0.6rem;" class="badge badge-primary mr-1">' +
                                        //                 term + '</span>';
                                        //         });
                                        //         $(td).html(html).addClass('align-middle');
                                        //     }
                                        // },


                                        {
                                            'targets': 1,
                                            'orderable': false,
                                            'createdCell': function(td, cellData,
                                                rowData, row, col) {
                                                // var buttons =
                                                //     '<a href="javascript:void(0)" class="delete_subject" data-id="' +
                                                //     rowData.id + '"><i class="far fa-trash-alt text-danger"></i></a>';
                                                // $(td)[0].innerHTML = buttons
                                                // $(td).addClass('text-center')
                                                // $(td).addClass('align-middle')
                                                var link =
                                                    '<a href="#" style="color: #blue; text-decoration: underline; font-weight: bold;" class="view_gradepoint_equivalency mr-2" data-id="' +
                                                    rowData.id +
                                                    '"><i class="fas fa-pencil-alt"></i></a> ' +
                                                    '<a href="#" style="color: red; text-decoration: underline; font-weight: bold;" class="delete_gradepoint_equivalency" data-id="' +
                                                    rowData.id +
                                                    '"><i class="fas fa-trash-alt"></i></a>';
                                                $(td)[0].innerHTML = link;
                                                $(td).addClass(
                                                    'text-center align-middle'
                                                );
                                            }
                                        },

                                    ],
                                    // lengthMenu: [
                                    //     [10, 25, 50, 100],
                                    //     [10, 25, 50, 100]
                                    // ],
                                    // pageLength: 10,
                                    // dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                                    //     "<'row'<'col-sm-12'tr>>" +
                                    //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                                });
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                            }

                        }
                    })
                }
            })


        })

        // function delete_general_subject(grade_point_equivalency_id) {
        //     $.ajax({
        //         type: 'GET',
        //         url: '/tesda/setup/gradepointequivalency/remove',
        //         data: {
        //             grade_point_equivalency_id: grade_point_equivalency_id,
        //         },
        //         success: function(data) {
        //             if (data[0].status == 1) {
        //                 gradePointEquivalencyTable()
        //                 Toast.fire({
        //                     type: 'success',
        //                     title: data[0].message
        //                 })
        //             } else {
        //                 Toast.fire({
        //                     type: 'error',
        //                     title: data[0].message
        //                 })
        //             }

        //         }
        //     })
        // }
    </script>
@endsection
