<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
      body {
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        font-size: 12px;
      }
      .text-bold{
        font-style: bold;
      }
      .header{
        font-size: medium;
      }
      .float-right{
        float: right;
        display: inline-block;
      }
      .mt-1{
        margin-top: 1em;
      }
      .mt-2{
        margin-top: 2em;
      }
      .mt-3{
        margin-top: 3em;
      }
      .text-right {
        text-align: right !important;
      }
      .text-center {
         text-align: center !important;
      }
      .b-1{
        border: solid !important;
      }

      .btop{
        border-top: solid;
      }

      .table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
  background-color: transparent;
}

.table th,
.table td {
  padding: 0.30rem;
  vertical-align: top;
  border-top: 0px solid #dee2e6;
}

.table thead th {
  vertical-align: bottom;
  border-bottom: 2px solid #dee2e6;
}

.table tfoot th {
  vertical-align: bottom;
  border-top: 2px solid #dee2e6;
}

.table tbody + tbody {
  border-top: 2px solid #dee2e6;
}

.table-sm th,
.table-sm td {
  padding: 0.3rem;
}

.table-bordered {
  border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6;
}

.table-bordered thead th,
.table-bordered thead td {
  border-bottom-width: 2px;
}

.table-borderless th,
.table-borderless td,
.table-borderless thead th,
.table-borderless tbody + tbody {
  border: 0;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, 0.05);
}

.table-hover tbody tr:hover {
  color: #212529;
  background-color: rgba(0, 0, 0, 0.075);
}

.table-primary,
.table-primary > th,
.table-primary > td {
  background-color: #b8daff;
}

.table-primary th,
.table-primary td,
.table-primary thead th,
.table-primary tbody + tbody {
  border-color: #7abaff;
}

.table-hover .table-primary:hover {
  background-color: #9fcdff;
}

.table-hover .table-primary:hover > td,
.table-hover .table-primary:hover > th {
  background-color: #9fcdff;
}

.table-secondary,
.table-secondary > th,
.table-secondary > td {
  background-color: #d6d8db;
}

.table-secondary th,
.table-secondary td,
.table-secondary thead th,
.table-secondary tbody + tbody {
  border-color: #b3b7bb;
}

.table-hover .table-secondary:hover {
  background-color: #c8cbcf;
}

.table-hover .table-secondary:hover > td,
.table-hover .table-secondary:hover > th {
  background-color: #c8cbcf;
}

.table-success,
.table-success > th,
.table-success > td {
  background-color: #c3e6cb;
}

.table-success th,
.table-success td,
.table-success thead th,
.table-success tbody + tbody {
  border-color: #8fd19e;
}

.table-hover .table-success:hover {
  background-color: #b1dfbb;
}

.table-hover .table-success:hover > td,
.table-hover .table-success:hover > th {
  background-color: #b1dfbb;
}

.table-info,
.table-info > th,
.table-info > td {
  background-color: #bee5eb;
}

.table-info th,
.table-info td,
.table-info thead th,
.table-info tbody + tbody {
  border-color: #86cfda;
}

.table-hover .table-info:hover {
  background-color: #abdde5;
}

.table-hover .table-info:hover > td,
.table-hover .table-info:hover > th {
  background-color: #abdde5;
}

.table-warning,
.table-warning > th,
.table-warning > td {
  background-color: #ffeeba;
}

.table-warning th,
.table-warning td,
.table-warning thead th,
.table-warning tbody + tbody {
  border-color: #ffdf7e;
}

.table-hover .table-warning:hover {
  background-color: #ffe8a1;
}

.table-hover .table-warning:hover > td,
.table-hover .table-warning:hover > th {
  background-color: #ffe8a1;
}

.table-danger,
.table-danger > th,
.table-danger > td {
  background-color: #f5c6cb;
}

.table-danger th,
.table-danger td,
.table-danger thead th,
.table-danger tbody + tbody {
  border-color: #ed969e;
}

.table-hover .table-danger:hover {
  background-color: #f1b0b7;
}

.table-hover .table-danger:hover > td,
.table-hover .table-danger:hover > th {
  background-color: #f1b0b7;
}

.table-light,
.table-light > th,
.table-light > td {
  background-color: #fdfdfe;
}

.table-light th,
.table-light td,
.table-light thead th,
.table-light tbody + tbody {
  border-color: #fbfcfc;
}

.table-hover .table-light:hover {
  background-color: #ececf6;
}

.table-hover .table-light:hover > td,
.table-hover .table-light:hover > th {
  background-color: #ececf6;
}

.table-dark,
.table-dark > th,
.table-dark > td {
  background-color: #c6c8ca;
}

.table-dark th,
.table-dark td,
.table-dark thead th,
.table-dark tbody + tbody {
  border-color: #95999c;
}

.table-hover .table-dark:hover {
  background-color: #b9bbbe;
}

