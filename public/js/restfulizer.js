// restfulizer.js
/**
* Author: Zizaco (http://forums.laravel.io/viewtopic.php?pid=32426)
* Tweaked by rydurham
* 
* Restfulize any hiperlink that contains a data-method attribute by
* creating a mini form with the specified method and adding a trigger
* within the link.
* Requires jQuery!
*
* Ex:
* <a href="post/1" data-method="delete">destroy</a>
* // Will trigger the route Route::delete('post/(:id)')
*
* 
* Update: 
*  - This method will now ignore elements that have a '.disabled' class 
*  - Adding the '.action_confirm' class will trigger an optional confirm dialog.
*  - Adding the Session::token to 'data-token' will add a hidden input for needed for CSRF. 
*
*/
$(function(){
    $('.formConfirm').not(".disabled").append(function(){
        var methodForm = "\n"
        methodForm += "<form action='"+$(this).attr('href')+"' method='POST' style='display:none' id='"+$(this).attr('data-id')+"'>\n"
        methodForm += " <input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"
       	if ($(this).attr('data-token')) 
       	{ 
       		methodForm +="<input type='hidden' name='_token' value='"+$(this).attr('data-token')+"'>\n"
       	}
        methodForm += "</form>\n"
        return methodForm
    })
    .removeAttr('href')
    .on('click', function(e) {
        e.preventDefault();
        var title = $(this).attr('data-title');
        var msg = $(this).attr('data-message');
        var dataForm = $(this).attr('data-form');
        $('#modal-confirm')
            .find('.modal-body').html(msg)
            .end()
            .find('.modal-title').html(title)
            .end()
            .find('#confirm').attr('data-form', dataForm);
    });

    $('#modal-confirm').on('click', '#confirm', function(e) {
        var id = $(this).attr('data-form');
        $(id).submit();
    });
});

