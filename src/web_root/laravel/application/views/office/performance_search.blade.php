@layout('template_office')
@section('title')
          <div class="title"><p>実績表出力</p></div>
@endsection
@section('main')
          <form method="post" action="">
            <div class="performance_search">
              <table>
                <tr>
                  <th align="left">集計期間</th>
                  <td>
                    <input type="text" name="e_s_yyyy" id="e_s_yyyy" size="4" />年
                    <input type="text" name="e_s_mm"   id="e_s_mm"   size="4" />月
                    <input type="text" name="e_s_dd"   id="e_s_dd"   size="4" />日～

                    <input type="text" name="e_e_yyyy" id="e_e_yyyy" size="4" />年
                    <input type="text" name="e_e_mm"   id="e_e_mm"   size="4" />月
                    <input type="text" name="e_e_dd"   id="e_e_dd"   size="4" />日
                                      
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />
            </div>

          </form>
@endsection