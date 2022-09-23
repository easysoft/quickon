$(function()
{
    $('#installLDAP input[type=checkbox]').on('change', function(event)
    {
        if($(event.target).is(':checked'))
        {
            $('#installLDAP button[type=submit]').attr('disabled', false);
        }
        else
        {
            $('#installLDAP button[type=submit]').attr('disabled', true);
        }
    });

    $('#installLDAP input[type=checkbox]').change();
});
