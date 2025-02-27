<!DOCTYPE html>
<html>

<head>
    <title>ECR Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 2px;
            text-align: center;
            vertical-align: middle;
        }

        h1,
        h2 {
            color: #333;
        }

        /* Rotate the text for Total and Average headers */
        .vertical-text {
            position: relative;
            display: inline-block;
            transform: rotate(-90deg);
            transform-origin: right top;
            height: auto;
            width: 20px;
            white-space: nowrap;
            text-align: center;
        }

        /* Additional styling */
        th,
        td {
            padding: 2px;
            text-align: center;
            vertical-align: middle;
        }
    </style>

<body>
    <!-- ECR Title -->
    {{-- <h1>ECR: {{ $data['ecr']->ecrDesc }}</h1> --}}

    <!-- Students Table -->
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
            <!-- First Row: Main Component Descriptions -->
            <tr>
                <th rowspan="3" style="vertical-align: middle;">STUDENT</th>
                @foreach ($components as $component)
                    @php
                        $hasSubcomponents = count($component->subComponents) > 0;
                        $totalColumns = $hasSubcomponents
                            ? collect($component->subComponents)->sum('subColumnECR') + 2
                            : $component->column_ECR + 2;
                    @endphp
                    <th colspan="{{ $totalColumns }}">
                        {{ $component->descriptionComp }} ({{ $component->component }}%)
                    </th>
                @endforeach
                <th rowspan="3" style="writing-mode: vertical-lr;">Total Average</th>
            </tr>

            <!-- Second Row: Subcomponent Descriptions -->
            <tr>
                @foreach ($components as $component)
                    @if (count($component->subComponents) > 0)
                        @foreach ($component->subComponents as $sub)
                            <th colspan="{{ $sub->subColumnECR }}">
                                {{ $sub->subDescComponent }}
                            </th>
                        @endforeach
                    @else
                        <th colspan="{{ $component->column_ECR }}">
                            {{ $component->descriptionComp }}
                        </th>
                    @endif
                    <th rowspan="2" style="writing-mode: vertical-lr; padding: -20px;">Total</th>
                    <th rowspan="2" style="writing-mode: vertical-lr; padding: -20px;">Average</th>
                @endforeach
            </tr>

            <!-- Third Row: Sequential Column Numbers -->
            <tr>
                @php $counter = 1; @endphp
                @foreach ($components as $component)
                    @if (count($component->subComponents) > 0)
                        @foreach ($component->subComponents as $sub)
                            @for ($i = 0; $i < $sub->subColumnECR; $i++)
                                <th>{{ $counter++ }}</th>
                            @endfor
                        @endforeach
                    @else
                        @for ($i = 0; $i < $component->column_ECR; $i++)
                            <th>{{ $counter++ }}</th>
                        @endfor
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- Students Rows -->
            @php
            $exampleStudents = [
                (object)['studentID' => 1, 'lastname' => 'Student 1', 'firstname' => '', 'middlename' => ''],
                (object)['studentID' => 2, 'lastname' => 'Student 2', 'firstname' => '', 'middlename' => ''],
                (object)['studentID' => 3, 'lastname' => 'Student 3', 'firstname' => '', 'middlename' => ''],
                (object)['studentID' => 4, 'lastname' => 'Student 4', 'firstname' => '', 'middlename' => ''],
                (object)['studentID' => 5, 'lastname' => 'Student 5', 'firstname' => '', 'middlename' => ''],
                (object)['studentID' => 6, 'lastname' => 'Student 6', 'firstname' => '', 'middlename' => ''],
                (object)['studentID' => 7, 'lastname' => 'Student 7', 'firstname' => '', 'middlename' => ''],
                (object)['studentID' => 8, 'lastname' => 'Student 8', 'firstname' => '', 'middlename' => ''],
            ];
        @endphp
        
        @foreach ($exampleStudents as $student)
            <tr>
                <!-- Student Name -->
                <td style="text-align: left;">
                    {{ $student->lastname }}, {{ $student->firstname }} {{ $student->middlename }}
                </td>
        
                <!-- Scores for Each Component -->
                @foreach ($components as $component)
                    @if (count($component->subComponents) > 0)
                        @foreach ($component->subComponents as $sub)
                            @for ($i = 0; $i < $sub->subColumnECR; $i++)
                                <td>
                                    {{ $studentScores[$student->studentID][$sub->id]['score'] ?? '' }}
                                </td>
                            @endfor
                        @endforeach
                    @else
                        @for ($i = 0; $i < $component->column_ECR; $i++)
                            <td>
                                {{ $studentScores[$student->studentID][$component->id]['score'] ?? '' }}
                            </td>
                        @endfor
                    @endif
        
                    <!-- Total and Average -->
                    <td>
                        {{ $studentTotals[$student->studentID][$component->id] ?? '' }}
                    </td>
                    <td>
                        {{ $studentAverages[$student->studentID][$component->id] ?? '' }}
                    </td>
                @endforeach
        
                <!-- Final Average -->
                <td>
                    @php
                        $studentTotalAverage = collect($components)->sum(function ($comp) use ($studentAverages, $student) {
                            return $studentAverages[$student->studentID][$comp->id] ?? 0;
                        });
                        $numComponents = count($components);
                    @endphp
                    {{ $numComponents > 0 ? number_format($studentTotalAverage / $numComponents, 2) : '' }}
                </td>
            </tr>
        @endforeach
        
        </tbody>
    </table>
</body>



<!-- Print Button -->
{{-- <button onclick="window.print()">Print this page</button> --}}
</body>

</html>
