$(document).on('click', 'a[data-zoom]', function (evt) {
    evt.stopPropagation();
    evt.preventDefault();

    var image = $(evt.currentTarget).attr('data-zoom');

    $("#modal-content").html('<a href="#" class="thumbnail"><img src="' + image + '" /></a>');
    $("#modal-content a.thumbnail").click(function () {
        $("#modal").modal('hide');
    });
    $("#modal").modal();

});
