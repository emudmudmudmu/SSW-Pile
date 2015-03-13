@layout('template_office')

@section('title')
          <div class="title"><p>材種設定</p></div>
@endsection
@section('main')
          <script type="text/javascript">
            function insert(id){
                document.getElementById('material_id').value = id;
            }
          </script>

          <form method="post" action="">
            <div class="system_material">
              <table>
                <tr>
                <th>材種コード</th><th>名称</th><th></th>
                </tr>

<!-- loop start -->
@foreach ( $m_material as $record )
                <tr>
                  <td>
                    <input type="text" id="material_code_{{ $record->material_id }}" value="{{ $record->material_code }}" size="20">
                  </td>
                  <td>
                    <input type="text" id="material_name_{{ $record->material_id }}" value="{{ $record->material_name }}" size="50">
                  </td>
                  <td>
                    <input type="button" class="reg_button" id="update_button" onclick="insert('{{ $record->material_id }}');">
                    <input type="button" class="del_button" id="delete_button" onclick="insert('{{ $record->material_id }}');">
                  </td>
                </tr>
@endforeach
<!-- loop end -->
                <input type="hidden" id="material_id"  value="" />
                <tr>
                  <td>
                    <input type="text" id="material_code" value="" size="20">
                  </td>
                  <td>
                    <input type="text" id="material_name" value="" size="50">
                  </td>
                  <td>
                    <input type="button" class="reg_button" id="new_button">
                  </td>
                </tr>
              </table>

            </div>
          </form>

@endsection