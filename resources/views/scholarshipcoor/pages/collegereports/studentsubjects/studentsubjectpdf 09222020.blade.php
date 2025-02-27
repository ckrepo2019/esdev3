

<!DOCTYPE html>
      <html lang="en">
      <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Document</title>
      <style>
             footer {
                position: fixed; 
                bottom: 150px; 
                left: 0px; 
                right: 0px;
                height: 50px; 
                color: white;
                text-align: center;
                line-height: 35px;
            }


            body {
                  margin: 0;
                  font-family: "Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
                  font-size: 1rem;
                  font-weight: 400;
                  line-height: 1.5;
                  color: #212529;
                  text-align: left;
                  background-color: #fff;
            }

            .table {
                  width: 100%;
                  margin-bottom: 1rem;
                  color: #212529;
                  background-color: transparent;
            }
            table {
                  border-collapse: collapse;
            }

            .table-bordered {
                  border: 1px solid #dee2e6;
            }

            .table-bordered td, .table-bordered th {
                  border: 1px solid black;
            }
            .table td, .table th {
                  vertical-align: top;
            }
            .text-center {
                  text-align: center!important;
            }
            .text-left {
                  text-align: left!important;
            }
            .align-middle {
                  vertical-align: middle!important;
            }
            .border-0 {
                  border: 1px solid white !important;
            }
      </style>

