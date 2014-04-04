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
      <p>Upload your CSV/Excel file here</p>
      <form enctype="multipart/form-data" method="post" action="upload">
        <span class="btn btn-default btn-file">Browse <input onchange="javascript:this.form.submit();" type="file" name="file"> </span>
      </form>
    </div>
  </div>

  <div class="row">&nbsp;</div>
  <div class="row">&nbsp;</div>

  <div class="row">
    <div class="col-md-4">
        <h3>View & Filter</h3>
        <p class="text-success">Online spreadsheet viewing and filtering.</p>
        <p>View and filter spreadsheet data using the fast spreadsheet application.</p>
    </div>
    <div class="col-md-4">
        <h3>Merge and Transform data</h3>
        <p class="text-success">Filter messy spreadsheet data into the format you want!</p>
        <p>Intuative column editor and manipulation tool will let you <strong>combine</strong> one or more columns while <strong>stripping out/cleaning</strong> letters or any sort of characters you don't need.</p>
    </div>
    <div class="col-md-4">
        <h3>Export file types</h3>
        <p class="text-success">Excel, CSV and PDF exports.</p>
        <p>You can eiter convert your existing document/spreadsheet into the format you want or get an export after merging and transforming data using the merge application the way you want it.</p>
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
