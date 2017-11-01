alert('first)');

$(function(){
    
    alert('secound)');
    
    saveMessage();
    chooseUser();
});

function saveMessages()
{
    $('.chat-submit').on('click', function(){
        var message = $('.chat-content').val();
        var user = 
        $.ajax({
            type: 'post',
            data: {
                message: message,
                toUser: user,
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
    $('div.friends-list-conteiner div table tbody tr td').on('click', function(){
        
        alert('third)');
        
        var userId = $(this).attr('id');
        $('.chat-area').attr('data-user', userId);
        $('.chat-area').append('Здесь будет переписка с пользователем. Его id = ' + userId);
    });
}



