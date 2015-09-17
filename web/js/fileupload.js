$('document').ready(function(){
    var $spinner = $('.spinner').hide().removeClass('hide');
    var $file = $('#uploadform-file');

    $file
        .on("filebatchselected", function(event, files) {
            // trigger upload method immediately after files are selected
            $file.fileinput("upload");
        })
        .on("filebatchuploadsuccess", function(event, data, previewId, index) {
            var $spinner = $('.spinner').fadeIn();
            var url = decodeURI(settings.redirect_url)
            url = url.replace('{filename}', data.response.filename);
            url = encodeURI(url);

            window.location.href = url;
        })
        .on("filebatchuploaderror", function(event, data, previewId, index) {
            console.log('failed to upload file');
        });
}); 