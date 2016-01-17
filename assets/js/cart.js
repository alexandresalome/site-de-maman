$(document).on('click', '.btn-add-to-cart button', function (evt) {
    var $btn = $(evt.currentTarget);
    var $input = $btn.parent().prev();

    evt.preventDefault();
    evt.stopPropagation();

    $btn.attr('disabled', true);

    var val    = $input.val();
    var url    = $input.attr('data-url');
    var mode    = $input.attr('data-mode');
    var mealId = $input.attr('data-meal');

    $btn.find('.add').fadeOut(function () {
        $btn.find('.wait').fadeIn(function () {
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    meal: mealId,
                    mode: mode,
                    quantity: val
                },

                error: function () {
                    $btn.find('.wait').fadeOut(function () {
                        $btn.find('.ko').fadeIn();
                        setTimeout(function () {
                            $btn.find('.ko').fadeOut(function () {
                                $btn.find('.add').fadeIn();
                                $btn.attr('disabled', false);
                            });
                        }, 1000);
                    });
                },

                success: function (resp) {
                    if ($input.attr('data-refresh') == 'refresh') {
                        $btn.attr('disabled', false);
                        window.location.reload();
                    }
                    $cartPanel = $("#cart-panel");
                    $cartPanel.fadeOut(function () {
                        $cartPanel.html(resp).fadeIn();
                    });
                    $btn.find('.wait').fadeOut(function () {
                        $btn.find('.ok').fadeIn();
                        setTimeout(function () {
                            $btn.find('.ok').fadeOut(function () {
                                $btn.find('.add').fadeIn();
                                $btn.attr('disabled', false);
                            });
                        }, 1000);
                    });
                }
            })
        });
    });
});
