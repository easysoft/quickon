$(function()
{
    $('#submitBtn').on('click', function()
    {
        var loadingDialog = bootbox.dialog(
        {
            message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + notices.creatingSolution + '</div>',
        });

        $('#submitBtn').attr('disabled', true);
        $.post(createLink('solution', 'install', 'id=' + cloudSolutionID), $('#installForm').serializeArray()).done(function(response)
        {

            var res = JSON.parse(response);
            if(res.result == 'success')
            {
                $.get(createLink('solution', 'ajaxInstall', 'id=' + res.data.id)).done(function(response)
                {
                    $('#submitBtn').attr('disabled', false);
                    parent.window.location.href = res.locate;
                });
                setTimeout(function()
                {
                    loadingDialog.modal('hide');
                    $('#submitBtn').attr('disabled', false);
                    parent.window.location.href = res.locate;
                }, 3000);
            }
            else
            {
                loadingDialog.modal('hide');
                $('#submitBtn').attr('disabled', false);
                var errMessage = res.message;
                if(res.message instanceof Array) errMessage = res.message.join('<br/>');
                if(res.message instanceof Object) errMessage = Object.values(res.message).join('<br/>');

                bootbox.alert(
                {
                  title:   notices.fail,
                  message: errMessage,
                });
            }

        });
    });
});
