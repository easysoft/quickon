$(function()
{
    /* Set the heights of every block to keep them same height. */
    projectBoxHeight = $('#projectbox').height();
    productBoxHeight = $('#productbox').height();
    if(projectBoxHeight < 180) $('#projectbox').css('height', 180);
    if(productBoxHeight < 180) $('#productbox').css('height', 180);

    $('.panel-block').scroll(function()
    {
        var hasFixed  = $(this).find('.fixedHead').size() > 0;
        if(!hasFixed)
        {
            $(this).css('position', 'relative');
            var hasHeading = $(this).find('.panel-heading').size() > 0;
            var fixed = hasHeading ? $(this).find('.panel-heading').clone() : "<table class='fixedHead' style='position:absolute;top:0px;z-index:10'><thead>" + $(this).find('table thead').html() + '</thead></table>';
            $(this).prepend(fixed);
            if(hasHeading)
            {
                var firstHeading = $(this).find('.panel-heading:first');
                var lastHeading  = $(this).find('.panel-heading:last');
                firstHeading.addClass('fixedHead');
                firstHeading.css({'position':'absolute','top':'0px'});
                firstHeading.width(lastHeading.width());
                firstHeading.height(lastHeading.height());
            }
            else
            {
                var $fixTable = $(this).find('table.fixedHead');
                $fixTable.addClass($(this).find('table:last').attr('class'));
                var $dataTable = $(this).find('table:last thead th');
                $fixTable.find('thead th').each(function(i){$fixTable.find('thead th').eq(i).width($dataTable.eq(i).width());})
            }
        }
        $(this).find('.fixedHead').css('top',$(this).scrollTop());
    });

    $('[data-toggle="tooltip"]').tooltip();

    var enableTimer = true;
    window.parent.$(window.parent.document).on('showapp', function(event, app)
    {
        enableTimer = app.code == 'my';
    });

    setInterval(function()
    {
        if(!enableTimer) return;

        var statusURL = createLink('instance', 'ajaxStatus');
        $.post(statusURL, {idList: instanceIdList}).done(function(response)
        {
            let res = JSON.parse(response);
            if(res.result == 'success' && res.data instanceof Array)
            {
                res.data.forEach(function(instance)
                {
                    if($(".instance-status[instance-id=" + instance.id + "]").data('status') != instance.status) window.location.reload();
                });
            }
            if(res.locate) window.parent.location.href = res.locate;
        });
    }, 1000 * 5);

    /* Count down for demo instance. */
    setInterval(function()
    {
        let nowSeconds = Math.round((new Date).getTime() / 1000);
        $('.count-down').each(function(index, item)
        {
            let createdAt = $(item).data('created-at');
            let passSeconds = nowSeconds - createdAt;
            let leftSeconds = (demoAppLife ? demoAppLife : 30) * 60 - passSeconds;

            if(leftSeconds < 0)
            {
                window.parent.location.reload();
            }
            else
            {
                let minutes = Math.floor(leftSeconds / 60);
                let seconds = Math.round(leftSeconds % 60);
                $(item).find('.left-time').text(('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2));
            }

        })
    }, 1000);
});
