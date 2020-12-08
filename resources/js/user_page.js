require('./bootstrap');

$(function () {
  $(document).on('click', '.delete', function(e) {
    e.preventDefault();
    let id = $(this).attr('data-id');
    $('#reserve_id').val(id);
    let message = `予約を削除します。\nよろしいでしょうか？`;
    let result = window.confirm(message);
    if (result) {
      $('#delete_form').submit();
    } else {
      $('#reserve_id').val('');
    }
  });
});