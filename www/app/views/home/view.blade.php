@extends('layouts.master')

@section('content')
<script type="text/template" id="column-group-markup">
<div class="panel panel-default merge-column-container">
  <div class="panel-heading">
    <b class="panel-title">
      Map Columns
    </b>
  </div>
  <div class="panel-body">
    <div class="form-horizontal" role="form">
      <div class="form-group">
        <label class="col-sm-2 control-label">Output column name</label>
        <div class="col-sm-5">
          <div class="input-group">
            <input data-name="merge_field[]" type="email" class="form-control input-sm" placeholder="Full name, etc">

            <div class="input-group-btn dropup">
              <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                <span class="glyphicon glyphicon-list-alt"></span>
                Map <span class="caret"></span>
              </button>
              <ul class="dropdown-menu pull-right" role="menu">
                @foreach($columns_menu as $prefix => $column)
                @if (!is_array($column))
                <li><a href="#" class="btn-add-column" data-column="{{ $prefix }}" data-column-display-name="{{ $column }}">{{ $column }}</a></li>
                @else
                  <li class="dropdown-submenu">
                  <a tabindex="-1" href="#">{{ $prefix }}</a>
                    <ul class="dropdown-menu">
                    @foreach ($column as $column_name => $display_name)
                      <li><a href="#" class="btn-add-column" data-column="{{ $column_name }}" data-column-display-name="{{ $display_name }}">{{ $display_name }}</a></li>
                    @endforeach
                    </ul>
                  </li>
                @endif
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>

      <table class="table table-condensed table-hover no-padding columns-container">
        <thead>
          <tr>
            <th style="width: 70%;">Source column name</th>
            <th style="width: auto;"></th>
            <th style="width: 150px;">Trim <span class="glyphicon glyphicon-question-sign tooltip-trim">&nbsp;</span></th>
            <th style="width: 150px;">Suffix <span class="glyphicon glyphicon-question-sign tooltip-suffix">&nbsp;</span></th>
            <th style="width: 100px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- content -->
          <tr class="when-empty">
            <td colspan="5"><em>No columns selected, please add columns using the "Add" button provided.</em></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="panel-footer">
    Use "Add" button to put another column into the export spreadsheet or use "Remove" to take out this column.
    <div class="btn-group pull-right">
      <button class="btn btn-success btn-sm btn-add-group-mapping"><span class="glyphicon glyphicon-plus"></span> Add</button>
      <button class="btn btn-danger btn-sm btn-remove-group-mapping"><span class="glyphicon glyphicon-remove"></span> Remove</button>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
</script>

<div class="container">
  <div class="row">
    <h3><i class="glyphicon glyphicon-th-list"></i> Viewing {{ $file_name }}</h3>

    <table id="table_id" data-file-id="{{ $file_id }}" cellpadding="0" cellspacing="0" border="0" class="table table-hover table-condensed" width="100%">
      <thead>
        <tr>
          @foreach($columns as $column)
          <th>{{ $column }}</th>
          @endforeach
        </tr>
      </thead>
      <!-- Empty tbody as ajax request would fill it-->
      <tfoot>
        <tr>
          @foreach($columns as $column)
          <th>{{ $column }}</th>
          @endforeach
        </tr>
      </tfoot>
    </table>
  </div>

  <br>

  <div class="row">
    <h3><i class="glyphicon glyphicon-filter"></i>  Map data</h3>
    <p>Get started with adding a just one column and linking it to the one or more columns in the table above.</p>

    <form class="merge-column-group">
      <!-- will be filled dynamically -->
    </form>
  </div>
</div>

<div class="navbar-separator">
</div>

<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
  <div class="container">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="#" data-toggle="modal" id="btn-preview-table" data-target="#preview-table"><span class="glyphicon glyphicon-eye-open"></span> Preview</a> </li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-export"></span>
          Export As <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#" data-toggle="modal" data-target="#export-dialog" class="export-type" data-type="CSV">CSV</a></li>
          <li><a href="#" data-toggle="modal" data-target="#export-dialog" class="export-type" data-type="XLS">Microsoft Excel 2000 (.XLS) </a></li>
          <li><a href="#" data-toggle="modal" data-target="#export-dialog" class="export-type" data-type="XLSX">Microsoft Excel 2010 (.XLSX) </a></li>
        </ul>
      </li>
    </ul>

    <ul class="nav navbar-nav navbar-left">
      <li><a href="#" data-toggle="modal" data-target="#save-dialog" id="btn-save-dialog"><span class="glyphicon glyphicon-floppy-disk"></span> Save</a>
    </ul>
  </div>
</nav>


<!-- Hidden Content -->
<div class="modal fade" id="preview-table" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Preview</h4>
      </div>
      <div class="modal-body">
        <!-- Table with preview data -->
        <div class="loader">Processing...</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="save-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Save Mappings</h4>
      </div>
      <div class="modal-body">
      @if(Auth::check())
        <div class="form-group">
          <label for="mapping-name-input">Save as </label>
          <input type="text" class="form-control mapping-name-input" placeholder="Give a name">
        </div>
        <div class="form-group">
          <label for="mapping-name-input">Little note</label>
          <input type="text" class="form-control mapping-des-input" placeholder="When, Why and what for">
        </div>
      @else
        <h4>Make yourself an Account.</h4>
        <strong>Features</strong>
        <ul>
          <li>Save your mappings.</li>
          <li>Apply, reapply and keep using the same mappings with any number of files.</li>
        </ul>
      @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      @if(Auth::check())
        <button type="button" class="btn btn-default btn-save-mappings">Save</button>
      @endif
      </div>
    </div>
  </div>
</div>

<!-- Export dialog -->
<div class="modal fade" id="export-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Export</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@stop
