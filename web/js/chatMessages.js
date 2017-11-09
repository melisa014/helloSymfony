$(function(){
    $("#chat_content").focus(); // по поле ввода сообщения ставим фокус
    setInterval("loadMessages();", 2000); // создаём таймер который будет вызывать загрузку сообщений каждые 2 секунды (2000 миллисекунд)
            
//    var lastMessageId = 0; // номер последнего сообщения, что получил пользователь
    var loadingStarted = false; // можем ли мы выполнять сейчас загрузку сообщений. Сначала стоит false, что значит - да, можем
    
    chooseUser();
    clearUser();
    
    saveMessages();
    
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
            if(!loadingStarted) {
                loadMessages();
            }
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
        });
        
        $("#chat_content").val(""); // очистим поле ввода сообщения
        $("#chat_content").focus(); // и поставим на него фокус
        
        return false;
    });
}

function loadMessages()
{
    loadingStarted = true;

    var activeUser = $('div.chat-area ').attr('data-admin');
    var friend = $('div.chat-area').attr('data-user');
    var lastMessageId = $('div.chat-area table').attr('data-last-load-message-id');
    
    console.log('Админ: ' + activeUser);
    console.log('Друг: ' + friend);
    console.log('Последнее сообщение: ' + lastMessageId);
        
    $.ajax({
        url: '/chat/loading',
        type: 'post',
        dataType: 'json',
        data: {
            lastMessageId: lastMessageId,
            activeUser: activeUser,
            friend: friend
        }
//            rand: (new Date()).getTime()
    })
    .done(function(res) {
        console.log(res);
        for (var message in res.loadingMessages) {
            console.log('От кого сообщение: ' + message.userFrom);
            if (message.userFrom === activeUser) {
                $("div.chat-area table tr.admin-message-").clone();
                var adminMessageArea = $("div.chat-area table tr.admin-message-").last();
                $(adminMessageArea).attr('class', 'admin-message-' + message.id);
                $(adminMessageArea + ':last-child').val(message.content);
            }
            // и тоже самое для друга
            if (message.userFrom === friend) {
                $("div.chat-area table tr.user-message-").clone();
                var userMessageArea = $("div.chat-area table tr.user-message-").last();
                $(userMessageArea).attr('class', 'user-message-' + message.id);
                $(userMessageArea + ':first-child').val(message.content);
            }
            
//            chat.append('<span>' + message.userFrom + '<br>' + message.content + '</span>');
        }
         $("div.chat-area table").attr('data-last-load-message-id', res.lastLoadMessageId);

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
    });

    loadingStarted = false;
}

function chooseUser()
{
    $('div.friends-list-conteiner tr').on('click', function(){
        var userId = $(this).attr('id');
        console.log('Id: ' + userId);
        
        $('div.chat-area').attr('data-user', userId);
        console.log('data-user:' + $('div.chat-area').attr('data-user'));

        $('#chat-area-conteiner').css('display','block');
    });
}

function clearUser()
{
    $(document).on('keydown', function(event){
        if (event.keyCode === 27) {
            $('div.chat-area').attr('data-user', 'nobody');
            console.log('data-user после esc:' + $('div.chat-area').attr('data-user'));
            $('#chat-area-conteiner').css('display','none');
        }
    });
}


