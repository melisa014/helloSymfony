$(function(){
    saveMessages();
    loadMessages();
    chooseUser();
    clearUser();
});

function saveMessages()
{
    $('#chat_submit').on('click', function(){
        var message = $('#chat_content').val();
        var userId = $('div.chat-area').attr('data-user');
        
        console.log('saving... ' + message + ' в переписку с ' + userId);
        
        $.ajax({
            url: '/chat/saving',
            type: 'post',
            dataType: 'json',
            data: {
                message: message,
                toUser: userId
            }
        })
        .done(function(res) {
            console.log(res);
            
        })
        .fail(function(xhr, status, error){
            $('.holder-loader').removeClass('open');

             // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);

            // соберем самое интересное в переменную
            var errorInfo = 'Ошибка выполнения запроса: '
                    + '\n[' + xhr.status + ' ' + status   + ']'
                    +  ' ' + error + ' \n '
                    + xhr.responseText
                    + '<br>'
                    + xhr.responseJSON;

            console.log('ajaxError:', errorInfo); // в консоль
            alert(errorInfo); // если требуется и то на экран
        })
        return false;
    });
}

function loadMessages()
{
    $('').on('', function(){
        
        console.log('');
        
        $.ajax({
            url: '/chat/loading',
            type: 'post',
            dataType: 'json',
            data: {
                
            }
        })
        .done(function(res) {
            console.log(res);
            
        })
        .fail(function(xhr, status, error){
            $('.holder-loader').removeClass('open');

             // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);

            // соберем самое интересное в переменную
            var errorInfo = 'Ошибка выполнения запроса: '
                    + '\n[' + xhr.status + ' ' + status   + ']'
                    +  ' ' + error + ' \n '
                    + xhr.responseText
                    + '<br>'
                    + xhr.responseJSON;

            console.log('ajaxError:', errorInfo); // в консоль
            alert(errorInfo); // если требуется и то на экран
        })
        return false;
    });
}

function chooseUser()
{
    $('div.friends-list-conteiner tr').on('click', function(){
        var userId = $(this).attr('id');
        console.log('Id: ' + userId);
        
        $('div.chat-area').attr('data-user', userId);
        console.log('data-user:' + $('div.chat-area').attr('data-user'));
    });
}

function clearUser()
{
    $(document).on('keydown', function(event){
        if (event.keyCode === 27) {
            $('div.chat-area').attr('data-user', 'nobody');
            console.log('data-user после esc:' + $('div.chat-area').attr('data-user'));
        }
    });
}


