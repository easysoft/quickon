$(function()
{
    $('#LDAPForm input[type=checkbox]').on('change', function(event)
    {
        if($(event.target).is(':checked'))
        {
            $('#LDAPForm button[type=submit]').attr('disabled', false);
        }
        else
        {
            $('#LDAPForm button[type=submit]').attr('disabled', true);
        }
    });

    $('#LDAPForm input[type=checkbox]').change();

    $('select[name=source]').on('change', function(event)
    {
        if($(event.target).val() == 'qucheng')
        {
            $('#quchengLDAP').show();
            $('#extraLDAP').hide();
        }
        else
        {
            $('#quchengLDAP').hide();
            $('#extraLDAP').show();
        }

    });
    $('select[name=source]').change();

    $('#testConnectBtn').on('click', function(event)
    {
        var settings = {};
        settings.host     = $('input[name="extra[host]"]').val();
        settings.port     = $('input[name="extra[port]"]').val();
        settings.bindDN   = $('input[name="extra[bindDN]"]').val();
        settings.bindPass = $('input[name="extra[bindPass]"]').val();
        settings.baseDN   = $('input[name="extra[baseDN]"]').val();

        $.post(createLink('system', 'testLDAPConnection'), settings).done(function(response)
        {
            console.log(response);
            try
            {
              var res = JSON.parse(response);
            }
            catch(error)
            {
              var res = {result: 'fail', message: errors.verifyLDAPFailed,};
            }
            $('#connectResult').html(res.message);
            if(res.result == 'success')
            {
              $('#connectResult').removeClass('text-red').addClass('text-success');
            }
            else
            {
              $('#connectResult').removeClass('text-success').addClass('text-red');
            }
        });
    });

  if(disableEdit)
  {
      $('#LDAPForm input').attr('disabled', true);
      $('#LDAPForm select').attr('disabled', true);
      $('#LDAPForm button').attr('disabled', true);
  }
});
