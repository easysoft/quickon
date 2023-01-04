$(function()
{
    $('#submitBtn').on('click', function()
    {
        $('#submitBtn').attr('disabled', true);
        $.post(createLink('solution', 'install', 'id=' + cloudSolutionID), $('#installForm').serializeArray()).done(function(response)
        {
            $('#submitBtn').attr('disabled', false);
            var res = JSON.parse(response);
            if(res.result == 'success')
            {
                $.get(createLink('solution', 'ajaxInstall', 'id=' + res.data.id)).done(function(response)
                {
                    parent.window.location.href = res.locate;
                });
                setTimeout(function()
                {
                    parent.window.location.href = res.locate;
                }, 3000);
            }
            else
            {
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
