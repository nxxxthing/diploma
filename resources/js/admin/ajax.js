function errorHandling(errors, form)
{
    errorsHtml = '<div class="validation-error"><ul>';

    $.each(errors, function (k, v) {
        $.each(v, function (key, value) {
            errorsHtml += '<li>' + value + '</li>';
        });

        errorsHtml += '</ul></div>';

        error_class = k + '-error';
        error_class = error_class.replace(/\./g, '-')

        $('.form-for-' + form).find(`.` + `${error_class}`).html(errorsHtml)
        errorsHtml = '<div class="validation-error"><ul>';
    });
}
$(function () {
    if (typeof CKEDITOR != 'undefined') {
        $('.reset-me').on('change', function (e) {
            if ($(CKEDITOR.instances).length) {
                for (var key in CKEDITOR.instances) {
                    var instance = CKEDITOR.instances[key];
                    if ($(instance.element.$).closest('.reset-me').attr('name') == $(e.target).attr('name')) {
                        instance.setData(instance.element.$.defaultValue);
                    }
                }
            }
        });
    }
});

function updateAllMessageForms()
{
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
}

function check_functions() {
    if (typeof window.disable_loader == 'undefined') {
        window.disable_loader = function disable_loader() {
            console.log('loader disabled (no loader)');
        }
    }
    if (typeof window.activate_loader == 'undefined') {
        window.activate_loader = function activate_loader() {
            console.log('loader active (no loader)');
        }
    }
    if (typeof window.update_card_headers == 'undefined') {
        window.update_card_headers = function update_card_headers() {
            console.log('no card headers function');
        }
    }
}


$(document).ready(function () {
    check_functions()

    $('div.ajax-no-data-message').each(function(k, item) {
        update_count(item, 0);
    });

    $('input').each(
        function(){
            $(this).attr('data-original-value',$(this).val());
        });
    $('select').each(
        function(){
            $(this).attr('data-original-value',$(this).val());
        });
    $('textarea').each(
        function(){
            $(this).attr('data-original-value',$(this).val());
        });
    $(document).on("click", ".ajax-create", function () {
        updateAllMessageForms()
        let sendFormData = new FormData()
        let successMessage = $(this).data('success-message')
        let success = $(this).data('success')
        let className = '.' + $(this).data('input-class')
        let relation = $(this).data('relation')
        let parentSection = $('.image-new-'+ relation).children().find('#image-wrap');
        let no_data_div = $('div.ajax-no-data-message[data-relation="' + relation + '"]');

        $("div.validation-error").remove();

        $(className).each(function (k, item) {
            key = $(item).attr('name');
            value = $(item).val();
            key = key.substring(key.indexOf("_") + 1);

            // If you have image fields, you need to add files to your payload
            if ($(item).hasClass('input-file')) {
                value = $(item).prop('files')[0];
            }

            sendFormData.append(key, value)
        });
        $.ajax({
            url: $(this).data('action'),
            type: 'POST',
            data: sendFormData,
            headers: {'Accept': 'application/json', 'locale': $(this).data('locale')},
            success: function (data) {
                disable_loader();
                toastr.success(successMessage, success);
                clearImage(parentSection);

                $('.reset-me').each(function (k, item) {
                    $(item).val($(item).data('original-value')).change();
                });
                $("div.validation-error").remove();

                let html_form = data.data.html_form
                $('.insert-form-'+relation).append(html_form)
                $(html_form).find('textarea.ckeditor').each((i, el) => {
                    CKEDITOR.replace($(el).attr('name'))
                })

                update_count(no_data_div,1);
            },
            beforeSend: function() {
                activate_loader();
            },
            error: function (data) {
                disable_loader();
                let errors = data.responseJSON;
                errorHandling(errors.errors, 'create')
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $(document).on("click", ".ajax-update", function () {
        updateAllMessageForms()
        let successMessage = $(this).data('success-message')
        let success = $(this).data('success')
        let sendFormData = new FormData()
        let id = $(this).data('id')
        let className = '.' + $(this).data('input-class')
        $(className).each(function (k, item) {
            key = $(item).attr('name');
            value = $(item).val();
            key = key.substring(key.indexOf("_") + 1);
            if ($(item).hasClass('input-file') && value) {
                value = $(item).prop('files')[0];
            }
            console.log(key + ": " + value);
            sendFormData.append(key, value)
        });

        $("div.validation-error").remove();
        $.ajax({
            url: $(this).data('action'),
            type: 'POST',
            data: sendFormData,
            headers: {'Accept': 'application/json', 'locale': $(this).data('locale')},
            success: function (data) {
                disable_loader();
                update_card(data)
                toastr.success(successMessage, success);
            },
            beforeSend: function() {
                activate_loader();
            },
            error: function (data) {
                disable_loader();
                let errors = data.responseJSON;
                errorHandling(errors.errors, id)
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $(document).on("click", ".ajax-delete", function () {
        let state = this
        let successMessage = $(this).data('success-message')
        let success = $(this).data('success')
        let adminHash =  $(this).data('admin-hash')
        let relation = $(this).data('relation')
        let no_data_div = $('div.ajax-no-data-message[data-relation="' + relation + '"]');

        let formData = new FormData()

        formData.append('adminHash', adminHash)

        $.ajax({
            url: $(this).data('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                disable_loader();
                toastr.success(successMessage, success);
                $(state).parents().parent('.to-hide').remove()
                update_count(no_data_div,-1);
            },
            beforeSend: function() {
                activate_loader();
            },
            error: function (data) {
                disable_loader();
                let errors = data.responseJSON;
                errorHandling(errors.errors, form)
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});
function update_count(div, adding) {
    let total = $(div).data('count') + adding;
    $(div).data('count', total)

    if (total > 0) {
        $(div).hide();
    } else {
        $(div).show();
    }
}

function clearImage(parentSection) {
    let imageElement = parentSection.find('img')

    let fileElement = parentSection.find('.create-input')
    let flagElement = parentSection.find('.isRemoveImage')
    $(parentSection).find('.file-preview').hide()
    $(flagElement).attr('value', '1');
    $(imageElement).attr('src', 'https://via.placeholder.com/150');
    $(fileElement).val('');
    $(parentSection).children().find('#removeImage').attr('hidden', true)

}

function update_card(data) {
    try {
        let id = data.data.card_title.id;
        let title = data.data.card_title.title;
        let image = data.data.card_title.image;
        let card = $('[data-faq-id="' + id + '"]');
        card.children('div.card-header').children('.card-title').html(image + ' ' + title);
    } catch (err) {
        console.log(err);
    }
}
