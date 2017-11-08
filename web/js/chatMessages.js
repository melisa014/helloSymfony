$(function(){
    $("#chat_content").focus(); // по поле ввода сообщения ставим фокус
    setInterval("loadMessages();", 2000); // создаём таймер который будет вызывать загрузку сообщений каждые 2 секунды (2000 миллисекунд)
            
    var lastMessageId = 0; // номер последнего сообщения, что получил пользователь
    var loadingStarted = false; // можем ли мы выполнять сейчас загрузку сообщений. Сначала стоит false, что значит - да, можем

    saveMessages();
    loadMessages(loadingStarted, lastMessageId);
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
            loadMessages();
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
    
    // Проверяем можем ли мы загружать сообщения. Это сделано для того, чтобы мы не начали загрузку заново, если старая загрузка ещё не закончилась.
    if(!loadingStarted) {
        loadingStarted = true; // загрузка началась
        // отсылаем запрос серверу, который вернёт нам javascript
        $.ajax({
            act: "load", // указываем на то что это загрузка сообщений
            last: last_message_id, // передаём номер последнего сообщения который получил пользователь в прошлую загрузку
            rand: (new Date()).getTime()
        },
        function (result) { // в эту функцию в качестве параметра передаётся javascript код, который мы должны выполнить
            $(".chat").scrollTop($(".chat").get(0).scrollHeight); // прокручиваем сообщения вниз
            loadingStarted = false; // говорим что загрузка закончилась, можем теперь начать новую загрузку
        });
                
    }
          
    if(!loadingStarted) {
        loadingStarted = true;
        
        $.ajax({
            url: '/chat/loading',
            type: 'post',
            dataType: 'json',
            data: {
                lastMessageId: lastMessageId
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
        });

        loadingStarted = false;
    }    
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


