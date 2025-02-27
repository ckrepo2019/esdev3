<style>
  .footerhead {
    padding: 1em;
    height: 100px;
  }
  /* .footerlogo {
    margin-top: -10px;
    margin-bottom: -20px;
    margin-left: 15%;
    width: 200px;
    height: 80px;
    /* display: none; */
  } */
  ul li {
    display: inline;
  }
  ul li .fa-facebook{
    color: #0272ea;
  }
  ul li .fa-google-plus{
    color: #d22210;
    padding-right: 20px;
  }
</style>


{{-- <div class="footer"> --}}
    <div class="row col-md-12  footerhead">
        <div class="col-md-3"> 
{{--              
          <a href="#">
            <img class="footerlogo" src="{{asset('assets\images\CK_Logo.png')}}" alt="">
         </a> 
            --}}
        </div>
        <div class="col-md-6"  style="text-align:center">
          <span>MENUS</span>
        </div>
        <div class="col-md-3"  style="text-align:right">
          <span>SC MEDIA</span>
          <ul>
            <li><a href="#"><i class="fab fa-facebook"></i></a></li>
            <li><a href="#"><i class="fab fa-google-plus"></i></a></li>
          </ul>
        </div>
    </div>
    <div class="col-md-12" style="text-align:center">
      <strong>Copyright © 2020-2021 <a href="http://adminlte.io">Essentiel.CK</a>.</strong>
      All rights reserved.
      {{-- <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.0.0
      </div> --}}
    </div>
{{-- </div> --}}