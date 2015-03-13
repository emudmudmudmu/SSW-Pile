@layout('template_office')

@section('title')
          <div class="title"><p>受注情報閲覧/変更</p></div>
@endsection
@section('main')
          <form method="post" action="" id="searchF">
            <div class="order_search">
              <table>
                <tr>
                  <th align="left">受注No</th>
                  <td>
                    <input type="text" name="order_no" size="50" value="{{ $order_no }}" />
                  </td>
                </tr>
                <tr>
                  <th align="left">施工会社名</th>
                  <td>
                    <select name="company_code" id="company_code">
                      <option value=""></option>
@foreach ( $companies as $company )
                      <option value="{{ pad3($company->company_code) }}"{{ $company->company_code == $company_code ? ' selected' : ''}}>{{ $company->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                <tr>
                  <th align="left">注文日</th>
                  <td>
                    <input type="text" name="s_yyyy" size="4" value="{{ $s_yyyy }}" />年
                    <input type="text" name="s_mm" size="4" value="{{ $s_mm }}" />月
                    <input type="text" name="s_dd" size="4" value="{{ $s_dd }}" />日～

                    <input type="text" name="e_yyyy" size="4" value="{{ $e_yyyy }}" />年
                    <input type="text" name="e_mm" size="4" value="{{ $e_mm }}" />月
                    <input type="text" name="e_dd" size="4" value="{{ $e_dd }}" />日
                  </td>
                </tr>
                <tr>
                  <th align="left">検索区分</th>
                  <td>
                    <table style="width: 100%; margin: 0px;">
                      <tr>
@foreach( $status_list as $k => $v )
                        <td class="td_status"><input type="checkbox" name="status[]" value="{{ $v }}" id="status{{ $v }}"{{ (in_array($v, $status) ? ' checked' : '' ) }} /><label for="status{{ $v }}">{{ $k }}</label></td>
@endforeach
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />

            </div>

@if ( isset($result) && 0 < count($result) )
            <div class="order_search_result">
              <table>
                <tr>
                <th>注文日</th><th>No</th><th>品名</th><th>本数</th><th>納入日</th><th>ステータス</th><th></th>
                </tr>

@foreach( $result as $order )
                <tr>
                  <td align="center">{{ parseDate($order->order_date) }}</td>
                  <td align="center">{{ $order->order_no }}</td>
                  <td>
<?php $style = '';?>
@foreach( $order->meisais as $meisai )
                    <div style="clear: both;{{ $style }}">
	                  <div style="float:left;">{{ $meisai->item_size }}</div>
                      <div style="float:right;">{{ getLabelBy('weldings', $meisai->item_type) }}</div>
                    </div>
<?php $style = 'border-top:dashed 1px silver;';?>
@endforeach
                    
                  </td>
                  <td>
<?php $style = '';?>
@foreach( $order->meisais as $meisai )
                    <div style="text-align:right;{{ $style }}">
                      {{ $meisai->quantity }}本
                    </div>
<?php $style = 'border-top:dashed 1px silver;';?>
@endforeach
                  </td>
                  <td align="center">{{ parseDate($order->arrival_date) }}</td>
                  <td align="center">{{ getLabelBy('order', $order->order_status) }}</td>
                  <td align="center"><a href="{{ URL::to("office/{$order->order_no}/order_detail.html") }}">詳細</a></td>
                </tr>
@endforeach
              </table>
            </div>
@else
            <p align="center" style="margin-top:4em;">検索条件に一致する受注情報がありません。</p>
@endif
            
          </form>
@endsection