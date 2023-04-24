var slug = require('slug')
var $ic = 10;

$(document).ready(function () {
    window._token = $('meta[name="csrf-token"]').attr('content')

    $('.date').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'en'
    })

    $(".tab-content").on("click", ".slug-generate", function () {
        let text = $(".name_ua").val();
        if (!text) {
            text = $(".name_en").val();
        }

        return $("#slug").val(slug(text))
    })

    $(document).on('click', '.slug-module-generate', function () {
        let wrap = $(this).parents('.fields-wrap');

        let input = wrap.find('.to-slug')

        wrap.find('.slug-input').val(slug(input.val()))
    });

    $('.datetime').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        locale: 'en',
        sideBySide: true
    })

    $('.timepicker').datetimepicker({
        format: 'HH:mm:ss'
    })

    $('.select-all').click(function () {
        let $select2 = $(this).parent().siblings('.select2')
        $select2.find('option').prop('selected', 'selected')
        $select2.trigger('change')
    })
    $('.deselect-all').click(function () {
        let $select2 = $(this).parent().siblings('.select2')
        $select2.find('option').prop('selected', '')
        $select2.trigger('change')
    })

    $('.select2').select2()

    $('.treeview').each(function () {
        var shouldExpand = false
        $(this).find('li').each(function () {
            if ($(this).hasClass('active')) {
                shouldExpand = true
            }
        })
        if (shouldExpand) {
            $(this).addClass('active')
        }
    })


    $(document).on("click", ".browse", function () {
        var file = $(this).parents().find(".file");
        file.trigger("click");
    });

    $(document).on('click', '.btn btn-success', function () {
        $(".alert").delay(1).fadeOut("slow", function () {
            console.log(1);
            $(this).remove();
        })
    })

    $('.with-loading').on("click", function () {
        if (!$(this).find('loading').length) {
            new Loading($(this));
        }
        return setTimeout(() => {
            var $form;
            $form = $(this).closest('form');
            if ($form.length) {
                return $form.submit();
            }
        }, 200);
    });

    $(document).ready(function () {
        $('.toogle').bootstrapToggle();
    });

    $(document).on('click', '.removeImage', function (e) {
        const parentSection = $(this).parent().parent('div')
        const imageElement = parentSection.find('img')

        const fileElement = parentSection.find('.imageInput')
        const flagElement = parentSection.find('.isRemoveImage')

        $(flagElement).attr('value', '1');
        $(imageElement).attr('src', 'https://via.placeholder.com/150');
        $(fileElement).val('');

        $(this).attr('hidden', true);
    })

    $(document).on('change', '.imageInput', function () {
        const parentSection = $(this).parent('div')

        const flagElement = parentSection.find('.isRemoveImage')
        const btnElement = parentSection.find('.removeImage')

        $(flagElement).attr('value', '0');
        $(btnElement).attr('hidden', false);
    })

// ad images
    $(document).on('change', '.input-file', function (e) {
        var fileName = e.target.files[0].name
        $('#file').val(fileName)
        const inputElement = $(this)
        const parentSection = $(this).parent('div')
        const imageElement = parentSection.find('img')

        var reader = new FileReader()
        reader.onload = function (e) {
            imageElement.attr('src', e.target.result)
        }

        reader.readAsDataURL(this.files[0])
    })
})

Loading = class Loading {
    constructor(obj) {
        var position;
        this.obj = obj;

        $('<div id="loader" class="loading"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></div>').appendTo(this.obj);

        position = this.obj.css('position');

        if (position !== 'absolute' && position !== 'fixed' && position !== 'relative') {
            this.obj.css('position', 'relative');
        }
    }

    hide() {
        return this.obj.find('.loading').remove();
    }
};

function initTableNews() {
    if (document.getElementById('datatable1')) {
        $(document).ajaxComplete(function () {
            $('.ajax_ckeckbox').bootstrapToggle()
        });
        $(document).on('change', '.ajax_ckeckbox', function (event) {
            const field = $(this).data('field');
            const id = $(this).data('id');
            const _token = $(this).data('token');
            const value = ($(this).is(':checked')) ? 1 : 0;
            const url = $(this).data('url');
            $.ajax({
                type: "POST",
                url: url,
                data: {_token: _token, id: id, field: field, value: value},
                success: function () {
                    toastr.success("Status successfully updated", "Success");
                },
                error: function () {
                    toastr.error("Error", "Error");
                },
            });
        });

        $(document).on('click', '.btn-status', function (event) {
            let state = this
            const field = $(this).data('field');
            const id = $(this).data('id');
            const _token = $(this).data('token');
            const value = parseInt($(this).data('value'))
            const url = $(this).data('url');
            const change_to_class = $(this).data('change-to-class');
            const change_to_text = $(this).data('change-to-text');

            $.ajax({
                type: "POST",
                url: url,
                data: {_token: _token, id: id, field: field, value: value},
                success: function () {
                    $(state).data('change-to-class', $(state).attr("class"))
                    $(state).data('change-to-text', $(state).text())
                    $(state).data('value', value === 1 ? 0 : 1)

                    $(state).text(change_to_text)
                    $(state).removeClass($(state).attr("class")).addClass(change_to_class);

                    toastr.success("Status successfully updated", "Success");
                },
                error: function () {
                    toastr.error("Error", "Error");
                },
            });
        });
    }
}

