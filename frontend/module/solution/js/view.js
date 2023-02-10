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

    setInterval(function()
    {
        var mainMenu = parent.window.$.apps.getLastApp();
        if(mainMenu.code != 'solution') return;

        var statusURL = createLink('instance', 'ajaxStatus');
        $.post(statusURL, {idList: instanceIdList}).done(function(response)
        {
            let res = JSON.parse(response);
            if(res.result == 'success' && res.data instanceof Array)
            {
                res.data.forEach(function(instance)
                {
                    if($(".instance-status[instance-id=" + instance.id + "]").data('status') != instance.status) window.location.reload();
                });
            }
        });
    }, 1000 * 5);
});
