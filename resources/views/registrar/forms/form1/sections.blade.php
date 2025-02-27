<style>
    .alert-primary {
        color: #004085;
        background-color: #cce5ff;
        border-color: #b8daff;
    }

    .alert-secondary {
        color: #383d41;
        background-color: #e2e3e5;
        border-color: #d6d8db;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .alert-light {
        color: #818182;
        background-color: #fefefe;
        border-color: #fdfdfe;
    }

    .alert-dark {
        color: #1b1e21;
        background-color: #d6d8d9;
        border-color: #c6c8ca;
    }
</style>
@if (count($sections) == 0)
    <div class="alert alert-primary" role="alert">
        No sections shown!
    </div>
@else
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i> &nbsp;Search</span>
                </div>
                <input type="text" class="filter form-control" placeholder="Ex. section or last name">
            </div>
        </div>
    </div>
    @foreach ($sections as $section)
        <div class="card card-success card-eachsection"
            data-string="{{ $section->sectionname }} {{ $section->firstname }} {{ $section->middlename }} {{ $section->lastname }} {{ $section->suffix }} {{ count($section->students) > 0 ? collect($section->students)->pluck('lastname') : '' }}<">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-7">
                        <h3 class="card-title">{{ $section->sectionname }}</h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <small class="text-bold">{{ $section->firstname }} {{ $section->middlename }}
                            {{ $section->lastname }} {{ $section->suffix }}</small>
                    </div>
                    <div class="col-md-1 text-right">
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-secondary mt-1"
                                data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    @if (count($section->students) == 0)
                        <div class="col-md-12">
                            <div class="alert alert-primary" role="alert">
                                No students enrolled!
                            </div>
                        </div>
                    @else
                        @if ($acadprogid == 5)
                            @php
                                $students = collect($section->students)->groupBy('strandname');
                            @endphp
                            @if (count($students) > 0)
                                @foreach ($students as $keystrand => $studentgroup)
                                    <div class="col-md-8 mb-2">
                                        <label>Strand: {{ $keystrand }}</label>
                                    </div>
                                    <div class="col-md-4 mb-2 text-right">
                                        <button type="button" class="btn btn-default btn-sm btn-exportpdf"
                                            data-sectionid="{{ $section->id }}"
                                            data-strandid="{{ $studentgroup[0]->strandid }}">Export to PDF</button>
                                        <button type="button" class="btn btn-default btn-sm btn-exportexcel"
                                            data-sectionid="{{ $section->id }}"
                                            data-strandid="{{ $studentgroup[0]->strandid }}">Export to Excel</button>
                                    </div>
                                    <div class="col-md-6">
                                        <label>MALE</label>
                                        <ol>
                                            @foreach ($studentgroup as $student)
                                                @if (strtolower($student->gender) == 'male')
                                                    <li
                                                        style="display: list-item;list-style: decimal; list-style-position: inside; @if ($student->studstatus == 3 || $student->studstatus == 5) text-decoration: line-through @endif">
                                                        {{ $student->lastname }}, {{ $student->firstname }}
                                                        {{ $student->middlename }} @if ($student->studstatus == 3 || $student->studstatus == 5)
                                                            {{ DB::table('studentstatus')->where('id', $student->studstatus)->first()->description }}
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <label>FEMALE</label>
                                        <ol>
                                            @foreach ($studentgroup as $student)
                                                @if (strtolower($student->gender) == 'female')
                                                    <li
                                                        style="display: list-item;list-style: decimal; list-style-position: inside; @if ($student->studstatus == 3 || $student->studstatus == 5) text-decoration: line-through @endif">
                                                        {{ $student->lastname }}, {{ $student->firstname }}
                                                        {{ $student->middlename }} @if ($student->studstatus == 3 || $student->studstatus == 5)
                                                            {{ DB::table('studentstatus')->where('id', $student->studstatus)->first()->description }}
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ol>
                                    </div>
                                    <hr />
                                @endforeach
                            @endif
                        @else
                            <div class="col-md-12 mb-2 text-right">
                                <button type="button" class="btn btn-default btn-sm btn-exportpdf"
                                    data-sectionid="{{ $section->id }}">Export to PDF</button>
                                <button type="button" class="btn btn-default btn-sm btn-exportexcel"
                                    data-sectionid="{{ $section->id }}">Export to Excel</button>
                            </div>
                            <div class="col-md-6">
                                <label>MALE</label>
                                <ol>
                                    @foreach ($section->students as $student)
                                        @if (strtolower($student->gender) == 'male')
                                            <li
                                                style="display: list-item;list-style: decimal; list-style-position: inside; @if ($student->studstatus == 3 || $student->studstatus == 5) text-decoration: line-through @endif">
                                                {{ $student->lastname }}, {{ $student->firstname }}
                                                {{ $student->middlename }} @if ($student->studstatus == 3 || $student->studstatus == 5)
                                                    {{ DB::table('studentstatus')->where('id', $student->studstatus)->first()->description }}
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <label>FEMALE</label>
                                <ol>
                                    @foreach ($section->students as $student)
                                        @if (strtolower($student->gender) == 'female')
                                            <li
                                                style="display: list-item;list-style: decimal; list-style-position: inside; @if ($student->studstatus == 3 || $student->studstatus == 5) text-decoration: line-through @endif">
                                                {{ $student->lastname }}, {{ $student->firstname }}
                                                {{ $student->middlename }} @if ($student->studstatus == 3 || $student->studstatus == 5)
                                                    {{ DB::table('studentstatus')->where('id', $student->studstatus)->first()->description }}
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ol>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    @endforeach
@endif
