/* 
 * For Clients of Modules
 */

$(document).ready(function() {
    
    // ******************************************************** //
    // ************    Start Block of Profile    ************** //
    // ******************************************************** //

    /* Обработка события при клике на checkBox 'Арендатор'
     * Если за лицевым счетом закреплен арендатор, то 
     * выводим модальное окно для управления учетной записью арендатора
     */
    
    $('#is_rent').on('change', function() {
        
        var currentAccount = $('#_list-account').val();
        accountNumber = parseInt(currentAccount, 10);
        
        console.log(accountNumber);
        
        $.post('check-is-rent?account=' + accountNumber, function(response) {
            if (response.new_rent == true) {
                $('#add-rent-modal').modal('show');
            } else if (response.new_rent == false) {
                $('#changes_rent').modal('show');
            }
        }); 
    });    
    
    
    $('input[name*=account-number]').val($('.current__account_list :selected').text());
    
    /* Обработка события при клике на dropDownList "Список лицевых счетов собственника"
     * Профиль, блок "Контактные данные арендатора"
     */
    $('#_list-account').on('change', function() {
        var client = $(this).data('client');
        var account = $(this).val();
        
        $.ajax({
            url: 'check-account',
            data: {
                dataClient: client,
                dataAccount: account,
            },
            error: function() {
                console.log('Error #1000-11');
            },
            dataType: 'json',
            type: 'POST',
            success: function(response) {
                if (response.is_rent) {
                    $('#is_rent').prop('checked', true);
                } else {
                    $('#is_rent').prop('checked', false);
                }                
               $("#content-replace").html(response.data);
            }
        });

    });
    
    /*
     * Спять чекбокс Арендатор, если пользователь закрыл модальное окно "Новый арендатор"
     */
    $('.add-rent-modal__close').on('click', function() {
        $('#is_rent').prop('checked', false);
    });    

    /*
     * Обработка событий в модальном окне 'Дальнейшие действия с учетной записью арендатора'
     * Закрыть модальное окно
     */
    $('.changes_rent__close').on('click', function() {
        $('#is_rent').prop('checked', true);
    });
    
    // Удалить данные арендатора из системы
    $('.changes_rent__del').on('click', function() {
        var rentsId = $('input[id=_rents]').val();
        $.ajax({
            url: 'delete-rent-profile',
            method: 'POST',
            data: {
                rentsId: rentsId,
            },
            success: function (response, textStatus, jqXHR) {
//                console.log(textStatus);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error #1000-03');                
            },
        });
    });
    
    /*
     * Форма 'Добавить нового Арендатора'
     */
    /*
     * При загрузке модального окна, получаем
     * ID выбранного лицевого счета
     */
    $('#add-rent-modal').on('show.bs.modal', function(e) {
        var accountId = $('#_list-account :selected').text();
        $('#_personal-account').val(accountId);
        $('.btn__add_rent', this).data('accountId', accountId);
    });
    
    // Очистить поля ввода, клик по кнопкам 'Отмена', 'x'
    $('#add-rent-modal .add-rent-modal__close').on('click', function() {
        $('#add-rent input').val('');
        $('#add-rent-modal .modal-body').removeClass('has-error');
        $('#add-rent-modal .modal-body').removeClass('has-success');
        $('#add-rent-modal .form-group').removeClass('has-success'); 
        $('#add-rent-modal .form-group').removeClass('has-error');
        $('#add-rent-modal').find('.help-block').text('');
     });
    
    $('.rent-info__btn_close').on('click', function () {
        $('#is_rent').prop('checked', false);
        $('#create-rent-form')[0].reset();
    });    
    
    /* End Block of Profile */


    // *************************************************************** //
    // ************   Start Block of Personal Account   ************** //
    // *************************************************************** //

    /*
     * Раздел "Лицевой счет"
     * Форма - Добавить лицевой счет
     */
    // Блокируем кнопку добавить арендатора
    $('.btn__add-rent').prop('disabled', true);
    
    // В зависимости от checkBox 'Арендатор' скрываем/показываем элементы
    $('#isRent').change(function() {
        if (this.checked) {
            $('.btn__add-rent').prop('disabled', false);
        } else {
            $('.btn__add-rent').prop('disabled', true);            
        }
    });
    
    /*
     * Если в модальном окне "Новый арендатор" нажата нопка Отмена/х
     * Сбрасываем заполненный поля
     * Снимаем чекбокс Арендатор
     * Блокирем кнопку "Добавить арендатора"
     * 
     */
    $("#add-rent-modal .btn__modal_rent_close, .btn__modal_close").on("click", function() {
        $("#add-rent-modal input").val("");
        $("#add-rent-modal .modal-body").removeClass("has-error");
        $("#add-rent-modal .modal-body").removeClass("has-success");
        $("#add-rent-modal .form-group").removeClass("has-success"); 
        $("#add-rent-modal .form-group").removeClass("has-error");
        $("#add-rent-modal").find(".help-block").text("");
        $("#isRent").prop("checked", false);
        $(".btn__add-rent").prop("disabled", true);
    });
    
    /*
     * Метод контрольной проверки заполнения формы "Новый лицевой счет"
     * до того, как была нажала кнопка "Добавить лицевой счет"
     */
    $("#add-account").on("beforeSubmit.yii", function (e) {

        // Форма "Новый арендатор" по умолчанию считается не заполненной
        var isCheck = false;
        // Поиск в модальном окне создания арендатора поля для заполнения
        var form = $("#add-rent-modal").find("input[id*=clientsrentform]");
        // Количество полей на форме "Новый арендатор"
        var field = [];

        // Проверяем каждое поле на форме "Новый арендатор" на заполнение
        form.each(function() {
            field.push("input[id*=clientsrentform]");
            var value = $(this).val();
            // console.log(value);
            for (var i = 1; i < field.length; i++) {
                // Если втречается заполненное поле, то статус заполнения формы меням на положительный
                if (value) {
                    isCheck = true;
                }
            }
            // console.log(field.length);
        });
    
        /*
        *   Перед отправкой формы, проверяем чекбокс "Арендатор"
        *   Если переключатель установлен, то проверяем наличие выбранного арендатора из списка
        *   или наличее добавленного арендатора
        */
        if (!$("#isRent").is(":checked")) {
            $("#add-rent-modal input").val("");
            $("#add-rent-modal .modal-body").removeClass("has-error");
            $("#add-rent-modal .modal-body").removeClass("has-success");
            $("#add-rent-modal .form-group").removeClass("has-success"); 
            $("#add-rent-modal .form-group").removeClass("has-error");
            $("#add-rent-modal").find(".help-block").text("");
            $("#isRent").prop("checked", false);
            $(".btn__add-rent").prop("disabled", true);
            e.preventDefault();    
        }
    });
    
    /*
     * Сброс формы в модальном окне на добавление нового личевого счета при закрытии формы "Отмена"/х
     */
    $('.account-create__btn_close').on('click', function () {
        $('#create-account-modal-form')[0].reset();
    }); 
    
    /*
     * Раздел 'Приборы учета'
     * 
     * 
     * Поля ввода для показания приборов учета блокируем
     */ 
    var ind = $('.indication_val');
    ind.prop('disabled', true);

    /*
     * Если нажата кнопка 'Ввести показания'
     * текстовые поля для ввода показаний делаем доступными
     */
    $('.btn__add_indication').on('click', function() {
        ind.prop('disabled', false);
    });
    
    /* End Block of Personal Account */


    // ******************************************************* //
    // ************   Start Block of Requests   ************** //
    // ******************************************************* //
    /*
     * Очистить форму заполнения заявки, если пользователь нажал в модальном окне 'Отмена'
     */ 
    $('.request__btn_close').on('click', function () {
        $('#add-request-modal-form')[0].reset();
    });
    
    /*
     * Сортировка заявок по
     * ID лицевого счета и статусу заявки
     */
    $('.status_request-switch').on('click', function(e) {
        e.preventDefault();
        
        var status = $(this).data('status');
        
        $('.status_request-switch').each(function() {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
        
        $.ajax({
            url: 'filter-by-type-request?status=' + status,
            method: 'POST',
            data: {
                status: status,
            },
            success: function(data) {
                if (data.status === false) {
                    console.log('Error #1000-08');
                } else {
                    $('.grid-view').html(data);
                }
            },
        });        
        
    });
    
    /*
     * Добавление оценки для закрытой заявки
     */
    // Получаем ID заявки через data
    var request_id = $('div#star').data('request');
    // Получаем оценку текущей заявки
    var scoreRequest = $('div#star').data('scoreReguest');
    // Вызываем функцию raty из библиотеки плагина по голосованию
    $('div#star').raty({
        score: scoreRequest, // Оценка
        readOnly: scoreRequest === 0 ? false : true, // Если оценка высталена, то возможность голосования закрываем
        click: function(score) {
            $.ajax({
                url: 'add-score-request',
                method: 'POST',
                data: {
                    score: score,
                    request_id: request_id,
                },
                success: function(response) {
                    if (response.status === true) {
                        $('#score-modal-message').modal('show');
                        $('div#star').raty({
                            score: score,
                            readOnly: true,
                        });
                    }
                },
                error: function() {
                    console.log('Error #1000-09');
                }
            });
        }
    });    
    
    /*
     * Всплывающая подсказка для кнопки "Вам доступна система оценки"
     */
//    $('[data-toggle="popover"]').popover();
    
    
    /* End Block of Requests */

    // ************************************************************ //
    // ************   Start Block of Paid Services   ************** //
    // *********************************************************** //
    
    /*
     * Загрузка модального окна "Добавить платную услугу"
     * В dropDownList, hiddenInput загружаем ID выбранной услуги
     */
    $(".new-rec").on("click", function(){
        var idService = $(this).data("record");
        $("#add-record-modal").modal("show");
        $("#add-record-modal").find("#name_services").val(idService);
        $("#secret").val(idService);
    });    


    /*
     * Сбросить заполненные поля формы в случае, 
     * если модальное окно "Добавить платную услугу" закрыто через кнопку "Отмена" / x
     */
    $('.btn__paid_service_close').on('click', function() {
        $('#add-paid-service')[0].reset();
    });

    /*
     * Поиск по исполнителю
     */
    $('#_search-input').on('input', function() {
    
        var searchValue = $(this).val();
//        var accountId = $('.current__account_list').val();
        
        $.ajax({
            url: 'search-by-specialist',
            method: 'POST',
            data: {
                searchValue: searchValue,
//                accountId: accountId,
            },
            success: function(response) {
                $('.grid-view').html(response.data);
            },
            error: function() {
                console.log('Error #1000-10');
            }
        });
    });

    /* End Block of Paid Services */


    // ************************************************************ //
    // ***************   Start Block of Voting    **************** //
    // *********************************************************** //

    /*
     * Отмена участия в голосовании
     */
    $('#renouncement_participate, #renouncement_participate_close').on('click', function(){
        var votingId = $(this).data('voting');
        $.ajax({
            type: 'POST',
            url: 'renouncement-to-participate',
            data: { votingId: votingId, }
        }).done(function(response) {
//            console.log('voting');
        });
    });
    
    /*
     * Повторная отправка СМС кода
     */
    $('#repeat_sms_code').on('click', function(){
        var votingId = $(this).data('voting');
        $.ajax({
            type: 'POST',
            url: 'repeat-sms-code',
            data: { votingId: votingId, }
        }).done(function(response) {
            if (response.success === true) {
                $('.repeat_sms_code-message').text('Новый код был отправлен');
            } else if (condition) {
                $('.repeat_sms_code-message').text('Ошибка отправки СМС сообщения');                
            }
        });
        return false;
    });
    
    
    /* End Block of Voting */
    

    // ************************************************************ //
    // ***************     Work with send SMS     **************** //
    // *********************************************************** //

    // Количество секунд до следующей отправки
    var timeMinute = 60*2;
    
    // Таймер
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        var startTimer = setInterval(function () {
            minutes = parseInt(timer / 60, 10)
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            display.text('Отправить код повторно можно будет через ' + minutes + ':' + seconds);

            if (--timer < 0) {
                timer = duration;
                clearInterval(startTimer);
                $('#time-to-send').html('<button type="button" id="repeat-sms_code">Отправить код повторно</button>');
            }

        }, 1000);
    }
    
    // После ввода 5тизначного смс кода формируем кнопку на повторную отправку смс
    $('.input-sms_code').on('input', function(){
        var value = $(this).val();
        if (value.length == 5) {
            display = $('#time-to-send');
            startTimer(timeMinute, display);
        }        
    });

    /*
     * Генерация нового СМС кода
     */
    $(document).on('click', '#repeat-sms_code', function (){
        display = $('#time-to-send');
        startTimer(timeMinute, display);
        $.ajax({
            type: 'POST',
            url: 'generate-new-sms-code',
        }).done(function(response) {
//            console.log(response.status);
        });
    });
    
});
