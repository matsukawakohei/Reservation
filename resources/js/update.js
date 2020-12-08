require('./bootstrap');

$(function () {
  $(document).on('click', '.reserve', function(e) {
    e.preventDefault();
    let old_date = $('#old_date').text();
    let old_start = $('#old_start').text();
    let old_end = $('#old_end').text();
    let start = $(this).attr('data-start');
    let end = $(this).attr('data-end');
    $('#start_time').val(start);
    $('#end_time').val(end);
    let message = `開始時間：${old_date} ${old_start}、終了時間：${old_date} ${old_end}を下記の日程に変更します。\nよろしいでしょうか？\n開始時間：${start}、終了時間：${end}`;
    let result = window.confirm(message);
    if (result) {
      $('#reserve_form').submit();
    } else {
      $('#start_time').val('');
    $('#end_time').val('');
    }
  });
});