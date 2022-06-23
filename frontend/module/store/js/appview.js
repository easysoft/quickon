$(function()
{
    $('.btn-install').on('click', function(event)
    {
        let confirmMessage = instanceNotices.confirmInstall.replace('%s', cloudApp.alias);
        bootbox.confirm(confirmMessage, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.submiting + '</div>',
            });

            let id  = $(event.target).attr('app-id');
            let url = createLink('instance', 'ajaxInstall', 'id=' + id);
            $.post(url).done(function(response)
            {
                loadingDialog.modal('hide');

                let res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.parent.$.apps.open(res.locate, 'space');
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
});
