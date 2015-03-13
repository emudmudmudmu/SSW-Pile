@layout('template_office')

@section('title')
          <div class="title"><p>消費税設定</p></div>
@endsection
@section('main')
          <script type="text/javascript">
            function insert(id){
                document.getElementById('ctax_id').value = id;
            }
          </script>


          <form method="post">
            <div class="system_tax">
              <table>
                <tr>
                <th>開始</th><th>終了</th><th>税率（％）</th><th></th>
                </tr>

<!-- loop start -->
@foreach ( $m_ctax as $record )
                <tr>
                  <td>
                    <input type="text" id="start_date_{{ $record->ctax_id }}" value="{{ parseDate($record->start_date) }}" size="20">
                  </td>
                  <td>
                    <input type="text" id="end_date_{{ $record->ctax_id }}" value="{{ parseDate($record->end_date) }}"" size="20">
                  </td>
                  <td>
                    <input type="text" id="rate_{{ $record->ctax_id }}" value="{{ $record->rate }}" size="20">
                  </td>
                  <td>
                    <input type="button" class="reg_button" id="update_button" onclick="insert('{{ $record->ctax_id }}');">
                    <input type="button" class="del_button" id="delete_button" onclick="insert('{{ $record->ctax_id }}');">
                  </td>
                </tr>
@endforeach
<!-- loop end -->
                <input type="hidden" id="ctax_id"  value="" />
                <tr>
                  <td>
                    <input type="text" id="start_date" value="" size="20">
                  </td>
                  <td>
                    <input type="text" id="end_date" value="" size="20">
                  </td>
                  <td>
                    <input type="text" id="rate" value="" size="20">
                  </td>
                  <td>
                    <input type="button" class="reg_button" id="new_button">
                  </td>
                </tr>
              </table>

            </div>
          </form>

@endsection