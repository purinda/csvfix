@extends('layouts.master')

@section('content')
<div class="container">
  <div class="jumbotron">
    <h1>Process CSV Files</h1>
    <p>because flat-files can be the messiest!</p>
  </div>

  <div class="row">
    <div class="col-md-offset-4 col-6 col-sm-6 col-lg-4">
      <h2>Upload Files</h2>
      <p>Upload your CSV/Excel file here and view it online.</p>
      <form enctype="multipart/form-data" method="post" action="upload">
        <span class="btn btn-default btn-file">Browse <input onchange="javascript:this.form.submit();" type="file" name="file"> </span>
      </form>
    </div>
  </div>
</div>


<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
  <div class="container">
    <ul class="nav navbar-nav ">
       <li><a>Copyright (C) 2014 {{ $copyright }}</a></li>
    </ul>
  </div>
</nav>
@stop
