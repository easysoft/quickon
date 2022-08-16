$(function()
{
    $('.upgrade').click(function()
    {
        $('#upgradeModal').modal('show');
    });

    $('#submitUpgrade').click(function()
    {
        window.parent.location.href= '/upgrading.html';
    });

    $('.backup').click(function()
    {
        $('#waitting').modal('show');
        setInterval(function()
        {
            $.get(createLink('backup', 'ajaxGetProgress'), function(data)
            {
                data = JSON.parse(data);
                $('.progressSQL').text(data.sql);
                $('.progressFile').text(data.file);
            });
        }, 1000);
    })

    $(".restoreButton").click(function()
    {
        let restoreUrl = $(this).data('url');
        $('#confirmRestore #submitRestore').data('url', restoreUrl);
        $('#confirmRestore').modal('show');
    });
    $('#submitRestore').click(function()
    {
        let restoreUrl = $('#confirmRestore #submitRestore').data('url');
        $('#hiddenwin').attr('src', restoreUrl);
        $('#confirmRestore').modal('hide');
        $('#restoring').modal('show');
        setInterval(function()
        {
            $.get(createLink('backup', 'ajaxGetRestoreProgress'), function(data)
            {
              console.log(data);
                data = JSON.parse(data);
                $('.restoreSQL').text(data.sql);
                $('.restoreFile').text(data.file);
            });
        }, 1000);
    });

    $(".deleteButton").click(function()
    {
        let delUrl = $(this).data('url');
        $('#confirmDelete #submitDelete').data('url', delUrl);
        $('#confirmDelete').modal('show');
    });
    $('#submitDelete').click(function()
    {
        $('#confirmDelete').modal('hide');
        let delUrl = $('#confirmDelete #submitDelete').data('url');

        $('#hiddenwin').attr('src', delUrl);
    });

    $('.rmPHPHeader').click(function()
    {
        $('#waitting .modal-body #backupType').html(rmPHPHeader);
        $('#waitting .modal-content #message').hide();
        $('#waitting').modal('show');
    })

})
