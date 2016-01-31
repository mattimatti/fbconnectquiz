$('.selection').on("click", function (e) {
	var elm = $(e.currentTarget);
	var selection = $('#selection').val(elm.data('val'));
    $('#quizform').submit();
});