initTableNews();


$(document).ready(function () {
    $ic = $('.duplication_row').length;
    $ic += $('.duplication-row').length;

    $(".duplicat").each(function () {
        return duplicate_row($(this))
    }),
        $("body").on("click", "table.duplication .destroy", function () {
        var t;
        return t = $(this), $(this).closest(".duplication_row").fadeOut(function () {
            return $("table.duplication .create").length < 3 && duplicate_row(t), $(this).remove()
        })
    }), $("body").on("click", "table.duplication .destroy.exists", function () {
        var t, e, a;
        return t = $(this), e = t.data("id"), e && (a = $(this).data("name"), $(this).closest("form").append('<input type="hidden" name="' + a + '" value="' + e + '" />')), $(this).closest(".duplication_row").fadeOut(function () {
            return $("table.duplication .create").length < 3 && duplicate_row(t), $(this).remove()
        })
    }), $(document).on("click", ".duplication .destroy", function () {
        var $th = $(this)
        var $parentRow = $th.parents('tr');
        var index = $parentRow.index();

        var $parentElem = $th.parents('.parent-elem');
        var $childrenTabs = $parentElem.find('.tab-pane')
        if ($childrenTabs.length === 0) {
            $childrenTabs = $parentElem.find('.no-children__tabs')
        }
        $childrenTabs.each((t, elem) => {
            var $el = $(elem);
            var $trs = $el.find('tr')
            $trs.each((i, e) => {
                if (i === index) {
                    $(e).remove()
                }
            })
        })
    });
});


$(document).ready(function () {
    window.ic = $('.duplication_row').length;
    window.ic += $('.duplication-row').length;

    $(".duplication.duplicate-on-start").each(function () {
        return duplicate_row($(this));
    });

    $(document).on("click", ".duplication .create", function () {
        return duplicate_row($(this));
    });

    $(document).on("click", ".duplication .destroy", function () {
        var $th = $(this)
        var $parentRow = $th.parents('tr');
        var index = $parentRow.index();

        var $parentElem = $th.parents('.parent-elem');
        var $childrenTabs = $parentElem.find('.tab-pane')
        if ($childrenTabs.length === 0) {
            $childrenTabs = $parentElem.find('.no-children__tabs')
        }
        $childrenTabs.each((t, elem) => {
            var $el = $(elem);
            var $trs = $el.find('tr')
            $trs.each((i, e) => {
                if (i === index) {
                    $(e).remove()
                }
            })
        })
    });
});

window.duplicate_row = function (t) {
    var e, a;
    a = t.closest(".duplication");

    if (a.find(".duplicat").length > 0) {
        e = duplicate_row_old(t);
    } else {
        e = duplicate_row_new(t);
    }

    // init ckeditor for textarea with class `with-editor`
    $(e).find('textarea.with-editor').each((i, el) => {
        CKEDITOR.replace($(el).attr('name'))
    })
}

window.duplicate_row_old = function (t) {
    var e, a;

    t.hasClass("table.duplication") || (a = t.closest(".duplication")),
        e = a.find(".duplicat").clone(!0),
        0 !== e.length && ($ic++, e[0].innerHTML = e[0].innerHTML.replace(/replaseme/g, $ic),
        e.find("div.select-2").remove(), e.removeClass("duplicat"), e.removeClass("hidden"),
        e.addClass("duplic").insertBefore(a.find(".duplication_row.duplicat")))

    return e
};

window.duplicate_row_new = function (t) {
    var e, a;
    return a = t.hasClass("duplication") ? t : t.closest(".duplication"),
        e = a.find(".duplicate").clone(!0),
        0 !== e.length ? (window.ic++, e[0].innerHTML = e[0].innerHTML.replace(/replaseme/g, window.ic),
            e.removeClass("duplicate").insertBefore(a.find(".duplication-button")),
            e.find(".form-control").each(function () {
                return $(this).attr("name", $(this).data("name")),
                    $(this).data("required") ? $(this).attr("required", $(this).data("required")) : void 0
            }), e[0]) : void 0
};


$(document).ready(function () {
    var $invalidValidationElems = $('.is-invalid');

    if ($invalidValidationElems.length) {
        $invalidValidationElems.each((index, elem) => {
            const $el = $(elem);
            var id = $el.parents('.parent-elem').attr('id')
            var $tabs = $('.card-body>.nav-tabs>.nav-item>a');
            $tabs.each((i, e) => {
                const $el = $(e);
                const href = $el.attr('href').replace('#', '')
                if (id === href) {
                    $el.addClass('error')
                }
            })
        })
    }
})

$(document).on('change', '.input-file', function (e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);
    let inputElement = $(this)
    let parentSection = $(this).parent('div')
    let imageElement = parentSection.find('img')

    var reader = new FileReader();
    reader.onload = function (e) {

        imageElement.attr("src", e.target.result);
    };

    reader.readAsDataURL(this.files[0]);
});

