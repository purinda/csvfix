<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-condensed table-striped" width="100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>Note</th>
      <th>Added on</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
      @foreach($sessions as $saved_session)
      <tr>
        <td>{{ $saved_session->name }}</td>
        <td>{{ $saved_session->description }}</td>
        <td></td>
        <td>
            <div class="btn-group pull-right">
              <button type="button" class="btn btn-xs btn-danger btn-erase-mapping" data-id="{{ $saved_session->id }}">Erase</button>
              <button type="button" class="btn btn-xs btn-success btn-open-mapping" data-id="{{ $saved_session->id }}">Open</button>
            </div>
        </td>
      </tr>
      @endforeach

      @if (empty($sessions))
      <tr>
        <td colspan="4">No saved mappings found.</td>
      </tr>
      @endif
  </tbody>
</table>
