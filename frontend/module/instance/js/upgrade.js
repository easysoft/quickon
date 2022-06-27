
$(function()
{
    $('#upgradeForm').on('submit', function(event)
    {
        event.preventDefault();

        var loadingDialog = bootbox.dialog(
        {
            message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.submiting + '</div>',
        });

        $.post($('#upgradeForm').attr('action'), $('#upgradeForm').serializeArray()).done(function(response)
        {
            loadingDialog.modal('hide');

            let res = JSON.parse(response);
            if(res.result == 'success')
            {
                config.onlybody = 'no';
                window.parent.$.apps.open(createLink('space', 'browse'), 'space');
            }
            else
            {
                bootbox.alert(
                {
                    title:   instanceNotices.fail,
                    message: res.message,
                });
            }
        });
    });
});
