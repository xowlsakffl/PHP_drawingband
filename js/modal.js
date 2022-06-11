$(function(){
    $('.imglist').click(function(event) {
        $.ajax({
        url: $(this).attr('href'),
        success: function(newHTML, textStatus, jqXHR) {
            $(newHTML).appendTo('#modal_content').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('ajax error');
        }
        // More AJAX customization goes here.
        });
    
    return false;
    });
})
