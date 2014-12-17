/*
 * ѕри полной загрузке документа
 * мы начинаем определ€ть событи€
 */

$(document).ready(function () {
/*
 * Ќа выборе селекта страны Ч вешаем событие,
 * функци€ будет брать значение этого селекта
 * и с помощью ajax запроса получать список
 * регионов дл€ вставки в следующий селект
 */
    $('#region_id').change(function () {
/*
 * ¬ переменную country_id положим значение селекта
 * (выбранна€ страна)
 */
        var region_id = $(this).val();
/*
 * ≈сли значение селекта равно 0,
 * т.е. не выбрана страна, то мы
 * не будем ничего делать
 */
        if (region_id == '0') {
            $('#city_id').html('<option>- выберите город -</option>');
            $('#city_id').attr('disabled', true);
            $('#pharmacy_id').html('<option>- выберите удобную дл€ вас аптеку -</option>');
            $('#pharmacy_id').attr('disabled', true);
            return(false);
/*
 * ќчищаем второй селект с регионами
 * и блокируем его через атрибут disabled
 * туда мы будем класть результат запроса
 */
        }
        $('#city_id').attr('disabled', true);
        $('#city_id').html('<option>загрузка...</option>');
/*
 * url запроса регионов
 */
        var url = 'get_city.php';
/*
 * GET'овый AJAX запрос
 * подробнее о синтаксисе читайте
 * на сайте http://docs.jquery.com/Ajax/jQuery.get
 * ƒанные будем кодировать с помощью JSON
 */
        $.get(
            url,
            "region_id=" + region_id,
            function (result) {
                if (result.type == 'error') {
                    alert('error');
                    return(false);
                }
                else {
/*
 * проходимс€ по пришедшему от бэк-энда массиву циклом
 */
                    var options = '';
 
                    $(result.citys).each(function() {
/*
 * и добавл€ем в селект по региону
 */
                        options += '<option value="' + $(this).attr('city_id') + '">' + $(this).attr('city_name') + '</option>';
                    });
 
                    $('#city_id').html('<option value="0">- выберите город -</option>'+options);
                    $('#city_id').attr('disabled', false);
                    $('#pharmacy_id').html('<option>- выберите удобную дл€ вас аптеку -</option>');
                    $('#pharmacy_id').attr('disabled', true);          
                }
            },
            "json"
        );
    });
/*
 * “е же действи€ проделываем с выбором города 
 */
$('#city_id').change(function () {
        var city_id = $('#city_id :selected').val();
        if (city_id == '0') {
            $('#pharmacy_id').html('<option>- выберите аптеку -</option>');
            $('#pharmacy_id').attr('disabled', true);
            return(false);
        }
        $('#pharmacy_id').attr('disabled', true);
        $('#pharmacy_id').html('<option>загрузка...</option>');
        var url = 'get_pharmacy.php';      
        $.get(
            url,
            "city_id=" + city_id,
 
            function (result) {
                if (result.type == 'error') {
                    alert('error');
                    return(false);
                }
                else {
                    var options = '';
                    $(result.pharmacys).each(function() {
                        options += '<option value="' + $(this).attr('address') + '">' + $(this).attr('address') + '</option>';
                    });
 
                    $('#pharmacy_id').html('<option>- выберите удобную дл€ вас аптеку -</option>'+options);
                    $('#pharmacy_id').attr('disabled', false);
                }
            },
            "json"
        );
    });
});