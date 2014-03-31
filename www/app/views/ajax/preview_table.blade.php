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
