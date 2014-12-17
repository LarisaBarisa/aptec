
var i=0;
function AddInput()
{
	
	//    ������� ����� �������
	var INPUT = document.createElement("select");

	//    ��������� �������
	INPUT.setAttribute("type", 'text');
	INPUT.setAttribute("id", 'drugs_pack'+i);
	INPUT.setAttribute("name", 'drugs_pack'+i);
	
	var PARENT = document.getElementById("recipe_form");
	//    ���� ������� �� ������������ �� ��������
    //    ��������� ��������� ������� INPUT � �������� PARENT
	PARENT.appendChild(INPUT);
	
	var PACKS = document.createElement("input");
	PACKS.setAttribute("type", 'number');
	PACKS.setAttribute("id", 'packs'+i);
	PACKS.setAttribute("name", 'packs'+i);
	PACKS.setAttribute("min", '1');
	PACKS.setAttribute("max", '10');
	PACKS.setAttribute("value", '1');
	PARENT.appendChild(PACKS);
	
	var BR = document.createElement("br");
	PARENT.appendChild(BR);
/*
 * ��� ������ �������� ���������
 * �� �������� ���������� �������
 */


}
$(document).ready(function () {
/*
 */
    $('#AddButton').click(function () {
/*
 *
 */
      //  var region_id = '1';
/*
 *
 */
        
/*
 * 
 */
        var url = 'get_drugs.php';
/*
 * GET'овый AJAX запрос
 * подробнее о синтаксисе читайте
 * на сайте http://docs.jquery.com/Ajax/jQuery.get
 * Данные будем кодировать с помощью JSON
 */
        $.get(
            url,
            '2',
			function (result) {
                if (result.type == 'error') {
                    alert('error');
                    return(false);
                }
                else {
/*
 * проходимся по пришедшему от бэк-энда массиву циклом
 */
                    var options = '';
                    $(result.drugs).each(function() {
/*
 * и добавляем в селект по региону
 */
                        options += '<option value="' + $(this).attr('drugs_id') + '">' + $(this).attr('drugs_name') +' - '+$(this).attr('cost')+'�'+'</option>';
                    });
 
                    $('#drugs_pack'+i).html('<option value="0">- �������� ��������� -</option>'+options);

					i++;
                }
            },
            "json"
        );
		$.get('recipe_request.php',	{numb:i});
    });
/*
 * Те же действия проделываем с выбором города 
 */
});