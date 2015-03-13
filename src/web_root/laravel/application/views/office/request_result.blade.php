@layout('template_office')

@section('title')
          <div class="title"><p>請求書詳細</p>
            <p class="search_list"><a href="javascript:history.back();">&lt;&lt;検索結果一覧に戻る</a></p>
          </div>
@endsection
@section('main')
          <script type="text/javascript">
            var type = "{{ $type }}";
            var key  = "{{ $key }}";
          </script>
          <form method="post" action="" id="regF">
            <div class="request_result">
            <table style="width: 450px; margin-left: 130px;" id="bill_header">
              <tr>
                <th style="border-color: #DAE0DB #E5F0FC #E5F0FC #DAE0DB; width: 10em;">請求先</th>
                <th style="border-color: #DAE0DB #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  {{ $result['company_name'] }}
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">請求書No</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;" class="code">
                  {{ $result['bill_no'] }}
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">請求年月</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  {{ $result['bill_nen'] }}年 {{ $result['bill_tuki'] }}月
                </th>
              </tr>
              <tr>
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">請求金額</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                  \{{ number_format($result['total']) }}
                </th>
              </tr>
@if ( $type == RESULT_TYPE_BILL )
              <tr id="tr_payment">
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">入金日</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                   <input type="text" name="yyyy" size="4" value="{{ $result['payment_dateY'] }}" />年
                   <input type="text" name="mm"   size="4" value="{{ $result['payment_dateM'] }}" />月
                   <input type="text" name="dd"   size="4" value="{{ $result['payment_dateD'] }}" />日
                </th>
              </tr>
@endif
              <tr id="tr_bill_date1">
                <th style="border-color: #E5F0FC #E5F0FC #DAE0DB #DAE0DB;">請求書発行日</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
@if ( $result['bill_date'] )
                  <div style="float:left;padding-right: 4px;">{{ getLabelYMD($result['bill_date']) }}</div>
                  <input class="reprint" type="button" id="reprint_button" />
@endif
                </th>
              </tr>
              <tr id="tr_bill_date2">
                <th style="border-color: #E5F0FC #E5F0FC #E5F0FC #DAE0DB;">請求書発行日</th>
                <th style="border-color: #E5F0FC #DAE0DB #E5F0FC #E5F0FC; font-weight: bold;">
                   <input type="text" name="b_yyyy" size="4" value="{{ parseDateY($result['bill_date']) }}" />年
                   <input type="text" name="b_mm"   size="4" value="{{ parseDateM($result['bill_date']) }}" />月
                   <input type="text" name="b_dd"   size="4" value="{{ parseDateD($result['bill_date']) }}" />日
                </th>
              </tr>
              <tr>
                <td colspan="2" align="center" id="request_buttons">
                  <input class="{{ $type == RESULT_TYPE_BILL ? 'print' : 'batch' }}"   type="button" id="print_button" />
@if ( $type == RESULT_TYPE_BILL )
                  <input class="payment" type="button" id="payment_button" />
@if ( $result['payment_dateY'] )
                  <input class="receipt" type="button" id="receipt_button" />
@endif
@endif
                </td>
              </tr>

            </table>

            <p>■請求書内容内訳</p>
            <table>
              <tr align="center">
                <th>月日</th>
                <th>品名</th>
                <th>数量</th>
                <th>単価</th>
                <th>金額</th>
@if ( $result['meisai_type'] != RESULT_TYPE_CONST )
                <th>納入先</th>
                <th></th>
@endif
              </tr>

@foreach ( $result['meisai'] as $meisai )
              <tr>
                <td align="center">{{ $meisai['meisai_date_md'] }}</td>
                <td align="left">{{ $meisai['bill_name'] }}</td>
                <td align="right">{{ $meisai['quantity'] }}</td>
                <td align="right">\{{ number_format($meisai['price']) }}</td>
                <td align="right">\{{ number_format($meisai['sub_total']) }}</td>
@if ( $result['meisai_type'] != RESULT_TYPE_CONST )
                <td align="left">{{ $meisai['shipping_name'] }}</td>
@if ( $meisai['url'] )
                <td align="center"><a href="{{ $meisai['url'] }}">詳細</a></td>
@endif
@endif
              </tr>
@endforeach

            </table>


            </div>
          </form>
@endsection