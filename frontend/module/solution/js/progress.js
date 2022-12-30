$(function()
{
    var timerID = setInterval(function()
    {
        $.get(createLink('solution', 'ajaxProgress', 'id='+ solutionID)).done(function(response)
        {
            var res = JSON.parse(response);
            if(res.result == 'success')
            {
                var finish = true;
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

                    if(cloudApp.status == 'installing')
                    {
                        $('.progress-message').text(notices.installingApp + cloudApp.alias);
                    }

                    if(cloudApp.status != 'installed')
                    {
                        finish = false;
                    }

                    if(cloudApp.status == 'error')
                    {
                        $('.error-message').text(res.message);
                    }
                }
                if(finish)
                {
                    $('.progress-message').text(notices.installationSuccess);
                    parent.window.location.href = createLink('solution', 'view', 'id=' + solutionID);
                }
            }
            else
            {
                var errMessage = res.message;
                if(res.message instanceof Array) errMessage = res.message.join('<br/>');
                if(res.message instanceof Object) errMessage = Object.values(res.message).join('<br/>');

                $('.step-message span').text(errMessage);
            }
        });
    }, 2000);

    $('#cancelInstallBtn').on('click', function()
    {
        bootbox.confirm(notices.cancelInstall, function(result)
        {
            if(!result) return;
            //showUninstallProgress();

            $.post(createLink('solution', 'uninstall', 'id=' + solutionID), function(response)
            {
                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    parent.window.location.href = createLink('solution', 'browse');
                }
            });
        });
    });

});
