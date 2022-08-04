$('.sidenav').hide();

$('#app').on('click', '#config-button', function(e) {
    e.preventDefault();
    var app = $('#app');
    var active = app.hasClass('header');
    app.toggleClass('header');
    if(active)
    {
        $('.add-item').hide();
    }
    else
    {
        //$('#sortable').sortable('enable');
        setTimeout(function()
        {
            $('.add-item').fadeIn();
            $('.item-edit').fadeIn();
        }, 350);
    }
}).on('click', '#add-item', function(e)
{
    e.preventDefault();
    var app = $('#app');
    var active = app.hasClass('sidebar');
    $('.sidenav').toggle();
    app.toggleClass('sidebar');
}).on('click', '.close-sidenav', function(e)
{
    e.preventDefault();
    var app = $('#app');
    app.removeClass('sidebar');
    $('.sidenav').hide();
});

$('.instance-item').click(function()
{
    var active = $(this).hasClass('active');
    if(active)
    {
        $(this).removeClass('active');
    }
    else
    {
        $(this).addClass('active');
    }
    instanceID = $(this).attr('data-id');
    link = createLink('navigation', 'ajaxGetPinnedInstance', 'instanceID=' + instanceID);
    $.get(link, function(result)
    {
        $('#sortable').replaceWith(result);
    });
});

$('.searchbutton').click(function()
{
    var searchName = $('.homesearch').val().trim();
    link = createLink('navigation', 'ajaxSearchPinnedInstance', 'name=' + searchName);
    $.get(link, function(result)
    {
        $('#sortable').replaceWith(result);
    });

    if(searchName == '')
    {
        $('#config-button').fadeIn();
    }
    else
    {
        $('#config-button').hide();
    }
});
