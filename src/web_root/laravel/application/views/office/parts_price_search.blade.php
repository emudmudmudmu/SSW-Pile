@layout('template_office')

@section('title')
          <div class="title"><p>パーツ代金精算</p></div>
@endsection
@section('main')
          <form method="post" action="{{ URL::to('office/parts_price_search_result.html') }}" id="searchF">
            <div class="parts_price_search">
              <table>
                <tr>
                  <th align="left">精算年月</th>
                  <td>
                    <input type="text" name="yyyy" size="4" />年
                    <input type="text" name="mm"   size="4" />月
                  </td>
                </tr>
              </table>
              <input class="search_button" id="pay_search_button" type="button" />

            </div>
          </form>
@endsection