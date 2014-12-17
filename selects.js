/*
 * ��� ������ �������� ���������
 * �� �������� ���������� �������
 */

$(document).ready(function () {
/*
 * �� ������ ������� ������ � ������ �������,
 * ������� ����� ����� �������� ����� �������
 * � � ������� ajax ������� �������� ������
 * �������� ��� ������� � ��������� ������
 */
    $('#region_id').change(function () {
/*
 * � ���������� country_id ������� �������� �������
 * (��������� ������)
 */
        var region_id = $(this).val();
/*
 * ���� �������� ������� ����� 0,
 * �.�. �� ������� ������, �� ��
 * �� ����� ������ ������
 */
        if (region_id == '0') {
            $('#city_id').html('<option>- �������� ����� -</option>');
            $('#city_id').attr('disabled', true);
            $('#pharmacy_id').html('<option>- �������� ������� ��� ��� ������ -</option>');
            $('#pharmacy_id').attr('disabled', true);
            return(false);
/*
 * ������� ������ ������ � ���������
 * � ��������� ��� ����� ������� disabled
 * ���� �� ����� ������ ��������� �������
 */
        }
        $('#city_id').attr('disabled', true);
        $('#city_id').html('<option>��������...</option>');
/*
 * url ������� ��������
 */
        var url = 'get_city.php';
/*
 * GET'���� AJAX ������
 * ��������� � ���������� �������
 * �� ����� http://docs.jquery.com/Ajax/jQuery.get
 * ������ ����� ���������� � ������� JSON
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
 * ���������� �� ���������� �� ���-���� ������� ������
 */
                    var options = '';
 
                    $(result.citys).each(function() {
/*
 * � ��������� � ������ �� �������
 */
                        options += '<option value="' + $(this).attr('city_id') + '">' + $(this).attr('city_name') + '</option>';
                    });
 
                    $('#city_id').html('<option value="0">- �������� ����� -</option>'+options);
                    $('#city_id').attr('disabled', false);
                    $('#pharmacy_id').html('<option>- �������� ������� ��� ��� ������ -</option>');
                    $('#pharmacy_id').attr('disabled', true);          
                }
            },
            "json"
        );
    });
/*
 * �� �� �������� ����������� � ������� ������ 
 */
$('#city_id').change(function () {
        var city_id = $('#city_id :selected').val();
        if (city_id == '0') {
            $('#pharmacy_id').html('<option>- �������� ������ -</option>');
            $('#pharmacy_id').attr('disabled', true);
            return(false);
        }
        $('#pharmacy_id').attr('disabled', true);
        $('#pharmacy_id').html('<option>��������...</option>');
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
 
                    $('#pharmacy_id').html('<option>- �������� ������� ��� ��� ������ -</option>'+options);
                    $('#pharmacy_id').attr('disabled', false);
                }
            },
            "json"
        );
    });
});