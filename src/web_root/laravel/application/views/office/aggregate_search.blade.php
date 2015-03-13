@layout('template_office')
@section('title')
          <div class="title"><p>会社別集計</p></div>
@endsection
@section('main')
          <form method="post" action="">
            <div class="aggregate_search">
              <table>
                <tr>
                  <th align="left">集計期間</th>
                  <td>
                    <input type="text" id="s_yyyy" size="4" />年
                    <input type="text" id="s_mm" size="4" />月
                    <input type="text" id="s_dd" size="4" />日～

                    <input type="text" id="e_yyyy" size="4" />年
                    <input type="text" id="e_mm" size="4" />月
                    <input type="text" id="e_dd" size="4" />日
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />

            </div>

          </form>
@endsection