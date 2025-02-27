@extends('teacher.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<style>
    .color-palette                  { display: block; height: 35px; line-height: 35px; text-align: left; padding-left: .75rem; }
    .color-palette.disabled         { text-align: center; padding-right: 0; display: block; }
    .color-palette-set              { margin-bottom: 15px; }
    .color-palette.disabled span    { display: block; text-align: left; padding-left: .75rem; }
    .color-palette-box h4           { position: absolute; left: 1.25rem; margin-top: .75rem; color: rgba(255, 255, 255, 0.8); font-size: 12px; display: block; z-index: 7; }
    img {
    border-radius: unset !important;
}
img {
    vertical-align: unset !important;
    border-style: none;
}

.imageThumb {
        max-height: 75px;
        border: 2px solid;
        padding: 1px;
        cursor: pointer;
        }
        .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
        }
        .remove {
        display: block;
        background: #444;
        border: 1px solid black;
        color: white;
        text-align: center;
        cursor: pointer;
        }
        .remove:hover {
        background: white;
        color: black;
        }

        
#studentstable_wrapper{
    width: 100%;
}
.wrapper {
    display: flex;
    width: 100%;
    align-items: stretch;
}

#sidebar {
    min-width: 250px;
    max-width: 250px;
    /* background: #7386D5; */
    /* color: #fff; */
    transition: all 0.3s;
}

#sidebar.active {
    margin-left: -266px;
}

#sidebar .sidebar-header {
    padding: 2px;
    /* background: #6d7fcc; */
}

#sidebar ul.components {
    padding: 20px 0;
    border-bottom: 1px solid #47748b;
}

#sidebar ul p {
    color: #fff;
    padding: 10px;
}

#sidebar ul li a {
    padding: 10px;
    font-size: 1.1em;
    display: block;
}

#sidebar ul li a:hover {
    color: #7386D5;
    background: #fff;
}

#sidebar ul li.active>a,
a[aria-expanded="true"] {
    color: #fff;
    background: #6d7fcc;
}

a[data-toggle="collapse"] {
    position: relative;
}

.dropdown-toggle::after {
    display: block;
    position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
}

ul ul a {
    font-size: 0.9em !important;
    padding-left: 30px !important;
    background: #6d7fcc;
}

ul.CTAs {
    padding: 20px;
}

ul.CTAs a {
    text-align: center;
    font-size: 0.9em !important;
    display: block;
    border-radius: 5px;
    margin-bottom: 5px;
}

a.download {
    background: #fff;
    color: #7386D5;
}

a.article,
a.article:hover {
    background: #6d7fcc !important;
    color: #fff !important;
}

/* ---------------------------------------------------
    CONTENT STYLE
----------------------------------------------------- */

#content {
    width: 100%;
    padding: 20px;
    min-height: 100vh;
    transition: all 0.3s;
}

/* ---------------------------------------------------
    MEDIAQUERIES
----------------------------------------------------- */

@media (max-width: 768px) {
    #sidebar {
        margin-left: -266px;
    }
    #sidebar.active {
        margin-left: 0;
    }
    #sidebarCollapse span {
        display: none;
    }
}
</style>

<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item"><a href="/virtualclassroomindex">Virtual Classrooms</a></li>
            <li class="active breadcrumb-item" aria-current="page">{{$classroom->classroomname}}</li>
        </ol>
    </nav>
</div>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
    $(document).ready(function(){
        $('body').addClass('sidebar-collapse');
    })
    
</script>
@endsection