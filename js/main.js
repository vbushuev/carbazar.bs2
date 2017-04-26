// Модальные окна
function openModal(modal) {
	$(".modal-window").fadeIn(400);

	$(".modal-window-wrapper").children().not(".close-button").fadeOut(0);
	$(".modal-window-overlay").height($(modal).height() + 100);
	$(modal).fadeIn(400);

	$("body").css("overflow", "hidden");
}
function closeModal() {
	$(".modal-window").fadeOut(400);
	$("body").css("overflow", "auto");
}
var putData = function(){
    var $content = (arguments.length)?arguments[0]:null, data = (arguments.length>1)?arguments[1]:null,s="";
    if(data==null||$content==null)return;
    if(! $content instanceof jQuery) $content = $($content);
    if(!Array.isArray(data))data=[data];
    $content = $('<ul class="vin-report-list"></ul>').appendTo($content);
    for(var i in data){
        s = '<li class="vin-report-data">';
		if(typeof(data[i].value)!="undefined"){
			s+= '<span class="vin-report-data-title">'+data[i].title+'</span>';
	        s+= '<span class="vin-report-data-value '+((typeof(data[i].code)!="undefined")?data[i].code:'data-value')+'">'+data[i].value+'</span>';
		}
        else {
			s+= '<span class="vin-report-data-value single">'+data[i].title+'</span>';
		}
		if(typeof(data[i].hint)!="undefined") s+= '<span class="vin-report-data-hint">'+data[i].hint+'</span>';
        s+= '</li>';
        $content.append(s);
    }
}
$(document).ready(function() {
	// Мобильное меню
	$(".menu-button").click(function () {
		$(".site-nav-menu").slideToggle(300);
	});

    $(".open-modal").click(function (e) {
        e.preventDefault();
        openModal($(this).attr("data-modal"));

        if($(this).attr("data-modal") == "#modal3") {
            $(".close-button").hide();
        }
        else {
            $(".close-button").show();
        }
    });

    $(".modal-window-overlay, .close-button").click(function () {
    	if ($("#modal2").is(":hidden")) {
    		closeModal();
    	}
    	else {
    		return false;
    	}
    });

    // Переключатели инпутов
    var toggleInput = $(".toggle-input");

    for (i = 0; i < toggleInput.length; ++i) {
    	var toggleInputAttr = $(toggleInput[i]).attr("data-toggle");

    	if ($(toggleInput[i]).is(":checked")) {
    		$(toggleInputAttr).show();
    	}
    	else {
    		$(toggleInputAttr).hide();
    	}
    }

    $(".toggle-input").change(function() {
    	var toggleInputAttr = $(this).attr("data-toggle");

    	if ($(this).is(":checked")) {
    		$(toggleInputAttr).show();
    	}
    	else {
    		$(toggleInputAttr).hide();
    	}
    });


    // Якоря
    $("a[href^='#']").click(function(e) {
        e.preventDefault();

        var scrollElement = $(this).attr("href");

        if ($(scrollElement).length != 0) {
        	if($(scrollElement).parents(".modal-window").length > 0) {
	        	if ($(document).width() > 960) {
	            	$(".modal-window").animate({ scrollTop: $(scrollElement).offset().top }, 500);
	            }
	            else {
	            	$(".modal-window-wrapper").animate({ scrollTop: $(scrollElement).offset().top }, 500);
	            }
        	}
        	else {
        		$("html, body").animate({ scrollTop: $(scrollElement).offset().top }, 500);
        	}
        }
    });


    // Маски текстовых полей
    $("input[name=phone]").inputmask("+7 (999) 999-99-99", {showMaskOnHover: false});


	// Спойлеры
	$(".spoiler-button").click(function () {
		$(this).next(".spoiler-content").slideDown(300);
		$(this).hide();
	});


	// Форма обратной связи
    $(".contact-form").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "mail/mail.php",
            data: $(this).serialize(),
            success: function(responce) {
                closeModal();
                alert(responce);
            }
        });
    });
	$(".print").on("click",function(e){
		$(".modal-window").scrollTop(0);
		window.print();
	});
	$(".pdf").on("click",function(e){

		$(".modal-window").css("position","relative").css("overflow-y","visible").css("display","block");

		html2canvas($(".vin-full-report-container"),{
			onrendered: function(img) {
				var doc = new jsPDF({
					unit: 'px',
 					format: [img.width, img.height]
				});
				console.debug(img,$(".vin-full-report-container").width(),$(".vin-full-report-container").height());
				//window.location.href = img.toDataURL('image/png');
				doc.addImage(img.toDataURL('image/png'), 'PNG', 0,0,img.width,img.height);
				doc.save(qp.vin+'.pdf');
			}
		});
		$(".modal-window").css("position","fixed").css("overflow-y","scroll");
	});
});
