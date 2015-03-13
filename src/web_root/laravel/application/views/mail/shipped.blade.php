@layout('template_mail')
@section('body')
{{ $shipping_company }} {{ $shipping_person }} 様

※ このメールは自動送信メールです。

いつも大変お世話になっております。
SSW-Pile工法協会 事務局 からのお知らせです。

このたび SSW-Pile工法協会Webサイトより、{{ getLabelYMD($order_date) }}に
承ったご注文について、次の通りに出荷が行なわれましたので、
ご連絡いたします。

------------------------------------------------------------
出荷日　　　　：{{ getLabelYMD($shipping_date) . LF }}
到着予定日　　：{{ getLabelYMD($delivery_date) . LF }}

運送会社　　　：{{ $shipping_agent . LF }}
運賃　　　　　：\{{ number_format($shipping_fee) . LF }}

@if ( $agent_tel )
問い合わせ TEL：{{ $agent_tel . LF }}
@endif
@if ( $agent_inqno )
問い合わせ No.：{{ $agent_inqno . LF }}
@endif

ご請求金額（税込）：\{{ number_format( $total ) . LF }}
------------------------------------------------------------


ご注文内容は下記の通りです。

------------------------------------------------------------
注文番号　　　：{{ $order_no . LF }}

納入会社　　　：{{ $shipping_company . LF }}
納入場所　　　：{{ $shipping_name . LF }}
納入先住所　　：{{ $shipping_address . LF }}
納入担当者　　：{{ $shipping_person . LF }}
メールアドレス：{{ $email . LF }}
電話番号　　　：{{ $tel . LF }}
 FAX番号　　　：{{ $fax . LF }}

納入希望日　　：{{ getLabelYMD($arrival_date) . LF }}

【発注内容】
@foreach ( $meisai as $m )
先端翼径　：{{ str_pad($m->item_size, 10, ' ', STR_PAD_LEFT) . ' ' . str_pad($m->quantity , 3, ' ', STR_PAD_LEFT) . '本 ' . str_pad(getLabelBy('weldings', $m->item_type) , 5, '　', STR_PAD_LEFT) }},
　　単価　：{{ numberFormatFor(10, $m->item_sprice, '\\') . LF }}
　　小計　：{{ numberFormatFor(10, ($m->item_sprice * $m->quantity), '\\') . LF }}

@endforeach

代金合計　：{{ numberFormatFor(10, $subtotal + $tax, '\\') }}（税込）
内、消費税：{{ numberFormatFor(10, $tax, '\\') . LF }}
------------------------------------------------------------


ご不明な点がございましたら、このメールの末尾に記載されている
連絡先までお問い合わせください。

よろしくお願いいたします。
@endsection