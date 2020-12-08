require('./bootstrap');

$(function () {
  $(document).on('click', '.reserve', function(e) {
    e.preventDefault();
    let start = $(this).attr('data-start');
    let end = $(this).attr('data-end');
    $('#start_time').val(start);
    $('#end_time').val(end);
    let message = `下記の日程で予約します。\nよろしいでしょうか？\n開始時間：${start}、終了時間：${end}`;
    let result = window.confirm(message);
    if (result) {
      $('#reserve_form').submit();
    } else {
      $('#start_time').val('');
    $('#end_time').val('');
    }
  });
});