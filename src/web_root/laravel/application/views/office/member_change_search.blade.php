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
                    <input type="text" name="company_code" size="50" maxlength="3" />
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
          </form>
@endsection