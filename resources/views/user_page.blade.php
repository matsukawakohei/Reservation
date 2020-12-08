<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link href="css/app.css" rel="stylesheet" type="text/css">
        <title>マイページ</title>

    </head>
    <body>
    @if (session('message'))
        <p class='alert alert-success text-center h3'>{{ session('message') }}</p>
    @endif
    @php
        $userName = Auth::user()->name;
        $count = 1;
        $now = date('Y-m-d');
    @endphp
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
    <h3 class='text-center h1'>{{ $userName }}さんの予約情報</h3>
    <div class='container text-right my-5'>
        <form method='post' action={{ route('search') }}>
            @csrf
            <select name='year'>
                @for ($i = 0; $i < 5; $i++)
                    @php
                        $year = date('Y', strtotime($now ."+${i} year"));
                    @endphp
                    <option value={{ $year }}>{{ $year }}</option>
                @endfor
            </select>
            <span>年</span>
            <select name='month'>
                @for ($i = 1; $i <= 12; $i++)
                    <option value={{ $i }}>{{ $i }}</option>
                @endfor
            </select>
            <span>月</span>
            <select name='day'>
                @for ($i = 1; $i <= 31; $i++)
                    <option value={{ $i }}>{{ $i }}</option>
                @endfor
            </select>
            <span>日</span>
            <input type='submit' value='検索'>
        </form>
    </div>
    <div class="container-fluid text-center my-5">
    <div class='row'>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                予約日
            </div>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                開始時間
            </div>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                終了時間
            </div>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                変更
            </div>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                削除
            </div>
        </div>
        @foreach ($userReserves as $userReserve)
        <div class='row'>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                {{ date('Y/m/d', strtotime($userReserve->start_time)) }}
            </div>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                {{ date('H:i', strtotime($userReserve->start_time)) }}
            </div>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                {{ date('H:i', strtotime($userReserve->end_time)) }}
            </div>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                <a href={{ route('edit', ['id' => $userReserve->id, 'start' => date('Y-m-d', strtotime($userReserve->start_time))]) }}>変更</a>
            </div>
            <div class='col d-flex align-items-center justify-content-center border h3 my-0'>
                <a href='#' class='delete' data-id={{ $userReserve->id }}>削除</a>
            </div>
        </div>
        @php
            $count++;
        @endphp
        @endforeach
    </div>
    <form method='post' action={{ route('delete') }} id='delete_form'>
        @csrf
        <input name='reserve_id' type='hidden' value="" id='reserve_id'>
    </form>
    <script type='module' src="{{ mix('js/user_page.js') }}"></script>
    </body>
</html>
