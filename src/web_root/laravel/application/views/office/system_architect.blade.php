@layout('template_office')

@section('title')
          <div class="title"><p>設計担当者設定</p></div>
@endsection
@section('main')
          <script type="text/javascript">
            function insert(id){
                document.getElementById('no').value = id;
            }
          </script>

          <form method="post">
            <div class="system_architect">
              <table>
                <tr>
                <th>No</th><th>氏名</th><th>所属</th><th>認定日</th><th></th>
                </tr>

<!-- loop start -->
@foreach ( $m_architect as $record )
                <tr>
                  <td>
                    {{ $record->no }}
                  </td>
                  <td>
                    <input type="text" id="name_{{ $record->no }}" value="{{ $record->name }}" size="20">
                  </td>
                  <td>
                    <select id="company_code_{{ $record->no }}">
@foreach ( $companies as $cc )
                      <option value="{{ $cc->company_code }}" <?php if($cc->company_code ==$record->company_code ) echo ' selected '?>>{{ $cc->company_name}}</option>
@endforeach
                    </select>
                  </td>
                  <td>
                    <input type="text" id="certificated_{{ $record->no }}" value="{{ parseDate($record->certificated) }}" size="20">
                  </td>
                  <td>
                    <input type="button" class="reg_button" id="update_button" onclick="insert('{{ $record->no }}');">
                    <input type="button" class="del_button" id="delete_button" onclick="insert('{{ $record->no }}');">
                  </td>
                </tr>
@endforeach
<!-- loop end -->
                <input type="hidden" id="no" value=""/>
                <tr>
                  <td>
                  </td>
                  <td>
                    <input type="text" id="name" value="" size="20">
                  </td>
                  <td>
                    <select id="company_code">
@foreach ( $companies as $cc )
                      <option value="{{ $cc->company_code }}" >{{ $cc->company_name}}</option>
@endforeach
                    </select>
                  </td>
                  <td>
                    <input type="text" id="certificated" value="" size="20">
                  </td>
                  <td>
                    <input type="button" class="reg_button" id="new_button">
                  </td>
                </tr>
              </table>

            </div>
          </form>

@endsection