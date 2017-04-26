//get query parameters
var qp = {};
var __p = window.location.href.match(/\?(.+)$/);
if(__p!=null&&__p.length>1){
    var __a = __p[1].split(/\&/);
    for(var i in  __a){
        var nv = __a[i].split(/=/);
        qp[nv[0]] = decodeURIComponent(nv[1].replace(/\+/ig," "));
    }
}

$(document).ready(function(){
    if(typeof(qp.vin)!="undefined"){
        var timerId;
        $.ajax({
            url:"report.php?vin="+qp.vin,
            dataType:"json",
            beforeSend:function(){
                $(".vin-report").hide();
                //$(".request-status-title").html((typeof(qp.type)!="undefined"&&qp.type=="full")?"":"");
                $(".request-status-title").html("");
                $(".request-status").html('<span class="modal-window-text">Ваш отчёт по VIN коду - '+qp.vin+' подготавливается.</span>'
                    +'<span class="modal-window-text">Пожалуйста подождите, идет обработка запроса.<br />Ожидаемое время формирования через: <span class="request-status-timer">45</span> секунд.</span>'
                );
                openModal("#modal2");
                timerId = setInterval(function(){
                    var cv =parseInt($(".request-status-timer").text());
                    cv--;
                    $(".request-status-timer").text(cv);
                    if(cv == 0 ){
                        $(".request-status").html(
                            '<span class="modal-window-text">Ваш отчёт по VIN коду - '+qp.vin+' все еще формируется.</span>'
                                +'<span class="modal-window-text">"В данный момент очень много запросов к нашей системе.<br />Пожалуй, нам нужно еще <span class="request-status-timer">30</span> секунд.</span>'

                        )
                    }

                },1000);
            },
            success:function(d){
                try{
                    putData($(".vin-report"),[
                        {
                            title:"Модель",
                            code:"vehicle-model",
                            value:d.history.RequestResult.vehicle.model
                        },
                        {
                            title:"VIN",
                            code:"vehicle-vin",
                            value:d.history.RequestResult.vehicle.vin
                        },
                        {
                            title:"Год производства",
                            code:"vehicle-year",
                            value:d.history.RequestResult.vehicle.year
                        },
                        {
                            title:"Цвет",
                            code:"vehicle-color",
                            value:d.history.RequestResult.vehicle.color
                        },
                        {
                            title:"Мощность двигателя",
                            code:"vehicle-powerHp",
                            value:parseFloat(d.history.RequestResult.vehicle.powerHp).toFixed(0)+" л.с."
                        },
                        {
                            title:"Объём двигателя",
                            code:"vehicle-engineVolume",
                            value:parseFloat(d.history.RequestResult.vehicle.engineVolume).toFixed(0)+" куб. см."
                        },
                        {title:"Комплектация автомобиля",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Участие в ДТП",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Ограничения на регистрационные действия",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Проверка на нахождение в залоге",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Информация об угоне и розыске",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Пробег машины",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Количество владельцев ТС",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"История регистрационных действий",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Сведения о страховке ОСАГО",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Информация о утилизации",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Статус владельца (физ / юр. лицо)",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Использование в качестве такси",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Таможенная история",code:"hidden",value:"Доступно в полном отчёте"},
                        {title:"Сведения о покупке в лизинг",code:"hidden",value:"Доступно в полном отчёте"}
                    ]);
                    closeModal();
                    $(".vin-report").show();
                }
                catch(e){
                    console.debug(e);
                    $(".request-status").html(
                        '<span class="modal-window-text">Ваш отчёт по VIN коду - '+qp.vin+' не сформировалася.</span>'
                        +'<button class="button" onclick="document.location.reload();">Повторить попытку</button>'

                    )
                }
            },
            complete:function(){
                clearInterval(timerId);

            }
        });
    }

});
