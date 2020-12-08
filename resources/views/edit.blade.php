<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
        <title>Laravel</title>

    </head>
    <body>
    @php
        $date = date('Y-m-d', strtotime($start));
        $time = date('H:i', strtotime('9:00'));
        $nowDate = new dateTime(date('Y-m-d'));
        $nowTime = new dateTime(date('H:i'));
    @endphp
    @if (session('message'))
        <p class='alert alert-success text-center h3'>{{ session('message') }}</p>
    @endif
    <nav class="navbar navbar-expand-md  navbar-light bg-light">
  <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#bs-navi" aria-controls="bs-navi" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="bs-navi">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href={{ route('index') }}>予約する</a></li>
      <li class="nav-item"><a class="nav-link" href={{ route('user_page') }}>マイページ</a></li>
    </ul>
  </div>
</nav>
    <div class="container text-center my-3">
        <div class='row'>
            <div class='col d-flex align-items-center justify-content-start'>
                <a class='h5' href={{ route('edit', ['id' => $reserve->id, 'start' => date('Y-m-d', strtotime($start .'-1 week'))]) }}>< 前の週へ</a>
            </div>
            <div class='col d-flex align-items-center justify-content-center'>
                <span class='h3'> 予約変更</span>
            </div>
            <div class='col d-flex align-items-center justify-content-end'>
                <a class='h5' href={{ route('edit', ['id' => $reserve->id, 'start' => date('Y-m-d', strtotime($start .'+1 week'))]) }}>次の週へ ></a>
            </div>
        </div>
    </div>
    <div class="container text-center my-3">
        <div class='row'>
            <div class='col d-flex align-items-center justify-content-center border'>
                予約日
            </div>
            <div class='col d-flex align-items-center justify-content-center border'>
                開始時間
            </div>
            <div class='col d-flex align-items-center justify-content-center border'>
                終了時間
            </div>
        </div>
        <div class='row'>
            <div class='col d-flex align-items-center justify-content-center border'>
                <span id='old_date'>{{ date('Y/m/d', strtotime($reserve->start_time)) }}</span>
            </div>
            <div class='col d-flex align-items-center justify-content-center border'>
                <span id='old_start'>{{ date('H:i', strtotime($reserve->start_time)) }}</span>
            </div>
            <div class='col d-flex align-items-center justify-content-center border'>
                <span id='old_end'>{{ date('H:i', strtotime($reserve->end_time)) }}</span>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row border text-center">
            <div class="col border text-center">
                時間
            </div>
            @for ($i = 0; $i < 7; $i++)
            <div class="col border text-center">
                {{ date('m/d', strtotime($date ."+${i} day")) }}
            </div>
            @endfor
        </div>
        @for ($i = 0; $i < 17; $i++)
        @php
            $start = 30 * $i;
            $end = 30 * ($i + 1);
            $startTime = date('H:i', strtotime($time ."+${start} minute"));
            $endTime = date('H:i', strtotime($time ."+${end} minute"));
            $startDateTime = new DateTime($startTime);
        @endphp
        <div class="row border">
            <div class="col border">
               <div class='text-center'>{{ $startTime }}</div>
                <div class='text-center'>~</div>
                <div class='text-center'>{{ $endTime }}</div>
            </div>
            @for ($j = 0; $j < 7; $j++)
            @php
                $cellDate = date('Y-m-d', strtotime($date ."+${j} day"));
                $cellDateTime = new DateTime($cellDate);
            @endphp
            <div class="col border d-flex align-items-center justify-content-center">
                @if ($nowDate > $cellDateTime)
                    <span>×</span>
                @elseif ($nowDate == $cellDateTime && $startDateTime < $nowTime)
                <span>×</span>
                @elseif (isset($reserves[$cellDate][$startTime]))
                    <span>×</span>
                @else
                    <a href='#'
                        data-start="{{$cellDate}} {{$startTime}}" 
                        data-end="{{$cellDate}} {{$endTime}}"
                        class="reserve text-primary">○</a>
                @endif
            </div>
            @endfor
        </div>
        @endfor
    </div>
    <form method='POST' action={{ route('update') }} id='reserve_form'>
        @csrf
        <input name='id' type='hidden' value={{ $reserve->id }}>
        <input name='start_time' type='hidden' value="" id='start_time'>
        <input name='end_time' type='hidden' value="" id='end_time'>
        <input name='court_id' type='hidden' value="1">
    </form>
    <script type='module' src="{{ mix('js/update.js') }}"></script>
    </body>
</html>
