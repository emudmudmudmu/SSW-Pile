@layout('template_parts')

@section('title')
          <div class="title"><p>伝票発行/出荷処理</p></div>
@endsection
@section('main')
          <form method="post" action="" id="searchF">
            <div class="slip_search">
              <table>
                <tr>
                  <th align="left">受注No</th>
                  <td>
                    <input type="text" name="order_no" size="50" />
                  </td>
                </tr>
                <tr>
                  <th align="left">施工会社名</th>
                  <td>
                    <select name="company_code" id="company_code">
                      <option value=""></option>
@foreach ( $companies as $company )
                      <option value="{{ pad3($company->company_code) }}">{{ $company->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                <tr>
                  <th align="left">注文日</th>
                  <td>
                    <input type="text" name="s_yyyy" size="4" />年
                    <input type="text" name="s_mm" size="4" />月
                    <input type="text" name="s_dd" size="4" />日～

                    <input type="text" name="e_yyyy" size="4" />年
                    <input type="text" name="e_mm" size="4" />月
                    <input type="text" name="e_dd" size="4" />日
                  </td>
                </tr>
                <tr>
                  <th align="left">検索区分</th>
                  <td>
                    <table style="width: 100%; margin: 0px;">
                      <tr>
@foreach( $status_list as $k => $v )
                        <td class="td_status"><input type="checkbox" name="status[]" value="{{ $v }}" id="status{{ $v }}" /><label for="status{{ $v }}">{{ $k }}</label></td>
@endforeach
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />

            </div>
          </form>
@endsection