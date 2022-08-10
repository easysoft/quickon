$(function()
{
    $('button.db-login').on('click', function(event)
    {
        let dbName    = $(event.target).data('db-name');
        let namespace = $(event.target).data('namespace');

        $.post(createLink('system', 'ajaxDBAuthUrl'), {dbName, namespace}).done(function(res)
        {
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
