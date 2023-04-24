$(document).ready(function () {
    $('.resumable-video-uploader').each(function() {
        const $el = $(this);
        const $browser = $el.find('.resumable-browser').first();
        const $progress = $el.find('.progress').first();
        const $pb = $progress.find('.progress-bar').first();
        const $video_preview = $el.find('.video_preview').first();
        const $video_input = $el.find('.video_input').first();
        const $delete_video = $el.find('.delete_video').first();

        const resumable = new Resumable({
            target: $el.data('target'),
            query: {
                _token: $el.data('token'), // CSRF token
                type: $el.data('type'),
                module: $el.data('module'),
                ability: $el.data('ability'),
                locale: $el.data('locale') ?? null
            },
            fileType: ['mp4'],
            // default is 1*1024*1024 (1MB), this should be less than your maximum limit in php.ini
            // post_max_size = for chunk upload
            // upload_max_filesize = for finish uploading and store
            chunkSize: 2 * 1024 * 1024, // 2MB
            headers: {
                'Accept': 'application/json'
            },
            testChunks: false,
            throttleProgressCallbacks: 1,
        });
        resumable.assignBrowse($browser);

        resumable.on('fileAdded', function (file) { // trigger when file picked
            $pb.css('width', '0%');
            $pb.html('0%');
            $pb.removeClass('bg-success');
            $progress.show();
            resumable.upload() // to actually start uploading.
        });

        resumable.on('fileProgress', function (file) { // trigger when file progress update
            const value = Math.floor(file.progress() * 100);
            $pb.css('width', `${value}%`)
            $pb.html(`${value}%`)
        });

        resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
            response = JSON.parse(response)
            $video_preview.attr('src', response.url);
            $video_input.val(response.path);
            $progress.hide();
        });

        resumable.on('fileError', function (file, response) { // trigger when there is any error
            console.log(file, response)
            alert('file uploading error.')
        });

        $delete_video.click(() => {
            const delete_video_label = $(this).find('.delete_video');
            const field = delete_video_label.data('field');
            const id = delete_video_label.data('id');
            const _token = delete_video_label.data('token');
            const url = delete_video_label.data('url');
            const message = delete_video_label.data('message');

            console.log(field, id, _token, url)

            $.ajax({
                type: "POST",
                url: url,
                data: {_token: _token, id: id, field: field},
                success: function () {
                    toastr.success(message);
                },
                error: function () {
                    toastr.error("Error", "Error");
                },
            });
            $video_input.val('')
        })

    });
});
