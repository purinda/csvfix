@foreach($session->getMergeFields() as $i => $merge_field)
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
            <input data-name="merge_field[]" type="email" class="form-control input-sm" placeholder="Full name, etc" value="{{ $merge_field }}">

            <div class="input-group-btn dropup">
              <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                <span class="glyphicon glyphicon-list-alt"></span>
                Map <span class="caret"></span>
              </button>
              <ul class="dropdown-menu pull-right" role="menu">
                <li class="divider"></li>
                <li><a href="#" class="btn-add-column" data-column="text">Add Text</a></li>
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
            @foreach($session->getColumns($i) as $column)
            <tr>
                <td>{{ $column }}</td>
                <td><input data-name="column[][{{$column}}]" type="hidden" value="{{$column}}"></td>
                <td><div class="col-sm-11 no-padding"><input data-name="column_separator[][{{$column}}]" type="text" class="form-control input-sm"></div></td>
                <td><button type="button" class="btn btn-danger btn-xs btn-remove-column"> <span class="glyphicon glyphicon-remove"></span> Remove</button></td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="panel-footer">
    Use "Add" button to put another column into the export CSV or use "Remove" to take out this column.
    <div class="btn-group pull-right">
      <button class="btn btn-success btn-sm btn-add-group-mapping"><span class="glyphicon glyphicon-plus"></span> Add</button>
      <button class="btn btn-danger btn-sm btn-remove-group-mapping"><span class="glyphicon glyphicon-remove"></span> Remove</button>
    </div>
    <div class="clearfix"></div>
  </div>
</div>

@endforeach
