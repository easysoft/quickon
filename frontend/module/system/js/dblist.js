$(function()
{
    $('button.db-login').on('click', function(event)
    {
        console.log(event);
        let dbName    = $(event.target).data('db-name');
        let namespace = $(event.target).data('namespace');

        $.post(createLink('system', 'logindb'), {dbName, namespace}).done(function(res)
        {
            console.log(res);
            let response = JSON.parse(res);
            if(response.result == 'success')
            {
                window.parent.open(response.data.url, 'Adminer');
            }
            else
            {
                bootbox.alert(response.message);
            }
        });
    });
});
