$(document).ready(function () {
    $('.translations-fix-button').click(function () {
        let wrapper = $(this).parent();
        let isActive = $(this).hasClass('active-translation');

        $(wrapper).children('.translations-fix-button').each(function (item) {
            $(this).removeClass('active-translation');
            $(this).removeClass('disabled-translation');
            changeButtonValue(this, false);
            if (!isActive) {
                $(this).addClass('disabled-translation');
            }
        });

        if (!isActive) {
            $(this).removeClass('disabled-translation');
            $(this).addClass('active-translation');
        }
    });

    function changeButtonValue(button, value)
    {
        let id = $(button).attr('for');
        id = id.replace(/\./g,'\\.');
        let radioButton = $('#' + id);
        $(radioButton).attr('checked', value);
    }
})
