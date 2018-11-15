

function validateStepOne(account, summ, square) {
    if ( !account || account.length < 11 
            || !summ || summ < 0 
            || !square || square < 0 ) {
        $('.step-one-message').text('Вы используете некорректные данные');
        return false;
    }
    var account = account;
    $('.step-one-message').text('');
    $.ajax({
        url: 'validate-step-one',
        method: 'POST',
        data: {
            account: account,
            summ: summ,
            square: square,
        },
        success: function (response, textStatus, jqXHR) {
            if (response.success === false) {
                $('.step-one-message').text('Вы используете некорректные данные 1');
            } else if (response.success === true) {
                $('.step-one-message').text('Все ок');
            }
            console.log(response.qaz);
        },
    });
    return true;
}


$('#step0Next').on('click', function(e){
    e.preventDefault();
    var accountNumber = $('.account-number-input').val();
    var lastSum = $('.last-summ-input').val();
    var square = $('.square-input').val();
    alert (parseInt(accountNumber) + ' ' + parseFloat(lastSum) + ' ' + parseFloat(square))
    
    if (validateStepOne(parseInt(accountNumber), parseFloat(lastSum), parseFloat(square))) {
        $('#step0').remove();
        $('#step1').show()();
    }

});