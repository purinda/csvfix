@if ($rows === false)
<div class="alert alert-warning">
<strong>Hmmm...!</strong><br>
There seems to be a problem with Output Columns you have specified, either they are not linked to any source columns or no Column Name entered.
</div>
@else
<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped" width="100%">
  <thead>
    <tr>
      @foreach($columns as $column)
      <th>{{ $column }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
      @foreach($rows as $row)
      <tr>
        @foreach($row as $column)
        <td>{{ $column }}</td>
        @endforeach
      </tr>
      @endforeach
  </tbody>
  <tfoot>
    <tr>
      @foreach($columns as $column)
      <th>{{ $column }}</th>
      @endforeach
    </tr>
  </tfoot>
</table>
@endif
