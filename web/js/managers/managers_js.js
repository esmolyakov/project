/* 
 * For Managers of Modules
 */

$(document).ready(function() {
    
    // ******************************************************** //
    // ************    Start Block of Profile    ************** //
    // ******************************************************** //
    /*
     * Формирование зависимых списков выбора Подразделения и Должности Администратора
     */
    $('.department_list').on('change', function(e) {
        $.post('/web/managers/app-managers/show-post?departmentId=' + $(this).val(),
        function(data) {
            $('.posts_list').html(data);
        });
    });
    
    /*
     * Блокировать/Разблокировать Учетную запись Собственника на портале
     */
    $('body').on('click', '.block_user', function(e) {
        e.preventDefault();
        var userId = $(this).data('user');
        var statusUser = $(this).data('status');        
        $.ajax({
            url: '/web/managers/app-managers/block-user-in-view',
            method: 'POST',
            data: {
                userId: userId,
                statusUser: statusUser,
            },
            success: function(response) {
                if (response.status == 2) {
                    $('.block_user').text('Разблокировать');
                    $('.block_user').removeClass('btn-danger');
                    $('.block_user').addClass('btn-success');
                    $('.block_user').data('status', 1);
                } else {
                    if (response.status == 1) {
                        $('.block_user').text('Заблокировать');
                        $('.block_user').addClass('btn-danger');
                        $('.block_user').removeClass('btn-success');
                        $('.block_user').data('status', 2);
                    }
                }
            },
            error: function() {
                console.log('Error #2000-01');
            },
        });
        return false;
    });
    
    /* Обработка события при клике на checkBox 'Арендатор'
     * Если за лицевым счетом закреплен арендатор, то 
     * выводим модальное окно для управления учетной записью арендатора
     */
    $('#is_rent').on('change', function(e) {
        //var rentsId = $('input[id=_rents]').val();
        var accountNumber = $('#_list-account :selected').text();
        var checkShow = $(this).val();
        accountNumber = parseInt(accountNumber, 10);
        // Если на форме есть скрытое поле, содеражащее ID арендатора
        if ($('input').is('#_rents')) {
            // Выводим модальное окно на удаление учетной записи Арендатора
            $('#delete_rent_manager').modal('show');
        } else {
            // Показать форму Добавление нового арендатора
            if ($('#is_rent').is(':checked')) {
//                $.ajax({
//                    url: 'show-form',
//                    method: 'POST',
//                    async: false,
//                    data: {
//                        accountNumber: accountNumber,
//                        _show: checkShow,
//                    },
//                    success: function(response) {
//                        if (response.status && response.show) {
//                            $('.form-add-rent').html(response.data);
//                        } else {
//                            $('.form-add-rent').html(response.message);
//                        }
//                    }
//                });
            } else {
                $('.form-add-rent').html('Арендатор отсутствует');
            }
        }
    });    

    /*
     * Обработка событий в модальном окне 'Дальнейшие действия с учетной записью арендатора'
     * Закрыть модальное окно
     */
    $('.delete_rent__close').on('click', function() {
        $('#is_rent').prop('checked', true);
    });
    
    /*
     * Загрузка данных Арендатора в модальное окно 'Дальнейшие действия с учетной записью арендатора'
     */
    $('#delete_rent_manager').on('show.bs.modal', function(){
        $(this).find('#rent-surname').text($('#rents_surname').data('surname'));
        $(this).find('#rent-name').text($('#rents_name').data('name'));
        $(this).find('#rent-second-name').text($('#rents_second_name').data('second-name'));
    });
    
    // Удалить данные арендатора из системы
    $('.delete_rent__ok').on('click', function() {
        var rentsId = $('input[id=_rents]').val();
        var accountId = $('#_list-account :selected').text();
        $.post({
            url: 'delete-rent-profile?rent=' + rentsId + '&account=' + accountId,
            method: 'POST',
            error: function() {
                console.log('Error #2000-03');
            }
        });
    });
    
    
    /* Обработка события при клике на dropDownList "Список лицевых счетов собственника"
     * Профиль Собственника, блок "Контактные данные арендатора"
     */
    $('#_list-account').on('change', function() {
        var accountNumber = $('input[name*=account-number]');
        var client = $(this).data('client');
        var account = $(this).val();
        
        accountNumber.val($('#_list-account :selected').text());
        $.ajax({
            url: 'check-account',
            data: {
                dataClient: client,
                dataAccount: account,
            },
            error: function() {
                console.log('Error #2000-11');
            },
            dataType: 'json',
            type: 'POST',
            success: function(response) {
                console.log(response.account);
                console.log(response.client);
                if (response.is_rent) {
                    $('#is_rent').prop('checked', true);
                } else {
                    $('#is_rent').prop('checked', false);
                }                
               $("#content-replace").html(response.data);
            }
        });

    });
    
    
    // ******************************************************** //
    // ************    Start Block of Employers    ************** //
    // ******************************************************** //
    
    /*
     * Поиск по Диспетчерам
     */
    $('#_search-dispatcher').on('input', function() {
    
        var searchValue = $(this).val();
        
        $.ajax({
            url: 'search-dispatcher',
            method: 'POST',
            data: {
                searchValue: searchValue,
            },
            success: function(response) {
                $('.grid-view').html(response.data);
            },
            error: function() {
                console.log('Error #2000-10');
            }
        });
    });

    /*
     * Загрузка данных о Сотруднике модальное окно "Удалить сотрудника"
     */
    $('.delete_empl').on('show.bs.modal', function(e) {
        // Обращаемся к кнопке, которая открыла модальное окно
        var button = $(e.relatedTarget);
        // Получаем ее дата атрибут
        var dataDis = button.data('employer');
        var dataFullName = button.data('fullName');
        $('.delete_empl').find('#disp-fullname').text(dataFullName);
        $(this).find('#confirm_delete-empl').data('employer', dataDis);
    });    

    /*
     * Запрос на удаление профиля сотрудника (Диспетчер)
     */
    $('.delete_disp__del').on('click', function(){
        var employerId = $(this).data('employer');
        $.ajax({
            url: 'query-delete-dispatcher',
            method: 'POST',
            dataType: 'json',
            data: {
                employerId: employerId,
            },
            success: function(response) {
                if (response.isClose === true) {                
                    $('#delete_disp_manager_message').modal('show');
                } else if (response.isClose === false) {
                    console.log('все заявки закрыты');
                }
            },
            error: function(){
                console.log('Error #2000');
            },
        });
    });

    /*
     * Запрос на удаление профиля сотрудника (Диспетчер)
     */
    $('.delete_spec__del').on('click', function(){
        var employerId = $(this).data('employer');
        $.ajax({
            url: 'query-delete-specialist',
            method: 'POST',
            dataType: 'json',
            data: {
                employerId: employerId,
            },
            success: function(response) {
                if (response.isClose === true) {                
                    $('#delete_spec_manager_message').modal('show');
                } else if (response.isClose === false) {
                    console.log('все заявки закрыты');
                }
            },
            error: function(){
                console.log('Error #2000');
            },
        });
    });
    
    /*
     * Поиск по Специалистам
     */
    /*
     * Поиск по Диспетчерам
     */
    $('#_search-specialist').on('input', function() {
    
        var searchValue = $(this).val();
        
        $.ajax({
            url: 'search-specialist',
            method: 'POST',
            data: {
                searchValue: searchValue,
            },
            success: function(response) {
                $('.grid-view').html(response.data);
            },
            error: function() {
                console.log('Error #2000-10');
            }
        });
    });
    
    // ******************************************************** //
    // ************    Start Block of Service    ************** //
    // ******************************************************** //
    /*
     * Перед выводом модального окна на подтверждение удаления услуги 
     * Формируем характеристики удаляемой услуги
     */
    $('#delete_service').on('show.bs.modal', function(e){
        // Обращаемся к кнопке, которая открыла модальное окно
        var button = $(e.relatedTarget);
        // Получаем ее дата атрибут
        var dataSrv = button.data('service');
        $(this).find('#srv_name').text(button.data('serviceName'));
        $('#delete_service').find('.delete_srv__del').data('service', dataSrv);
    });
    
    /*
     * Запрос на удаление услуги
     */
    $('.delete_srv__del').on('click', function() {
        var serviceId = $(this).data('service');
        $.ajax({
            url: 'confirm-delete-service',
            method: 'POST',
            data: {
                serviceId: serviceId,
            },
            success: function(response) {
                // console.log(response.here);
            },
            error: function() {
                console.log('error');
            }
        });
    });
    
    /*
     * Сквозной поиск по таблице услуги
     */
    $('#_search-service').on('input', function() {
        var searchValue = $(this).val();
        $.ajax({
            url: 'search-service',
            method: 'POST',
            data: {
                searchValue: searchValue,
            },
            success: function(response) {
                $('.grid-view').html(response.data);
            },
            error: function() {
                console.log('Error #2000-10');
            }
        });
    });


    // ******************************************************** //
    // ************    Start Block of Requests    ************** //
    // ******************************************************** //
    /*
     * Формирование зависимых списков выбора имени услуги от ее категории
     */
    $('#category_service').on('change', function(e) {

        $.post('/web/managers/app-managers/show-name-service?categoryId=' + $(this).val(),
        function(data) {
            $('#service_name').html(data);
        });
    });

    /*
     * Поиск собственника по введенному номеру телефона
     * Поиск срабатывает когда поле ввода теряем фокус
     */
    $('body').on('blur', '.mobile_phone', function(e) {
        // Получаем текущее значение
        var strValue = $(this).val();
        // В полученном значении удаляем все символы кроме цифр, знака -, (, )
        strValue = strValue.replace(/[^-0-9,(,)]/gim, '');
        $.post('/web/managers/app-managers/show-houses?phone=' + strValue,
        function(data) {
            $('.house').html(data);
        });
    });
    
    /*
     * Переключение статуса заявки
     */
    $('.switch-request').on('click', function(e){
        e.preventDefault();
        var linkValue = $(this).text();
        var statusId = $(this).data('status');
        var requestId = $(this).data('request');
        var liChoosing = $('li#status' + statusId);
        $.ajax({
            url: 'switch-status-request',
            method: 'POST',
            data: {
                statusId: statusId,
                requestId: requestId,
            },
            success: function(response) {
                if (response.status) {
                    console.log(response.status);
                    $('.dropdown-menu').find('.disabled').removeClass('disabled');
                    $('#value-btn').text(linkValue);
                    liChoosing.addClass('disabled');
                    if (statusId === 4) {
                        $('.btn:not(.dropdown-toggle)').attr('disabled', true);
                    } else {
                        $('.btn:not(.dropdown-toggle)').attr('disabled', false);
                    }
                }
            },
            error: function() {
                console.log('error');
            },
        });
    });

    /*
     * Переключение статуса заявки на платную услугу
     */
    $('.switch-paid-request').on('click', function(e){
        e.preventDefault();
        var linkValue = $(this).text();
        var statusId = $(this).data('status');
        var requestPaidId = $(this).data('request');
        var liChoosing = $('li#status' + statusId);
        $.ajax({
            url: 'switch-status-paid-request',
            method: 'POST',
            data: {
                statusId: statusId,
                requestPaidId: requestPaidId,
            },
            success: function(response) {
                if (response.status) {
                    $('.dropdown-menu').find('.disabled').removeClass('disabled');
                    $('#value-btn').text(linkValue);
                    liChoosing.addClass('disabled');
                    if (statusId === 4) {
                        $('.btn:not(.dropdown-toggle)').attr('disabled', true);
                        $('textarea[id="comments-text"]').attr('disabled', true);
                    } else {
                        $('.btn:not(.dropdown-toggle)').attr('disabled', false);
                        $('textarea[id="comments-text"]').attr('disabled', false);                        
                    }
                }
            },
            error: function() {
                console.log('error');
            },
        });
    });    
    
    /*
     * Вызов модального окна "Назначение диспетчера"
     * Вызов модального окна "Назначение специалиста"
     */
    $('#add-dispatcher-modal, #add-specialist-modal').on('show.bs.modal', function(e) {
        var requestId = $('.switch-request-status').data('request');
        var employeeId = $(e.relatedTarget).data('employee');
        var typeRequest = $(e.relatedTarget).data('typeRequest');
        $('.error-message').text('');
        $('.add_dispatcher__btn').data('request', requestId);
        $('.add_dispatcher__btn').data('typeRequest', typeRequest);
        $('.add_specialist__btn').data('request', requestId);
        $('.add_specialist__btn').data('typeRequest', typeRequest);
        // Если сотрудник уже назначен, то обозначаем его активным в списке выбора сотрудников
        $('a[data-employee=' + employeeId + ']').addClass('active');
    });
    
    $('#add-dispatcher-modal, #add-specialist-modal').on('hide.bs.modal', function() {
        $('#dispatcherList, #specialistList').find('.active').removeClass('active');
    });

    /*
     * В списке сотрудников, выбранных диспетчеров/специалистов обозначаем активными
     */
    $('#dispatcherList a, #specialistList a').on('click', function() {
        var employeeId = $(this).data('employee');
        $('#dispatcherList, #specialistList').find('.active').removeClass('active');
        $(this).toggleClass('active');
        $('.add_dispatcher__btn').data('dispatcher', employeeId);
        $('.add_specialist__btn').data('specialist', employeeId);
    });

    /*
     * Отправляем запрос на добавления диспетчера к выбранной заявке
     */
    $('.add_dispatcher__btn').on('click', function(e) {
        e.preventDefault();
        // Получаем ФИО выбранного сотрудника
        var employeeName = $('#dispatcherList').find('.active').text();
        var dispatcherId = $(this).data('dispatcher');
        var requestId = $(this).data('request');
        var typeRequest = $(this).data('typeRequest');

        // Проверяем налицие дата параметров
        if (dispatcherId === undefined || requestId === undefined) {
            $('.error-message').text('Прежде чем назначить диспетчера, выберите его из списка');
            return false;
        } else {
            $.ajax({
                url: 'choose-dispatcher',
                method: 'POST',
                data: {
                    dispatcherId: dispatcherId,
                    requestId: requestId,
                    typeRequest: typeRequest,
                },
                success: function(response) {
                    console.log(response.type_request);
                    if (response.success === false) {
                        $('.error-message').text('Ошибка');
                        return false;
                    }
                    
                    $('.btn-dispatcher').data('employee', dispatcherId);
                    $('#dispatcher-name').text('');
                    $('#dispatcher-name').html(
                            '<a href="/web/managers/employers/edit-dispatcher?dispatcher_id=' + dispatcherId + '">' + 
                            employeeName + '</a>');
                },
                error: function() {
                    $('.error-message').text('Ошибка');
                },
            });
        }
    });

    
    /*
     * Отправляем запрос на добавления диспетчера к выбранной заявке
     */
    $('.add_specialist__btn').on('click', function(e) {
        e.preventDefault();
        // Получаем ФИО выбранного сотрудника
        var employeeName = $('#specialistList').find('.active').text();
        var specialistId = $(this).data('specialist');
        var requestId = $(this).data('request');
        var typeRequest = $(this).data('typeRequest');

        // Проверяем налицие дата параметров
        if (specialistId === undefined || requestId === undefined) {
            $('.error-message').text('Прежде чем назначить специалиста, выберите его из списка');
            return false;
        } else {
            $.ajax({
                url: 'choose-specialist',
                method: 'POST',
                data: {
                    specialistId: specialistId,
                    requestId: requestId,
                    typeRequest: typeRequest,
                },
                success: function(response) {
                    if (response.success === false) {
                        $('.error-message').text('Ошибка');
                        return false;
                    }
                    $('.btn-specialist').data('employee', specialistId);
                    $('#specialist-name').text('');
                    $('#specialist-name').html(
                            '<a href="/web/managers/employers/edit-specialist?specialist_id=' + specialistId + '">' + 
                            employeeName + '</a>');
                },
                error: function() {
                    $('.error-message').text('Ошибка');
                },
            });
        }
    });

    /*
     * Сброс форм 
     *      Новая завяка
     *      Новая заявка на платную услугу
     */
    $('.create-request, .create-paid-request').on('click', function(){
        $('#create-new-request, #create-new-paid-request')[0].reset();
    });
    
    
    // ******************************************************** //
    // ************     Start Block of News      ************** //
    // ******************************************************** //
    /*
     * Переключатель, статус публикации новости
     *      Для всех
     *      Для жилого комплекса
     *      Для отдельного дома
     */
    $('#for_whom_news').on('change', function(e) {
        var forWhom = $("#news-form input[type='radio']:checked").val();
        
        if (forWhom === '0') {
            $('#adress_list').prop('disabled', true);
        } else {
            $('#adress_list').prop('disabled', false);
        }
        
        $.post('for-whom-news?status=' + forWhom,
            function(data) {
                $('#adress_list').html(data);
            }
        );
    });
    
    /*
     * Блокируем виды оповещений, если выбран пункт "Публикация в личном кабинете"
     */
    $('#type_notice').on('change', function (){
        var valueType = $('input[name*=isPrivateOffice]:checked').val();

        if (valueType === '0') {
            $('input[id^=is_notice]').prop('disabled', true);
            $('input[id^=is_notice]').prop('checked', false);
        } else {
            $('input[id^=is_notice]').prop('disabled', false);
            $('input[id^=is_notice]').prop('checked', false);            
        }
    });
    
    /*
     * Перед загрузкой модального окна на удаление новости, присваиваем в дата атрибут ID новости
     */
    $('#delete_news_manager').on('show.bs.modal', function(e) {
        var newsId = $(e.relatedTarget).data('news');
        var isAdvert = $(e.relatedTarget).data('isAdvert');
        $('.delete_news__del').data('news', newsId);
        $('.delete_news__del').data('isAdvert', isAdvert);
    });

    /*
     * Запрос на удаление новости
     */
    $('.delete_news__del').on('click', function(){
        var newsId = $(this).data('news');
        var isAdvert = $(this).data('isAdvert');
        
        $.ajax({
            url: '/web/managers/news/delete-news',
            method: 'POST',
            data: {
                newsId: newsId,
                isAdvert: isAdvert,
            },
            success: function(responce){
//                console.log(responce.success);
            },
            error: function(){
                console.log('error');
            },
        });
    });
    
    /*
     * Запрос на удаление прикрепленного документа
     */
    $('.delete_file').on('click', function(){
       var fileId = $(this).data('files');
       alert(fileId);
       $.ajax({
           url: 'delete-file',
           method: 'POST',
           data: {
               fileId: fileId,
           },
           success: function(response){
//               console.log(response.status);
           },
           error: function(){
               console.log('error');
           },
       });
    });
    
    /*
     * Если выбран параметр "Реклама" то список партнеров делаем доступным
     */
    $('#check_advet').on('change', function(){
       if ($('#check_advet').is(':checked')) {
           $('#parnters_list').prop('disabled', false);
       } else {
           $('#parnters_list').val('0');
           $('#parnters_list').prop('disabled', true);
       }
    });
    
    // ******************************************************** //
    // ************     Start Block of Voting    ************** //
    // ******************************************************** //
    /*
     * Переключатель, тип голосования
     *      Для дома
     *      Для подъезда
     */
    $('#type_voting').on('change', function(e) {
        var objectType = $("#create-voting input[type='radio']:checked").val();
//        if (forWhom === '0') {
//            $('#adress_list').prop('disabled', true);
//        } else {
//            $('#adress_list').prop('disabled', false);
//        }
        $.post('for-whom-voting?status=' + objectType,
            function(data) {
                $('#object_vote_list').html(data);
            }
        );
    });

    $('#delete_voting_manager').on('show.bs.modal', function(e){
        var votingId = $(e.relatedTarget).data('voting');
        $('.delete_voting__del').data('voting', votingId);
    })
    
    /*
     * Запрос на удаление голосования
     */
    $('.delete_voting__del').on('click', function(){
        var votingId = $(this).data('voting');
        $.ajax({
            url: 'confirm-delete-voting',
            method: 'POST',
            data: {
                votingId: votingId,
            },
            success: function (response){
                return console.log(response.success);
            },
            error: function (){
                return console.log('error');                
            },
        })
    });
    
    /*
     * Запрос на подтверждение закрытия голосования
     */
    var messageText;
    $('.close_voting_btn').on('click', function(e){
        var votingId = $(this).data('voting');
        $.ajax({
            url: 'confirm-close-voting',
            method: 'POST',
            data: {
                votingId: votingId,
            },
            success: function(response) {
                if (response.success === true && response.close === 'ask') {
                    messageText = response.message + ' <b>' + response.title + '</b>?';
                } else if (response.success === true && response.close === 'yes') {
                    messageText = response.message + ' <b>' + response.title + '</b>?';
                }
                $('#close_voting_manager .close_voting_yes').data('voting', votingId);
                $('#close_voting_manager').modal('show');
            },
            error: function(){
                console.log('error');
            },
        });
    });
    
    /*
     * Закрытие голосования
     */
    $('.close_voting_yes').on('click', function(e){
        e.preventDefault();
        var votingId = $(this).data('voting');
        $.ajax({
            url: 'close-voting',
            method: 'POST',
            data: {
                votingId: votingId,
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            },
        });
        return false;
    });
    
    $('#close_voting_manager').on('show.bs.modal', function(e){
        $(this).find('.modal__text p').html(messageText);
    });   
    
    
    // ******************************************************** //
    // ************     Start Block of Estates   ************** //
    // ******************************************************** //    
    
    /*
     * Метод загрузки модального окна на редактирование описания о доме
     */
    $('a#edit-discription-btn').on('click', function(e){
        var link = $(this).attr('href');
        $('#edit-description-house').modal('show');
        $('#edit-description-house .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });
    
    $(document).on('click', '#house_link', function(){
        var house = $(this).attr('href');
        house = house.replace(/[^0-9]/gim, '');
        // Устанавливаем куку, выбранного дома
        setCookie('_house', house);
        
        $.ajax({
            url: 'view-characteristic-house',
            method: 'POST',
            data: {
                house: house,
            },
            success: function (response) {
                $('#characteristic_list').html(response.data);
                $('#flats_list').html(response.flats);
                $('#files_list').html(response.files);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            }
        });
    });

    /*
     * Полное удаление или восстановление выбранной характеристики дома
     */
    // Массив будет содержать клонированные участки кода с выбранными для удаления характеристиками
    var chooseArray = new Array();
    $('#characteristic_list').on('click', '#delete-characteristic__link', function(){
        var html = $(this).closest('tr').html();
        var block = $(this).closest('tr');
        var characteristicId = $(this).data('characteristicId');
        chooseArray.push({'html':html});
        var num = chooseArray.length;

        block.html(
                "<span class='rest_char' id='rest_" + num + "'>Восстановить</span> | " + 
                "<span class='delete_char' id='char_" + characteristicId + "'>Удалить</span>")
        
        $("span[id='rest_" + num + "']").on('click', function () {
            var num = $(this).attr('id');
            num = num.replace(/[^0-9]/gim, '') - 1;
            var parent = $(this).parent();
            parent.html(chooseArray[num]['html']);
        });
        /*
         * Удаление характеристики
         */
        $("span[id='char_" + characteristicId + "']").on('click', function () {
            var charId = $(this).attr('id');
            charId = charId.replace(/[^0-9]/gim, '');
            $.ajax({
                url: 'delete-characteristic',
                method: 'POST',
                data: {
                    charId: charId,
                },
                success: function (data, textStatus, jqXHR) {
                    if (data.success === true) {
                        block.remove();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                },
            });
            
        });        
        
    });

    /*
     * Загрузка модального окна для добавления новой характеристики
     */
    $('a#add-charact-btn').on('click', function(e){
        // Перед загрузкой модального окна, проверяем наличие куки выбранного дома
        if (!getCookie('_house')) {
            $('#estate_house_message_manager .modal-title').text(
                    'Ошибка добавления характеристики');
            $('#estate_house_message_manager .modal__text').text(
                    'Для добавления характеристики, пожалуйста, выберите дом из списка "Жилой комплекс" слева');
            $('#estate_house_message_manager').modal('show');
            return false;
        }
        
        var link = $(this).attr('href');
        $('#add-characteristic-modal-form').modal('show');
        $('#add-characteristic-modal-form .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });

    /*
     * Загрузка модального окна для загружки документов
     */
    $('a#add-files-btn').on('click', function(e){
        // Перед загрузкой модального окна, проверяем наличие куки выбранного дома
        if (!getCookie('_house')) {
            $('#estate_house_message_manager .modal-title').text(
                    'Ошибка загрузки документа');
            $('#estate_house_message_manager .modal__text').text(
                    'Для загрузки документа, пожалуйста, выберите дом из списка "Жилой комплекс" слева');
            $('#estate_house_message_manager').modal('show');
            return false;
        }
        
        var link = $(this).attr('href');
        $('#add-load-files-modal-form').modal('show');
        $('#add-load-files-modal-form .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });
    
    /*
     * Загрузка модального окна для установки статуса "Должник"
     * по чекбоксу "Должник"
     */
    $('#flats_list').on('change', '#check_status__flat', function(){
        var flatId = $(this).data('flat');
        var link = 'check-status-flat';
        
        if (!$(this).is(':checked')) {
            $('#estate_note_message_manager').modal('show');
            $('#estate_note_message_manager .estate_note_message__yes').data('flat', flatId);            
        } else {
            $('#add-note-modal-form').modal('show');
            $('#add-note-modal-form .modal-dialog .modal-content .modal-body').load(link, 'flat_id=' + flatId);
        }
    });
    /*
     * Загрузка модального окна для добавление нового примечания
     * по кнопке "Новое примечание"
     */
    $('#flats_list').on('click', '#add-note', function(){
        var flatId = $(this).data('flat');
        var link = 'check-status-flat';
        $('#add-note-modal-form').modal('show');
        $('#add-note-modal-form .modal-dialog .modal-content .modal-body').load(link, 'flat_id=' + flatId);
    });
    
    $('.estate_note_message__yes').on('click', function(){
        var flatId = $(this).data('flat');
        $.ajax({
            url: 'take-off-status-debtor',
            method: 'POST',
            data: {
                flatId: flatId,
            },
            success: function (response) {
                if (response.success) {
                    // Удаляем html блок со всеми примечаниями
                    $('tr[id=note_flat__tr-' + flatId + ']').remove();
                    $('#check_status__flat').attr('checked', false);
                    $('#estate_note_message_manager').modal('hide');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);                
            }
        });
        return false;
    });
    
    /*
     * Удаление примечания для квартиры
     */
    $('#flats_list').on('click', '.flat_note__delete', function(e){
        e.preventDefault();
        var noteId = $(this).data('note');
        var htmlBlock = $(this).closest('tr');
        // Количество строк с примечаниями
        var countTr = $('tr[id^=note_flat__tr]').length;
        
        $.ajax({
            url: 'delete-note-flat',
            method: 'POST',
            data: {
                noteId: noteId,
            },
            success: function (responce, textStatus, jqXHR) {
                console.log('responce.success ' + responce.success);
                if (responce.success === true) {
                    htmlBlock.remove();
                }
                // Если количество примечаний <=1 + Строка заголовок с кнопкой "Добавить примечание", то снимаем с квартиры статус Должник
                if (countTr <= 2) {
                    $('#add-note').closest('tr').remove();
                    $('#check_status__flat').attr('checked', false);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);                
            }
        });
        return false;
    })
    
    /*
     * Удаление прикрепленного документа
     */
    $('#files_list').on('click', '#delete_file__house', function(e) {
        var blockHtml = $(this).closest('tr');
        var fileId = $(this).data('files');
        e.preventDefault();
        $.ajax({
            url: 'delete-files-house',
            method: 'POST',
            data: {
                fileId: fileId,
            },
            success: function (response) {
                if (response.success === true){
                    blockHtml.remove();
                } else if (response.success === false) {
                    blockHtml.html('Ошибка удаления документа');
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            },
        });
        return false;
    });
    
    /*
     * Запрос на загрузку файла с сервера
     */
    $('#files_list').on('click', '#download-file__house', function(e) {
        var blockHtml = $(this).closest('tr');
        var fileId = $(this).data('files');
        e.preventDefault();
        $.ajax({
            url: 'download-files-house',
            method: 'POST',
            data: {
                fileId: fileId,
            },
            success: function (response) {
                if (response.success === true){
                    blockHtml.remove();
                } else if (response.success === false) {
                    blockHtml.html('Ошибка удаления документа');
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            },
        });
        return false;
    });


    /*
     * Установка куки
     */
    function setCookie(name, value, expire){
        var date = new Date(new Date().getTime() + (expire * 1000 * 60 * 60 * 24)).toUTCString();
        document.cookie = name + '=' + value + '; path=/; expires=' + date;
    }

    function getCookie(name) {
      var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
      ));
      return matches ? decodeURIComponent(matches[1]) : undefined;
    } 
    

    
 });
    


