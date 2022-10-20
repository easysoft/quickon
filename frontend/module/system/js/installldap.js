$(function()
{
    function freshSubmitBtn()
    {
        var enableLDAP    = $('#LDAPForm input[type=checkbox]:checked').length > 0;
        var ldapCheckPass =  true;
        if($('#LDAPForm select[name=source]').val() == 'extra')
        {
            ldapCheckPass = $('#testConnectBtn').attr('pass') == 'true';
        }

        if(enableLDAP && ldapCheckPass)
        {
          $('#LDAPForm button[type=submit]').attr('disabled', false);
        }
        else
        {
          $('#LDAPForm button[type=submit]').attr('disabled', true);
        }
    }

    $('#LDAPForm input[type=checkbox]').on('change', function(event)
    {
        freshSubmitBtn();
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

        freshSubmitBtn();
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
                $('#testConnectBtn').attr('pass', 'true');
                $('#connectResult').removeClass('text-red').addClass('text-success');
                freshSubmitBtn();
            }
            else
            {
                $('#testConnectBtn').attr('pass', 'false');
                $('#connectResult').removeClass('text-success').addClass('text-red');
                freshSubmitBtn();
            }
        });
    });

    freshSubmitBtn();
});
