$(function()
{
    var timerID = setInterval(function()
    {
        var mainMenu = parent.window.$.apps.getLastApp();
        if(mainMenu.code != 'solution') return;

        $.get(createLink('solution', 'ajaxProgress', 'id='+ solutionID)).done(function(response)
        {
            var res = JSON.parse(response);
            if(res.result == 'success')
            {
                var installed = true;
                for(var index in res.data)
                {
                    var cloudApp = res.data[index];
                    if(cloudApp.status == 'waiting')
                    {
                        $('.arrow.app-' + cloudApp.id).removeClass('active');
                        $('.step.app-' + cloudApp.id + ' .step-no').removeClass('active');
                    }
                    else
                    {
                        $('.arrow.app-' + cloudApp.id).addClass('active');
                        $('.step.app-' + cloudApp.id + ' .step-no').addClass('active');
                    }

                    if(cloudApp.status == 'installing' || cloudApp.status == 'installed')
                    {
                        $('#' + cloudApp.alias + '-status').text(installLabel);
                    }

                    if(cloudApp.status == 'configured')
                    {
                        $('#' + cloudApp.alias + '-status').text(configLabel);
                    }

                    if(cloudApp.status != 'installed' && cloudApp.status != 'configured')
                    {
                        installed = false;
                    }

                    if(cloudApp.status == 'error')
                    {
                        $('.error-message').text(res.message);
                        $('.progress.loading').hide();
                    }
                }
                if(installed)
                {
                    $('.progress-message').text(notices.installationSuccess);
                    parent.window.location.href = createLink('solution', 'view', 'id=' + solutionID);
                }
            }
            else
            {
                $('#retryInstallBtn').show();
                $('.progress.loading').hide();

                var errMessage = res.message;
                if(res.message instanceof Array) errMessage = res.message.join('<br/>');
                if(res.message instanceof Object) errMessage = Object.values(res.message).join('<br/>');

                $('.error-message').text(errMessage);
            }
        });
    }, 2000);

    $('#cancelInstallBtn').on('click', function()
    {
        $('#cancelInstallBtn').attr('disabled', true);

        bootbox.confirm(notices.cancelInstall, function(result)
        {
            $('#cancelInstallBtn').attr('disabled', false);
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

    $('#retryInstallBtn').on('click', function()
    {
        $('#retryInstallBtn').attr('disabled', true);

        bootbox.confirm(notices.confirmReinstall, function(result)
        {
            $('#retryInstallBtn').attr('disabled', false);

            if(!result) return;

            $('#retryInstallBtn').hide();
            $('.error-message').text('');
            $('.progress.loading').show();

            $.post(createLink('solution', 'ajaxInstall', 'id=' + solutionID), function(response)
            {
                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    parent.window.location.href = res.locate;
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   errors.error,
                        message: res.message
                    });
                }
            });
        });
    });

    if(hasError && !startInstall)
    {
        $('#retryInstallBtn').show();
    }
    else
    {
        $('#retryInstallBtn').hide();
    }

    if(startInstall)
    {
        $.get(createLink('solution', 'ajaxInstall', 'id=' + solutionID)).done(function(response){});
    }
});
