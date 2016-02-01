$(document).on('click', '.btn-edit-cart button', function (evt) {
    var $btn = $(evt.currentTarget);
    var $input = $btn.parent().prev();
    var $btns = $btn.parent().find('.btn');

    var url    = $input.attr('data-url');
    var mealId = $input.attr('data-meal');

    if ($btn.hasClass('btn-success')) {
        var val    = $input.val();
    } else {
        var val = 0;
    }

    evt.preventDefault();
    evt.stopPropagation();

    $btns.attr('disabled', true);

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            meal: mealId,
            mode: 'set',
            quantity: val
        },

        error: function () {
            $btns.attr('disabled', false);
            window.location.reload();
        },

        success: function () {
            $btns.attr('disabled', false);
            window.location.reload();
        }
    });
});

$(document).on('click', '.btn-add-to-cart button', function (evt) {
    var $btn = $(evt.currentTarget);
    var $input = $btn.parent().prev();
    var $alert = $btn.parents('.add-to-cart').find('.alert');

    evt.preventDefault();
    evt.stopPropagation();

    $btn.attr('disabled', true);

    var val    = $input.val();
    var url    = $input.attr('data-url');
    var mealId = $input.attr('data-meal');

    $btn.find('.add').fadeOut(function () {
        $btn.find('.wait').fadeIn(function () {
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    meal: mealId,
                    mode: 'add',
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
                    $cartPanel = $("#cart-panel");
                    $cartPanel.fadeOut(function () {
                        $cartPanel.html(resp).fadeIn();
                    });
                    $btn.find('.wait').fadeOut(function () {
                        $btn.find('.ok').fadeIn();
                        $alert.slideDown();
                        setTimeout(function () {
                            $btn.find('.ok').fadeOut(function () {
                                $btn.find('.add').fadeIn();
                                $btn.attr('disabled', false);
                            });
                        }, 1000);
                        setTimeout(function () {
                            $alert.slideUp();
                        }, 6000);
                    });
                }
            })
        });
    });
});
