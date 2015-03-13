@layout('template_construction')

@section('title')
          <div class="title"><p>協会からのお知らせ</p></div>
@endsection
@section('main')
          <div class="notice">

<!-- loop start -->
@foreach ( $d_news as $record )
            <dl class="clearfix">
              <dt>・{{ parseDate($record->news_date) }}</dt>
              <dd><a href="{{ $record->news_id }}/top_detail.html">{{ $record->news_title }}</a></dd>
            </dl>
@endforeach
<!-- loop end -->

          </div>
@endsection