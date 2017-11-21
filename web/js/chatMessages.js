$(function(){
    
    var loadingStarted = false; // можем ли мы выполнять сейчас загрузку сообщений. Сначала стоит false, что значит - да, можем
    $("#chat_content").focus(); // по поле ввода сообщения ставим фокус
    
    chooseUser();
    clearUser();
//    var loadingInterval = setInterval(loadMessages(loadingStarted), 5000);
         
    saveMessages(loadingStarted);
});

function saveMessages(loadingStarted)
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
                loadMessages(loadingStarted);
            }
        })
        .fail(function(xhr, status, error){
            $('.holder-loader').removeClass('open');

             // выводим значения переменных
            console.log('SAVING ajaxError status:', status);
            console.log('SAVING ajaxError error:', error);

            // соберем самое интересное в переменную
            var errorInfo = 'SAVING Ошибка выполнения запроса: '
                    + '\n[' + xhr.status + ' ' + status   + ']'
                    +  ' ' + error + ' \n '
                    + xhr.responseText
                    + '\n'
                    + xhr.responseJSON;

            console.log('SAVING ajaxError:', errorInfo); // в консоль
            alert(errorInfo); // если требуется и то на экран
        });
        
        $("#chat_content").val(""); // очистим поле ввода сообщения
        $("#chat_content").focus(); // и поставим на него фокус
        
        return false;
    });
}

function loadMessages(loadingStarted)
{
    loadingStarted = true;

    console.log('LOADING ...');
    var activeUser = $('div.chat-area ').attr('data-admin');
    var friend = $('div.chat-area').attr('data-user');
    var lastMessageId = $('div.chat-area table').attr('data-last-load-message-id');
    
    $.ajax({
        url: '/chat/loading',
        type: 'post',
        dataType: 'json',
        data: {
            lastMessageId: lastMessageId,
            activeUser: activeUser,
            friend: friend
        }
    })
    .done(function(res) {
        console.log(res);
        let loadingMessages = res.loadingMessages;
        console.log(res.loadingMessages);
            for (let key in loadingMessages){
                let message = loadingMessages[key];
                
                // Вариант натянутый на вёрстку, пока работает неправильно!
                if (message.userFrom.id == friend) {
                    // клонируем шаблон
                    $("#user-message-0").clone().appendTo("div.chat-area div");
                    // даём уникальный id клону
                    $('div.chat-area div li').last().attr('id', 'user-message-' + message.id);
                    // добавляем сообщениев ленту
//                    $('li#user-message-' + message.id + ' p').html(message.content).css('color', 'green');
                    $('li#user-message-' + message.id + '.other div.msg').append("<p>" +message.content+ "</p>").css('color', 'green');
                    // показываем новый блок
                    $('#user-message-' + message.id).css('display','block');
                }
                if (message.userFrom.id == activeUser) {
                    // клонируем шаблон
                    $("#admin-message-0").clone().appendTo("div.chat-area div");
                    // даём уникальный id клону
                    $('div.chat-area div li').last().attr('id', 'admin-message-' + message.id);
                    // добавляем сообщениев ленту
//                    $('li#admin-message-' + message.id + ' p').html(message.content).css('color', 'blue');
                    $('li#admin-message-' + message.id+ '.self div.msg').append("<p>" +message.content+ "</p>").css('color', 'blue');
                    // показываем новый блок
                    $('#admin-message-' + message.id).css('display','block');
                }
                
            }
        $("div.chat-area div").attr('data-last-load-message-id', res.lastLoadMessageId);
                
                //Old версия. Изменены селекторы в соответствии с новым шаблоном, index_outer.html.twig
//                if (message.userFrom.id == friend) {
//                    // клонируем шаблон
//                    $("#user-message-").clone().appendTo("div.chat-area table tbody");
//                    // даём уникальный id клону
//                    $('div.chat-area table tbody tr').last().attr('id', 'user-message-' + message.id);
//                    // добавляем сообщениев ленту
//                    $('tr#user-message-' + message.id + ' td').first().html(message.content).css('color', 'green');
//                    // показываем новый блок
//                    $('#user-message-' + message.id).css('display','block');
//                }
//                if (message.userFrom.id == activeUser) {
//                    // клонируем шаблон
//                    $("#admin-message-").clone().appendTo("div.chat-area table tbody");
//                    // даём уникальный id клону
//                    $('div.chat-area table tbody tr').last().attr('id', 'admin-message-' + message.id);
//                    // добавляем сообщениев ленту
//                    $('tr#admin-message-' + message.id + ' td').last().html(message.content).css('color', 'blue');
//                    // показываем новый блок
//                    $('#admin-message-' + message.id).css('display','block');
//                }
//                
//            }
//        $("div.chat-area table").attr('data-last-load-message-id', res.lastLoadMessageId);
    })
    .fail(function(xhr, status, error){
        $('.holder-loader').removeClass('open');

         // выводим значения переменных
        console.log('LOADING ajaxError status:', status);
        console.log('LOADING ajaxError error:', error);

        // соберем самое интересное в переменную
        var errorInfo = 'LOADING Ошибка выполнения запроса: '
                + '\n[' + xhr.status + ' ' + status   + ']'
                +  ' ' + error + ' \n '
                + xhr.responseText
                + '<br>'
                + xhr.responseJSON;

        console.log('LOADING ajaxError:', errorInfo); // в консоль
        alert(errorInfo); // если требуется и то на экран
    });

    loadingStarted = false;
    
    return loadingStarted;
}

function chooseUser()
{
    $('div.friends-list-conteiner tr').on('click', function(){
        var userId = $(this).attr('id');
        var username = $(this).attr('data-username');
        var last = $(this).attr('data-last');
        console.log('Id: ' + userId);
        
        $('div.chat-area').attr('data-user', userId);
        console.log('data-user:' + $('div.chat-area').attr('data-user'));
        
        $('div.menu div.name').html(username);
        $('div.menu div.last').html(last);
        $('div.menu div.last').css('display','block');
//        $('div.menu img').attr('src', imgSrc);

        $('#chat-area-conteiner').css('display','block');
    });
}

function clearUser()
{
    $(document).on('keydown', function(event){
        if (event.keyCode === 27) {
            $('div.chat-area').attr('data-user', 'nobody');
            console.log('data-user после esc:' + $('div.chat-area').attr('data-user'));
            
            $('div.menu div.name').html("Выберите собеседника...");
            $('div.menu div.last').css('display','none');
//            $('div.menu img').css('display','none');
            
            $('#chat-area-conteiner').css('display','none');
        }
    });
}