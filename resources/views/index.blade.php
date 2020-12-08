<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link href="css/app.css" rel="stylesheet" type="text/css">
        <title>予約ページ</title>

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
    <a href="#" class="navbar-brand h3">テニス壁打ちコート予約サイト</a>
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link h4" href={{ route('index') }}>予約する</a></li>
      <li class="nav-item"><a class="nav-link h4" href={{ route('user_page') }}>マイページ</a></li>
    </ul>
  </div>
</nav>
    <div class="container-fluid text-center my-5">
        <div class='row'>
            <div class='col d-flex align-items-center justify-content-start'>
                <a class='h3' href={{ route('index', ['start' => date('Y-m-d', strtotime($start .'-1 week'))]) }}>< 前の週へ</a>
            </div>
            <div class='col d-flex align-items-center justify-content-center'>
                <span class='h1'> 予約一覧</span>
            </div>
            <div class='col d-flex align-items-center justify-content-end'>
                <a class='h3' href={{ route('index', ['start' => date('Y-m-d', strtotime($start .'+1 week'))]) }}>次の週へ ></a>
            </div>
        </div>
    </div>
    <div class="container-fluid my-5">
        <div class="row border text-center">
            <div class="col border text-center h2 my-0">
                時間
            </div>
            @for ($i = 0; $i < 7; $i++)
            <div class="col border text-center h2 my-0">
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
               <div class='text-center h2 my-0'>{{ $startTime }}</div>
                <div class='text-center h2 my-0'>~</div>
                <div class='text-center h2 my-0'>{{ $endTime }}</div>
            </div>
            @for ($j = 0; $j < 7; $j++)
            @php
                $cellDate = date('Y-m-d', strtotime($date ."+${j} day"));
                $cellDateTime = new DateTime($cellDate);
            @endphp
            <div class="col border d-flex align-items-center justify-content-center">
                @if ($nowDate > $cellDateTime)
                    <span class='h2 my-0'>×</span>
                @elseif ($nowDate == $cellDateTime && $startDateTime < $nowTime)
                <span class='h2 my-0'>×</span>
                @elseif (isset($reserves[$cellDate][$startTime]))
                    <span class='h2 my-0'>×</span>
                @else
                    <a href='#'
                        data-start="{{$cellDate}} {{$startTime}}" 
                        data-end="{{$cellDate}} {{$endTime}}"
                        class="reserve text-danger h2 my-0">○</a>
                @endif
            </div>
            @endfor
        </div>
        @endfor 
    </div>
    <form method='POST' action={{ route('create') }} id='reserve_form'>
        @csrf
        <input name='start_time' type='hidden' value="" id='start_time'>
        <input name='end_time' type='hidden' value="" id='end_time'>
        <input name='court_id' type='hidden' value="1">
    </form>
    <script type='module' src="{{ mix('js/index.js') }}"></script>
    </body>
</html>
