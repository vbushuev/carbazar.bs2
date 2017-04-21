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
if(typeof(qp.vin)!="undefined"){
    $.ajax({
        url:"report.php?vin="+qp.vin,
        dataType:"json",
        before:function(){

        },
        success:function(d){
            console.debug(d);
            if(typeof(d.history)=="undefined")return;
            if(typeof(d.history.RequestResult)=="undefined")return;
            if(typeof(d.history.RequestResult.vehicle)=="undefined")return;
            if(typeof(d.history.RequestResult.vehicle.model)=="undefined")return;

            $(".vechile-model").html(d.history.RequestResult.vehicle.model);
            $(".vechile-vin").html(d.history.RequestResult.vehicle.vin);
            $(".vechile-year").html(d.history.RequestResult.vehicle.year);
            $(".vechile-color").html(d.history.RequestResult.vehicle.color);
            $(".vechile-power").html(parseFloat(d.history.RequestResult.vehicle.powerHp).toFixed(0)+" л.с.");
            $(".vechile-volume").html(parseFloat(d.history.RequestResult.vehicle.engineVolume).toFixed(0)+" куб. см.");
        }
    });
}
var getLastVins = function(l){
    $.ajax({
        url:"/vin_base.php?l="+l,
        dataType:"json",
        success:function(d,x,s){
            console.debug(d);
            var date = new Date(),
                type = [
                    '<span class="check-category purchased">куплен полный отчёт для</span>',
                    '<span class="check-category">проверен автомобиль</span>',
                    '<span class="check-category">проверен автомобиль</span>'
                ];
            //$(".recent-numbers-list").html();
            for(var i in d){
                vin = d[i],
                    s = '<li class="recent-number"><div class="check-info">';
                s+='<span class="check-time">'+date.getHours()+':'+date.getMinutes()+'</span>';
                s+= type[(Math.random()*10)%type.length];
                s+='</div><div class="check-number"><div class="check-icon"><i class="icon icon-car"></i></div>';
                s+='<a class="link" href="#">'+vin+'</a></div></li>';
                $(".recent-numbers-list li").last().remove()
                $(".recent-numbers-list li").append(s);
            }
        }
    });
}
$(document).ready(function(){
    getLastVins(20);
    setInterval(function(){
        console.debug("Load vinbase");
        getLastVins(8);
    },1000);
});
