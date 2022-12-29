$(function()
{
    var timerID = setInterval(function()
    {
        $.get(createLink('solution', 'ajaxProgress', 'id='+ solutionID)).done(function(response)
        {
            var res = JSON.parse(response);
            if(res.result == 'success')
            {
                $('.step-message span').text(res.message);
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
});
