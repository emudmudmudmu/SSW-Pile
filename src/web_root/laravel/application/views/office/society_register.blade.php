@layout('template_office')

@section('title')
            <div class="title"><p>協会基本情報</p></div>
@endsection
@section('main')
          <form method="post">
            <div class="society_register">
              <div class="society_register_text">
                <p class="p_subject">登録内容を変更する場合は、変更箇所を訂正し〔更新する〕を押してください。</p>
              </div>
<?php
foreach($m_basicinfo as $record) {
	if( $record->basicinfo_id =='1'){
		$association = $record->info_value;
	}
	if( $record->basicinfo_id =='2'){
		$zip1 = $record->info_value;
	}
	if( $record->basicinfo_id =='3'){
		$zip2 = $record->info_value;
	}
	if( $record->basicinfo_id =='4'){
		$address = $record->info_value;
	}
	if( $record->basicinfo_id =='5'){
		$comany = $record->info_value;
	}
	if( $record->basicinfo_id =='6'){
		$tel = $record->info_value;
	}
	if( $record->basicinfo_id =='7'){
		$fax = $record->info_value;
	}
	if( $record->basicinfo_id =='8'){
		$tanto = $record->info_value;
	}
	if( $record->basicinfo_id =='9'){
		$email = $record->info_value;
	}
	if( $record->basicinfo_id =='10'){
		$syukka = $record->info_value;
	}
	if( $record->basicinfo_id =='11'){
		$bank_name1 = $record->info_value;
	}
	if( $record->basicinfo_id =='12'){
		$bank_name2 = $record->info_value;
	}
	if( $record->basicinfo_id =='13'){
		$bank_sybt = $record->info_value;
	}
	if( $record->basicinfo_id =='14'){
		$bank_no = $record->info_value;
	}
	if( $record->basicinfo_id =='15'){
		$bank_meigi = $record->info_value;
	}

}
?>
              <table>
                <tr>
                  <th><p class="th_p">名称</p></th>
                  <td>
                    <input type="text" id="association" size="50"  value="<?php echo $association;?>">
                  </td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th colspan="2"><p class="th_p">【事務局住所】</p></th>
                </tr>
                <tr>
                  <th><p class="th_p">〒</p></th>
                  <td>
                    <input type="text" id="zip1" size="10" value="<?php echo $zip1;?>"> -
                    <input type="text" id="zip2" size="10" value="<?php echo $zip2;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">住所</p></th>
                  <td>
                    <input type="text" id="address" size="50" value="<?php echo $address;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">会社名</p></th>
                  <td>
                    <input type="text" id="comany" size="50" value="<?php echo $comany;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">電話番号</p></th>
                  <td>
                    <input type="tel" id="tel" size="25" value="<?php echo $tel;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">FAX番号</p></th>
                  <td>
                    <input type="tel" id="fax" size="25" value="<?php echo $fax;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">担当</p></th>
                  <td>
                    <input type="text" id="tanto" size="50" value="<?php echo $tanto;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">E-Mail</p></th>
                  <td>
                    <input type="email" id="email" size="50" value="<?php echo $email;?>">
                  </td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th colspan="2"><p class="th_p">【パーツ出荷場所】</p></th>
                </tr>
                <tr>
                  <th><p class="th_p">会社名</p></th>
                  <td>
                    <select id="syukka" value="<?php echo $syukka;?>">
@foreach($companies as $ss) {
                      <option value="{{ $ss->company_code }}" <?php if($ss->company_code == $syukka ) echo ' selected '?>>{{ $ss->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th colspan="2"><p class="th_p">【口座情報】</p></th>
                </tr>
                <tr>
                  <th><p class="th_p">銀行名</p></th>
                  <td>
                    <input type="text" id="bank_name1" size="50" value="<?php echo $bank_name1;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">支店名</p></th>
                  <td>
                    <input type="text" id="bank_name2" size="50" value="<?php echo $bank_name2;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">預金種別</p></th>
                  <td>
                    <input type="text" id="bank_sybt" size="20" value="<?php echo $bank_sybt;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">口座番号</p></th>
                  <td>
                    <input type="text" id="bank_no" size="50" value="<?php echo $bank_no;?>">
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">名義人</p></th>
                  <td>
                    <input type="text" id="bank_meigi" size="50" value="<?php echo $bank_meigi;?>">
                  </td>
                </tr>
              </table>

              <input class="society_register" type="button" id="submit">
            </div>
          </form>

@endsection