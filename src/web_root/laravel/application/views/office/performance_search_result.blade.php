@layout('template_office')
@section('title')
          <div class="title"><p>実績表出力</p></div>
@endsection
@section('main')
          <form method="post" action="" id="searchF">
            <div class="performance_search">
              <table>
                <tr>
                  <th align="left">集計期間</th>
                  <td>
                    <input type="text" name="e_s_yyyy" id="e_s_yyyy" size="4"  value="{{ $input['e_s_yyyy'] }}"/>年
                    <input type="text" name="e_s_mm"   id="e_s_mm"   size="4"  value="{{ $input['e_s_mm'] }}"/>月
                    <input type="text" name="e_s_dd"   id="e_s_dd"   size="4"  value="{{ $input['e_s_dd'] }}"/>日～

                    <input type="text" name="e_e_yyyy" id="e_e_yyyy" size="4"  value="{{ $input['e_e_yyyy'] }}"/>年
                    <input type="text" name="e_e_mm"   id="e_e_mm"   size="4"  value="{{ $input['e_e_mm'] }}"/>月
                    <input type="text" name="e_e_dd"   id="e_e_dd"   size="4"  value="{{ $input['e_e_dd'] }}"/>日
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />
            </div>

            <div class="performance_search_result">
              <table>
                <tr>
                  <th>認定番号</th>
                  <th>工事名</th>
                  <th>工事場所</th>
                  <th>幹事会社</th>
                  <th>工期</th>
                </tr>

<!-- loop start -->
@foreach($d_construction as $construction)
                <tr>
                  <td align="left" class="code">{{ parseConstructionNo($construction->construction_no) }}</td>
                  <td align="left">{{ $construction->construction_name }}</td>
                  <td align="left">{{ $construction->construction_address }}</td>
                  <td align="left" nowrap>{{ $construction->construction_company_entity->company_name }}</td>
                  <td align="left"><span style="white-space: nowrap;">{{ $construction->construction_start_date }}～</span><br /> {{ $construction->complete_date }}</td>
                </tr>
@endforeach
<!-- loop end -->
              </table>

              <table style="margin: 0px; width=100%;">
                <tr>
                  <td style="border: none; margin: 0px; width=50%;">
                    <input class="pdf_button" type="button" id="pdf_button" />
                  </td>
                  <td style="border: none; margin: 0px; width=50%;">
                    <input class="csv_button" type="button" id="csv_button" />
                  </td>
                </tr>
              </table>

            </div>
          </form>
@endsection