</head>

      <body>
            @php
                  $width = 80;
                  $subjCount = count($college_classsched);
                  $minwidth = ( 43 *  $subjCount ) + 100;
                  if($subjCount > 0){
                        $unitWidth = ( ( $width / $subjCount ) / 3 ) * 1;
                        $subjCodeWidth = ( ( $width / $subjCount ) / 3 ) * 2;
                  }
            @endphp

            {{-- <table class="table" >
                  <thead>
                        <tr>
                              <th class="text-center">ENROLLMENT REPORT</th>
                        </tr>
                        <tr>
                              <th class="text-center" style="font-size:10px">{{$activeSem->semester}} S.Y. {{$activeSy->sydesc}}</th>
                        </tr>
                  </thead>
            </table> --}}
     
            {{-- <table class="table" style="font-size:10px">
                  <thead>
                        <tr>
                              <th class="text-left" width="80%"><u>SCHOOL: {{$schoolInfo->schoolname}} </u></th>
                              <th class="text-left" width="20%"><u>REGION: {{$schoolInfo->regDesc}}</u></th>
                        </tr>
                        <tr>
                              <th class="text-left"><u>ADDRESS: {{$schoolInfo->address}}</u></th>
                              <th class="text-left"><u>PAGES: </u></th>
                        </tr>
                  </thead>
            </table> --}}


            {{-- <table class="table" style="font-size:10px" >
                  <thead>
                        <tr>
                              <td width="3%" class="text-center align-middle">No.</td>
                              <td width="17%" colspan="2"  class="text-center align-middle">NAME OF STUDENTS</td>
                        </tr>
                  </thead>
                 
            </table> --}}

            <table class="table table-bordered border-0" style="font-size:10px" >
                  <thead class="border-0">
                        <tr class="border-0">
                              <th style="font-size:15px" class="text-center border-0" colspan="{{ ( $subjCount * 2 ) + 3}}">ENROLLMENT REPORT</th>
                        </tr>
                        <tr class="border-0">
                              <th class="text-center border-0" style="font-size:10px" colspan="{{ ( $subjCount * 2 ) + 3}}">{{$activeSem->semester}} S.Y. {{$activeSy->sydesc}}</th>
                        </tr>
                        <tr class="border-0">
                              <th class="text-left border-0" colspan="{{$subjCount*2}}"><u>SCHOOL: {{$schoolInfo->schoolname}} </u></th>
                              <th class="text-left border-0" colspan="3"><u>REGION: {{$schoolInfo->regDesc}}</u></th>
                        </tr>
                        <tr class="border-0">
                              <th class="text-left border-0" colspan="{{$subjCount*2}}"><u>ADDRESS: {{$schoolInfo->address}}</u></th>
                              <th class="text-left border-0" colspan="3"><u>PAGES: </u></th>
                        </tr>
                        <tr class="border-0" style="border-bottom: 1px solid black !important">
                              <td class="border-0" colspan="{{ ( $subjCount * 2 ) + 3}}" style="border-bottom: 1px solid black !important">&nbsp;</td>
                        </tr>
                        <tr>
                              <th style="padding:.75rem;" colspan="{{ ( $subjCount * 2 ) + 3}}">{{$course->courseDesc}} ( {{$course->courseabrv}} )</th>
                        </tr>
                        <tr>
                              <td width="3%" class="text-center align-middle">No.</td>
                              <td width="17%" colspan="2"  class="text-center align-middle">NAME OF STUDENTS</td>
                        
                              @foreach ($college_classsched as $item)
                              <td class="text-center" width="{{$subjCodeWidth}}%">SUBJECT CODE</td>
                              <td class="text-center align-middle" width="{{$unitWidth}}%">UNITS</td>
                              @endforeach
                        </tr>
                        <tr>
                              <td width="3%" class="text-center align-middle">&nbsp;</td>
                              <td width="17%" colspan="2"  class="text-center align-middle">
                                    @if($gender != null && $gender != 'null')
                                          {{$gender}}
                                    @endif
                              </td>
                              @foreach ($college_classsched as $item)
                                    <td class="text-center" width="{{$subjCodeWidth}}%">&nbsp;</td>
                                    <td class="text-center align-middle" width="{{$unitWidth}}%">&nbsp;</td>
                              @endforeach
                        </tr>
                        
                  </thead>
                  <tbody>
                        @foreach ($data[0]->data as $key=>$item)
                              <tr>
                                    <td class="text-center align-middle">{{$key + 1 }}</td>
                                    <td style="padding-left:.75rem; " class="align-middle">{{$item->lastname}}</td>
                                    <td style="padding-left:.75rem; " class="align-middle">{{$item->firstname}}</td>

                                    @foreach ($college_classsched as $scheditem)
                                          @php
                                                $matchedSched = collect($item->sched)
                                                                        ->where('schedid',$scheditem->schedid)
                                                                        ->first()
                                                                        ;
                                          @endphp
                                          @if( isset($matchedSched->subjCode))
                                                <td class="text-center align-middle bg-success" >{{$matchedSched->subjCode}}</td>
                                                <td class="text-center align-middle bg-success">{{$matchedSched->lecunits + $matchedSched->labunits}}</td>
                                          @else
                                                <td class="text-center align-middle bg-danger" ></td>
                                                <td class="text-center align-middle bg-danger"></td>
                                          @endif
                                    @endforeach

                              </tr>
                        @endforeach
                  </tbody>
                  <tfoot></tfoot>
            </table>

            
            <footer>
                  @if(count($signatories) > 0)
                        @php
                              $width = 100 / count($signatories);
                        @endphp

                        <table class="table" style="font-size:10px">
                              <tbody>
                                    <tr>
                                          @foreach ($signatories as $item)
                                                <td width="{{$width}}%"><b>{{$item->description}}<b></td>
                                          @endforeach
                                    </tr>
                              </tbody>
                        
                        </table>
                        <table class="table" style="font-size:10px">
                              <tbody>
                                    <tr>
                                          @foreach ($signatories as $item)
                                                <td width="{{$width}}%"><b>{{$item->name}}<b></td>
                                          @endforeach
                                    </tr>
                                    <tr  style="font-size:10px">
                                          @foreach ($signatories as $item)
                                                <td>{{$item->title}}</td>
                                          @endforeach
                                    </tr>
                              </tbody>
                        
                        </table>

                  @endif
              </footer>
      </body>
</html>
      
      