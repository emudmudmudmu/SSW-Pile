@layout('template_office')

@section('title')
          <div class="title"><p>事務局からのお知らせ</p></div>
@endsection
@section('main')
          <form method="post" action="news_finish.html">
            <input type="hidden" id="news_date" value="{{ $input['news_date'] }}">
            <input type="hidden" id="news_title" value="{{ $input['news_title'] }}">
            <input type="hidden" id="news_content" value="{{ $input['news_content'] }}">

            <div class="news_check">
              <table>
                <tr>
                  <th>日付</th>
                  <td>
                    {{ $input['news_date'] }}
                  </td>
                </tr>
                <tr>
                  <th>タイトル</th>
                  <td>
                    {{ $input['news_title'] }}
                  </td>
                </tr>
                <tr>
                  <th>本文</th>
                  <td>
                    <textarea readonly cols="50" rows="23" id="news_content" style="resize: none;">{{ $input['news_content'] }}</textarea>
                  </td>
                </tr>
              </table>

              <table>
                <tr>
                  <td class="o_id">
                    <input class="reg_back" type="button" name="button" onclick="history.back();">
                  </td>
                  <td class="o_id">
                    <input class="reg_button" type="button" id="new">
                  </td>
                </tr>
              </table>

            </div>
          </form>

@endsection