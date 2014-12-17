
var i=0;
function AddInput()
{
	
	//    Создаем новый элемент
	var INPUT = document.createElement("select");

	//    Добавляем атрибут
	INPUT.setAttribute("type", 'text');
	INPUT.setAttribute("id", 'drugs_pack'+i);
	INPUT.setAttribute("name", 'drugs_pack'+i);
	
	var PARENT = document.getElementById("recipe_form");
	//    пока элемент не отображается на странице
    //    Добавляем созданный элемент INPUT к элементу PARENT
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
 * При полной загрузке документа
 * мы начинаем определять события
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
 * GET'РѕРІС‹Р№ AJAX Р·Р°РїСЂРѕСЃ
 * РїРѕРґСЂРѕР±РЅРµРµ Рѕ СЃРёРЅС‚Р°РєСЃРёСЃРµ С‡РёС‚Р°Р№С‚Рµ
 * РЅР° СЃР°Р№С‚Рµ http://docs.jquery.com/Ajax/jQuery.get
 * Р”Р°РЅРЅС‹Рµ Р±СѓРґРµРј РєРѕРґРёСЂРѕРІР°С‚СЊ СЃ РїРѕРјРѕС‰СЊСЋ JSON
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
 * РїСЂРѕС…РѕРґРёРјСЃСЏ РїРѕ РїСЂРёС€РµРґС€РµРјСѓ РѕС‚ Р±СЌРє-СЌРЅРґР° РјР°СЃСЃРёРІСѓ С†РёРєР»РѕРј
 */
                    var options = '';
                    $(result.drugs).each(function() {
/*
 * Рё РґРѕР±Р°РІР»СЏРµРј РІ СЃРµР»РµРєС‚ РїРѕ СЂРµРіРёРѕРЅСѓ
 */
                        options += '<option value="' + $(this).attr('drugs_id') + '">' + $(this).attr('drugs_name') +' - '+$(this).attr('cost')+'р'+'</option>';
                    });
 
                    $('#drugs_pack'+i).html('<option value="0">- выберите лекарство -</option>'+options);

					i++;
                }
            },
            "json"
        );
		$.get('recipe_request.php',	{numb:i});
    });
/*
 * РўРµ Р¶Рµ РґРµР№СЃС‚РІРёСЏ РїСЂРѕРґРµР»С‹РІР°РµРј СЃ РІС‹Р±РѕСЂРѕРј РіРѕСЂРѕРґР° 
 */
});