@extends('layouts.master')

@section('content')
<div class="container">
  <div class="jumbotron center">
    <h1>play with spreadsheets</h1>
    <p></p>
  </div>

  <div class="row">
    <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-sm-offset-3 col-6 col-sm-6 center">
      <h2>Upload Here</h2>
      <p>Upload your CSV/Excel file here</p>
      <form enctype="multipart/form-data" method="post" action="upload">
        <span class="btn btn-primary btn-file"> <i class="glyphicon glyphicon-cloud-upload"> </i> <input onchange="javascript:this.form.submit();" type="file" id="file-browse" name="file"> </span>
      </form>
      @if(Session::get('success') === false)
      <br>
      <div class="alert alert-danger left">
        File you uploaded can not be interpreted as a spreadsheet for following reasons. <br>
        <p>1. File is not a spreadsheet at all, we only accept CSV and Excel files without pictures.</p>
        <p>2. There may not be any data in the first worksheet.</p>
        <p>3. There may not column headings defined in the worksheet.</p>
      </div>
      @endif
    </div>
  </div>

  <div class="row">&nbsp;</div>
  <div class="row">&nbsp;</div>

  <div class="row">
    <div class="col-md-4">
        <h3>View & Filter</h3>
        <p class="text-success">Online spreadsheet viewing and filtering.</p>
        <p>View and filter spreadsheet data using the fast spreadsheet application.</p>
        <p>You don't need to add AutoFilters to columns, just type what you are after in the text box and it will filter the entire spreadsheet.</p>
    </div>
    <div class="col-md-4">
        <h3>Merge and Transform data</h3>
        <p class="text-success">Filter messy spreadsheet data into the format you want!</p>
        <p>Intuative column editor and manipulation tool will let you <strong>combine</strong> one or more columns while <strong>stripping out/cleaning</strong> letters or any sort of characters you don't need.</p>
        <p>Register online to save your mostly used data mapping templates online, just upload the file and apply the template to transform your data.</p>
    </div>
    <div class="col-md-4">
        <h3>File Type Conversion</h3>
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