.table-hover .table-dark:hover > td,
.table-hover .table-dark:hover > th {
  background-color: #b9bbbe;
}

.table-active,
.table-active > th,
.table-active > td {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-hover .table-active:hover > td,
.table-hover .table-active:hover > th {
  background-color: rgba(0, 0, 0, 0.075);
}

.table .thead-dark th {
  color: #ffffff;
  background-color: #212529;
  border-color: #383f45;
}

.table .thead-light th {
  color: #495057;
  background-color: #e9ecef;
  border-color: #dee2e6;
}

.table-dark {
  color: #ffffff;
  background-color: #212529;
}

.table-dark th,
.table-dark td,
.table-dark thead th {
  border-color: #383f45;
}

.table-dark.table-bordered {
  border: 0;
}

.table-dark.table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(255, 255, 255, 0.05);
}

.table-dark.table-hover tbody tr:hover {
  color: #ffffff;
  background-color: rgba(255, 255, 255, 0.075);
}

@media (max-width: 575.98px) {
  .table-responsive-sm {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-sm > .table-bordered {
    border: 0;
  }
}

@media (max-width: 767.98px) {
  .table-responsive-md {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-md > .table-bordered {
    border: 0;
  }
}

@media (max-width: 991.98px) {
  .table-responsive-lg {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-lg > .table-bordered {
    border: 0;
  }
}

@media (max-width: 1199.98px) {
  .table-responsive-xl {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .table-responsive-xl > .table-bordered {
    border: 0;
  }
}

.table-responsive {
  display: block;
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table-responsive > .table-bordered {
  border: 0;
}

    </style>

    <title>{{strtoupper($stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename) . ' | STUDENT LEDGER'}}</title>
  </head>
  <body>
    <div>
      <div style="text-align: right; display: block; width: 250px" class="float-right">
        <span class="">STUDENT LEDGER</span>
        
        <span style="display: block;">As of {{$curDate}}</span>
      </div>
      <div class=" header">
        {{$sinfo->schoolname}}
      </div>
      <div>
        {{$sinfo->address}}
      </div>
      <div class="mt-1">
        <table cellspacing="0" cellpadding="0" style="width: 100%">
          <tr>
            <td style="width: 100%" colspan="2" class="text-bold">{{strtoupper($stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename)}}</td>
          </tr>
          <tr>
            <td>{{strtoupper($stud->levelname . ' | ' . $stud->sectionname)}}</td>
            <td style="text-align: right">
              @if($levelid == 14 || $levelid == 15)
                SY: {{$sydesc}} - {{$semester}}
              @elseif($levelid >= 17 && $levelid <= 21)
                SY: {{$sydesc}} - {{$semester}}
              @else
                SY: {{$sydesc}}
              @endif
            </td>
          </tr>
        </table>
        {{-- <div class="text-bold"></div>
        <div style="width: 100%">
          <div style="width: 60%">
            
          </div>
          <div style="width: 40%; display: block;" class="float-right">
            
          </div>
            
        </div> --}}
      </div> 

      <div class="mt-1">
        <table class="table">
          <thead>
            <tr>
              <th>DATE</th>
              <th>PARTICULARS</th>
              <th class="text-center">CHARGES</th>
              <th class="text-center">PAYMENT</th>
              <th class="text-center">BALANCE</th>
            </tr>
          </thead>
          <tbody>
            @php
              $bal = 0;
              $debit = 0;
              $credit = 0;


            @endphp
            @foreach($ledger as $led)
              @php
                $debit += $led->amount;
                $credit += $led->payment;
                $tDate = date_create($led->createddatetime);
                $tDate = date_format($tDate, 'm-d-Y');

                if($led->amount > 0)
                {
                  $amount = number_format($led->amount,2);
                }
                else
                {
                  $amount = '';
                }

                if($led->payment > 0)
                {
                  $payment = number_format($led->payment,2);
                }
                else
                {
                  $payment = '';
                }

                $bal += $led->amount - $led->payment;
              @endphp
              <tr>
                <td>{{$tDate}}</td>
                <td>{{$led->particulars}}</td>
                <td class="text-right">{{$amount}}</td>
                <td class="text-right">{{$payment}}</td>
                <td class="text-right">{{number_format($bal, 2)}}</td>
              </tr>
            @endforeach
            <tfoot>
              <tr>
                <th colspan="2" class="text-right">TOTAL: </th>
                <th class="text-right bt">{{number_format($debit, 2)}}</th>
                <th class="text-right bt">{{number_format($credit, 2)}}</th>
                <th class="text-right bt">{{number_format($bal, 2)}}</th>
              </tr>
              <tr>
                <td colspan="3">
                  Prepared by: <u><span class="text-bold">{{auth()->user()->name}}</span></u>
                </td>
              </tr>
            </tfoot>
          </tbody>
        </table>
      </div>

    </div>
    
        
  </body>
</html>