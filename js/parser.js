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
            if(typeof(d.history)=="undefined" || d.history==null)return;
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
