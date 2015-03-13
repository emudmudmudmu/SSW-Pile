@layout('template_office')

@section('title')
          <div class="title"><p>事務局からのお知らせ</p></div>
@endsection
@section('main')
          <script type="text/javascript">
            function insert(id){
                document.getElementById('news_id').value = id;
            }
          </script>

          <form method="post" action="news_check.html">
            <div class="news">
              <p class="inf"><新規追加></p>
              <table>
                <tr>
                  <th>日付</th>
                  <td>
                    <input type="text" id="news_date" value="" size="10">
                  </td>
                </tr>
                <tr>
                  <th>タイトル</th>
                  <td>
                    <input type="text" id="news_title" value="" size="90">
                  </td>
                </tr>
                <tr>
                  <th>本文</th>
                  <td>
                    <textarea cols="70" rows="26" id="news_content" style="resize: none;"></textarea>
                  </td>
                </tr>
              </table>

              <input type="button" class="reg_button" id="reg_button">

              <p class="inf"><公開中></p>

              <table>

<!-- loop start -->
@foreach ( $d_news as $record )
                <tr>
                  <td class="dot_td" style="width 100px;">・
                    {{ parseDate($record->news_date) }}
                  </td>
                  <td class="dot_td" style="width 400px;">
                    {{ $record->news_title }}
                  </td>
                  <td class="dot_td">
                    <input type="button" class="del_button" id="delete_button" onclick="insert('{{ $record->news_id }}');">
                  </td>
                </tr>
@endforeach
<!-- loop end -->

              <input type="hidden" id="news_id" value="" />

              </table>
            </div>
          </form>

@endsection