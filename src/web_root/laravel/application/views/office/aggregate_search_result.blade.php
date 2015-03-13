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
                    <input type="text" id="s_yyyy" size="4"  value="{{ $input['s_yyyy'] }}"/>年
                    <input type="text" id="s_mm" size="4"  value="{{ $input['s_mm'] }}"/>月
                    <input type="text" id="s_dd" size="4"  value="{{ $input['s_dd'] }}"/>日～

                    <input type="text" id="e_yyyy" size="4"  value="{{ $input['e_yyyy'] }}"/>年
                    <input type="text" id="e_mm" size="4"  value="{{ $input['e_mm'] }}"/>月
                    <input type="text" id="e_dd" size="4"  value="{{ $input['e_dd'] }}"/>日
                  </td>
                </tr>
              </table>
              <input class="search_button" id="search_button" type="button" />

            </div>

<?php
	$data = NULL;
	$dd = NULL;
	$company_code = "";
	$index = 0;
	foreach($d_bill as $bill) {
		if($company_code <> $bill->company_code){
			if( $dd != NULL ) {
				if($data == NULL) {
					$data = array($index => $dd);
					$index++;
				}
				else {
					array_push($data,$index++,$dd);
				}
			}

			$company_code = $bill->company_code;
			$count = $bill->count;
			$dd = NULL;
			$dd = array(
				'company_code' => $bill->company_code,
				'company_name' => $bill->company_name,
				'count' => $bill->count
			);
		}

		if( $bill->bill_type == '1' ) {
			$dd['type1'] = $bill->price;
		}
		if( $bill->bill_type == '2' ) {
			$dd['type2'] = $bill->price;
		}
	}
	if( $dd != NULL ) {
		if($data == NULL) {
			$data = array($index => $dd);
			$index++;
		}
		else {
			array_push($data,$index++,$dd);
		}
	}
?>
@if ( $data != NULL )
            <div class="aggregate_search_result">
              <table>
                <tr>
                <th>指定施工会社</th><th>工事件数</th><th>工法使用料</th><th>パーツ購入額</th>
                </tr>

<!-- loop start -->
@foreach($data as $bill)
<?php if(!isset($bill['company_name'])) continue;?>
                <tr>
                  <td align="left"><?php echo $bill['company_name'];?></td>
                  <td align="center"><?php echo $bill['count'];?></td>
                  <td align="center"><?php if(isset($bill['type1'])) {echo $bill['type1'];}else{echo " ";}?></td>
                  <td align="center"><?php if(isset($bill['type2'])) {echo $bill['type2'];}else{echo " ";}?></td>
                </tr>
@endforeach
<!-- loop end -->
              </table>

              <input class="aggregate_button" type="button" id="csv_button"  />

            </div>
@else
            <p align="center" style="margin-top:4em;">
              検索条件に一致する発注情報がありません。<br />
            </p>
@endif

          </form>
@endsection