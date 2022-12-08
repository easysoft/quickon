$(function()
{
    var timerID = 0;

    /**
     * Show progress modal and fresh progress of updating domain by ajax.
     *
     * @access public
     * @return void
     */
    function showProgressModal()
    {
        $('#waiting').modal('show');
        timerID = setInterval(function()
        {
            $.get(createLink('system', 'ajaxUpdatingDomainProgress'), function(data)
            {
                $('#waiting #message').html(data);
            });
        }, 1000);
    };

    $('#submitBtn').on('click', function()
    {
        bootbox.confirm(notices.confirmUpdateDomain, function(result)
        {
            if(!result) return;

            showProgressModal();
            $('#submitBtn').attr('disabled', true);

            var domainData = $('#domainForm').serializeArray();
            $.post(createLink('system', 'editDomain'), domainData).done(function(response)
            {
                $('#submitBtn').attr('disabled', false);

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    parent.window.location.href = res.locate;
                }
                else
                {
                    $('#waiting').modal('hide');
                    clearInterval(timerID);

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
});
