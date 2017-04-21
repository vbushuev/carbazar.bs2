"use strict";
var getYandexKassaForm = function(){
    var email = $("#buyForm [name=email]").val(),
        phone = $("#buyForm [name=phone]").val(),
        amount = $("#buyForm .report-price .price").text(),
        promo = $("#buyForm [name=promo]").val();
    $("#buyForm [name=sum]").val(amount);
    $("#buyForm [name=customerNumber]").val('cb_'+email);
    $("#buyForm [name=cps_email]").val(email);
    $("#buyForm [name=cps_phone]").val(phone);
}
window.getYandexKassaForm = getYandexKassaForm;
$(document).ready(function(){
    $(".payment-button").on("click",function(e){
        getYandexKassaForm();
        return true;
    });
});
