<style>
  .listholiday i {
    /* ... other styles ... */
    transition: transform 0.6s ease-in-out;
  }
  /* Styling for the "List Here" badge */
  .listholiday, .listholidayclose {
    padding: 4px !important;
    transition: all 0.3s;

  }
  .listholiday:hover{
    transform: translate(-2px);
  }
  .listholidayclose:hover {
    transform: translate(-2px);
  }
  /* Define animation classes for opening and closing */
  .listherediv {
    opacity: 0;
    max-height: 0;
    overflow:hidden;
    /* transition: max-height 0.6s ease-in-out, opacity 0.6s ease-in-out; */
  }

  .listherediv.active {
    opacity: 1;
    max-height: 1000px;
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
</style>
<div class="card shadow" style="border: none !important; border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
  <div class="card-header">
    <div class="row">
      <span><i class="fas fa-exclamation-triangle text-warning"></i> &nbsp;<b>No Basic Salary Information</b></span>
    </div>
  </div>
</div>