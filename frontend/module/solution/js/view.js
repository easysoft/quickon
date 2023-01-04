$(function()
{
    $('[data-toggle="tooltip"]').tooltip();

    $('#uninstallBtn').on('click', function()
    {
        bootbox.confirm(notices.confirmToUninstall, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + notices.uninstallingSolution + '</div>',
            });

            $.post(createLink('solution', 'ajaxUninstall', 'id=' + solutionID), function(response)
            {
                loadingDialog.modal('hide');

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    parent.window.location.href = res.locate;
                }
            });
        });
    });
});
