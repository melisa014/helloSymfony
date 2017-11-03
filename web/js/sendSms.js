$(function(){
    ajaxCreateSmsCode();
});

function ajaxCreateSmsCode()
{
    $('div.text-link').on('click', function(){
        var mobileN = $('#app_user_registration_mobileNumber').val(); // считываем значение, если это форма регистрации
        if (!mobileN) {
            var mobileN = $('#form_mobileNumber').val(); // считываем значение, если это форма входа
        }
        console.log(mobileN);
        $.ajax({
            url: '/register/generateSmsCode', 
            type: 'POST',
            data: { mobileNumber : mobileN },
            dataType: 'json',
        })
        .done (function(res){
            console.log(res);
        })
        .fail(function(){
            console.log('Ошибка соединения с сервером');
        });
    });
}