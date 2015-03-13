@layout('template_office')
@section('title')
          <div class="title"><p>会員情報閲覧/変更</p></div>
@endsection
@section('main')
          <form method="post" action="" id="searchF">
            <div class="member_change_search">
              <table>
                <tr>
                  <th align="left">会社コード</th>
                  <td>
                    <input type="text" name="company_code" size="50" maxlength="3" value="{{ $condition }}" />
                  </td>
                </tr>
                <tr>
                  <th align="left">会社名</th>
                  <td>
                    <select name="company">
                      <option value=""></option>
@foreach ( $companies as $c )
                      <option value="{{ pad3($c->company_code) }}">{{ $c->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
              </table>
              <input class="search_button" id="search_button" type="button" />

              <p class="s_p">一覧を表示したい場合は空白のまま〔検索〕を押してください。</p>

            </div>
            
            <div class="member_change_search_result">
              <table>
                <tr>
                <th>会社名</th><th>住所</th><th>電話番号</th><th></th>
                </tr>
@foreach ( $result as $c )
                <tr>
                  <td align="left">{{ $c->company_name }}</td>
                  <td align="left">{{ $c->address }}</td>
                  <td align="center">{{ $c->tel }}</td>
                  <td align="center"><a href="{{ URL::to('office/' . pad3($c->company_code) . '/member_change.html') }}">詳細</a></td>
                </tr>
@endforeach
              </table>
            </div>
            
          </form>
@endsection