//CKEDITOR
$(document).ready(function () {
    CKEDITOR.config.removePlugins = 'elementspath';
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;
    CKEDITOR.config.filebrowserImageBrowseUrl = '/file-manager/ckeditor';
    CKEDITOR.config.allowedContent = true;

    CKEDITOR.config.toolbar = 'custom';
    CKEDITOR.config.toolbar_custom = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items:['Source'] },
        { name: 'image', groups: [ 'mode' ], items:['Image']},
        {
            name: 'clipboard',
            groups: [ 'clipboard', 'undo', 'basicstyles', 'cleanup'],
            items: [
                'Cut', 'Copy', 'Paste', 'PasteText',
                'Undo', 'Redo', 'SelectAll', 'BulletedList', 'NumberedList',
                'Bold', 'Italic', 'Underline', 'Strike', 'CopyFormatting',
                'RemoveFormat', 'SelectAll', 'JustifyLeft', 'JustifyCenter',
                'JustifyRight', 'JustifyBlock', 'Link', 'Unlink', 'CreateToken'
            ] },

        { name: 'styles', groups: [ 'styles' ], items:['Font', 'Styles', 'Format', 'FontSize', 'Maximize', 'ShowBlocks']},
    ];

    CKEDITOR.config.tabSpaces = 4;
});

// Custom ckeditor plugins

$(document).ready(function () {
    const json = $('#ck-tokens').data('value');
    if (json) {
        // Enable token plugin
        CKEDITOR.config.extraPlugins = 'token';

        // Configure available tokens
        CKEDITOR.config.availableTokens = json;

        // Configure token string
        CKEDITOR.config.tokenStart = '{{$';
        CKEDITOR.config.tokenEnd = '}}';
    }
});



$(document).ready(function () {
    $('body').find('#nav-tabs').find('.nav-link.major').on('click', function () {
        let url = "?id=" + $(this).attr('href').split('#')[1]
        let pathname = window.location.pathname;
        window.history.pushState(null, null, pathname + url);
    })
})

$(window).on("load", () => {
    let currentUrl = window.location.href.split('=')[1]

    if (currentUrl) {
        currentUrl = '#' + currentUrl
        if (currentUrl.indexOf('&') > -1) {
            currentUrl = currentUrl.split('&')[0]
        }
    } else {
        currentUrl = '#tab_ru'
    }

    let items = $('body').find('#nav-tabs').find('.nav-link.major')
    items.each(function (e) {
        if ($(this).hasClass('active')) {
            if ($(this).attr('href') !== currentUrl) {
                $(this).removeClass('active')
                $(this).attr('aria-selected', false)
                $('body').find('#tab-content').children().removeClass('show active')
            }
        }

        if ($(this).attr('href') === currentUrl) {
            $(this).addClass('active')
            $(this).attr('aria-selected', true)
            let id = e + 1
            $('body').find('#tab-content .tab-pane:nth-child(' + id + ")").addClass('show active')
        }
    })
})

$(document).on('click', ".upload-button-image", function (){

    let url = '/file-manager/fm-button?attr-id=' + $(this).attr('data-tr-id')
    let locale = $(this).attr('data-locale')

    if (locale) {
        url += '&locale=' + locale
    }

    window.open(url, 'fm', 'width=1000,height=600').focus();
})

function fmSetLink($url, dataId, dataLocale) {
    let imgClass = '.upload-preview-img'
    let btnClass = '.upload-label-img'

    if (dataId){
        imgClass += '-' + dataId
        btnClass += '-' + dataId
    }

    if (dataLocale) {
        imgClass += '-' + dataLocale
        btnClass += '-' + dataLocale
    }

    $(imgClass).parents('.image-wrap').find('.removeFile').attr('hidden', false)
    $(imgClass).parents('.image-wrap').find('.showFile').attr('hidden', false)
    $(imgClass).parents('.image-wrap').find('.showFile').attr('href', $url)

    $(imgClass).attr('hidden', false)
    $(imgClass).attr('src', $url)
    $(btnClass).val($url)
}

window.fmSetLink = fmSetLink;

$(document).on('input', '.preview-link', function () {
    $(this).parents('.image-wrap').find('.preview').attr('hidden', false)
    $(this).parents('.image-wrap').find('.preview').attr('src', $(this).val())
})

$(document).ready(function () {
    $('.theme').select2({
        minimumResultsForSearch: Infinity,
        templateResult: function (data, container) {
            if (data.element) {
                $(container).css({"background-color":data.id, "color":"transparent"});
            }

            return data.text;
        },
        templateSelection: function (data, container) {
            if (data.element) {
                $(container).css({"background-color":data.id, "color":"transparent", "height":"100%", "margin-top":"0"});
            }

            return data.text;
        },
    });
})

window.activate_loader = function activate_loader() {
    $('.__outer_loader').show();
}

window.disable_loader = function disable_loader() {
    $('.__outer_loader').hide();
}

$(document).on('toastr-notify', (e) => {
    const {type, title, message} = e.detail;
    toastr[type](title, message);
})
