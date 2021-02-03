function PNOTY(_text,_type){
	new PNotify({
	    text: _text,
	    icon : false,
	    type:_type,
	    buttons: {
	      	sticker: false
	    }
	});
}


function delete_confirm(url) {
	swal({
		title: "Are you sure want to delete this?",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "Yes, delete it!",
		closeOnConfirm: false
	},
	function(isConfirm) {
		if (isConfirm) {
			
		} else {
			return false;
		}
	});
}

$(function(){
    $('.select2').select2();

    
	$(document).on('keydown','.decimal-num', function(event){

        
		if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190 || event.keyCode == 110) {

		} else {
			event.preventDefault();
		}
		
		if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
			event.preventDefault();

		if($(this).val().indexOf('.') !== -1 && event.keyCode == 110)
			event.preventDefault();

	});

    $(document).on('keydown','.minus-decimal-num', function(event){
        if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190 || event.keyCode == 110 || event.keyCode == 189 || event.keyCode == 109) {

        } else {
            event.preventDefault();
        }
        
        if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
            event.preventDefault();

        if($(this).val().indexOf('.') !== -1 && event.keyCode == 110)
            event.preventDefault();

        if($(this).val().indexOf('-') !== -1 && event.keyCode == 109)
            event.preventDefault();

        if($(this).val().indexOf('-') !== -1 && event.keyCode == 189)
            event.preventDefault();

    });

	$(document).on('keydown','.numbers', function(event){

		if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) {

		} else {
            // Ctrl+C or Cmd+C pressed?
            if ((event.ctrlKey || event.metaKey) && event.keyCode == 67) {

                
            }

            // Ctrl+V or Cmd+V pressed?
            else if ((event.ctrlKey || event.metaKey) && event.keyCode == 86) {
                
            }

            // Ctrl+X or Cmd+X pressed?
            else if ((event.ctrlKey || event.metaKey) && event.keyCode == 88) {
             // Do stuff.
            } else{
                event.preventDefault();
            } 
		}

	});

	$(document).on('keydown','.time-text', function(event){

		if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 186) {

		} else {
			event.preventDefault();
		}


		if($(this).val().indexOf(':') !== -1 && event.keyCode == 186)
			event.preventDefault();

	});

	$('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight:'TRUE',
        autoclose: true
    }).keydown(function(e) {
        return false;
    });

    $('.birth-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        endDate: '-15y',
        startDate: '-100y'
    }).keydown(function(e) {
        return false;
    });

	var date = new Date();
   var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('.datepicker-new').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        startDate: new Date()
    }).keydown(function(e) {
        return false;
    });

    $('.checkAll').click(function(){
            if($(this).prop("checked")) {
                $(".checkBox").prop("checked", true);
            } else {
                $(".checkBox").prop("checked", false);
            }                
        });


    $('.checkBox').click(function(){
        if($(".checkBox").length == $(".checkBox:checked").length) { 
            $(".checkAll").prop("checked", true);
        }else {
            $(".checkAll").prop("checked", false);            
        }
    });
})


function readFile(input) {
    if (input.files && input.files[0]) {
        
        var FileSize = input.files[0].size / 1024 / 1024; // in MB
        var extension = input.files[0].name.substring(input.files[0].name.lastIndexOf('.')+1);
        
        if (FileSize > 2) {
            alert("Maxiumum File Size Is 2 Mb.");
            input.value = '';
            return false;
        }
        else{
            if (extension == 'jpg' || extension == 'png' || extension == 'jpeg' || extension == 'docx' || extension == 'pdf' || extension == 'csv' || extension == 'xlsx') {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $("#imgProfile").attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
            else
            {
                alert("Only Allowed '.jpg' OR '.png' OR '.jpeg' OR '.docx' OR '.pdf' OR '.csv' OR '.xlsx' Extension ");
                input.value = '';
                return false;
            }
        }
    }
}

function pdfImageAllowed(input) {
    if (input.files && input.files[0]) {
        
        var FileSize = input.files[0].size / 1024 / 1024; // in MB
        var extension = input.files[0].name.substring(input.files[0].name.lastIndexOf('.')+1);
        
        if (FileSize > 5) {
            alert("Maxiumum File Size Is 4 Mb.");
            input.value = '';
            return false;
        }
        else{
            if (extension == 'jpg' || extension == 'png' || extension == 'jpeg' || extension == 'pdf') {
                
            }
            else
            {
                alert("Only Allowed '.jpg' OR '.png' OR '.jpeg' OR '.docx' OR '.pdf' OR '.csv' OR '.xlsx' Extension ");
                input.value = '';
                return false;
            }
        }
    }
}

function excelAlowed(input) {
    if (input.files && input.files[0]) {
        
        var FileSize = input.files[0].size / 1024 / 1024; // in MB
        var extension = input.files[0].name.substring(input.files[0].name.lastIndexOf('.')+1);
        
        if (FileSize > 2) {
            alert("Maxiumum File Size Is 2 Mb.");
            input.value = '';
            return false;
        }
        else{
            if (extension == 'xlsx') {
                // var reader = new FileReader();

                // reader.onload = function (e) {
                //     $("#imgProfile").attr('src', e.target.result);
                // }

                //reader.readAsDataURL(input.files[0]);
            }
            else
            {
                alert("Only Allowed '.xlsx' Extension ");
                input.value = '';
                return false;
            }
        }
    }
}

function readFileImage(input) {
    if (input.files && input.files[0]) {
        
        var FileSize = input.files[0].size / 1024 / 1024; // in MB
        var extension = input.files[0].name.substring(input.files[0].name.lastIndexOf('.')+1);
        
        if (FileSize > 2) {
            alert("Maxiumum Image Size Is 2 Mb.");
            input.value = '';
            return false;
        }
        else{
            if (extension == 'jpg' || extension == 'png' || extension == 'jpeg') {
                
            }
            else
            {
                alert("Only Allowed '.jpg' OR '.png' OR '.jpeg'");
                input.value = '';
                return false;
            }
        }
    }
}


(function timeAgo(selector) {

    var templates = {
        prefix: "",
        suffix: " ago",
        seconds: "less than a minute",
        minute: "about a minute",
        minutes: "%d minutes",
        hour: "about an hour",
        hours: "about %d hours",
        day: "a day",
        days: "%d days",
        month: "about a month",
        months: "%d months",
        year: "about a year",
        years: "%d years"
    };
    var template = function(t, n) {
        return templates[t] && templates[t].replace(/%d/i, Math.abs(Math.round(n)));
    };

    var timer = function(time) {
        if (!time)
            return;
        time = time.replace(/\.\d+/, ""); // remove milliseconds
        time = time.replace(/-/, "/").replace(/-/, "/");
        time = time.replace(/T/, " ").replace(/Z/, " UTC");
        time = time.replace(/([\+\-]\d\d)\:?(\d\d)/, " $1$2"); // -04:00 -> -0400
        time = new Date(time * 1000 || time);

        var now = new Date();
        var seconds = ((now.getTime() - time) * .001) >> 0;
        var minutes = seconds / 60;
        var hours = minutes / 60;
        var days = hours / 24;
        var years = days / 365;

        return templates.prefix + (
                seconds < 45 && template('seconds', seconds) ||
                seconds < 90 && template('minute', 1) ||
                minutes < 45 && template('minutes', minutes) ||
                minutes < 90 && template('hour', 1) ||
                hours < 24 && template('hours', hours) ||
                hours < 42 && template('day', 1) ||
                days < 30 && template('days', days) ||
                days < 45 && template('month', 1) ||
                days < 365 && template('months', days / 30) ||
                years < 1.5 && template('year', 1) ||
                template('years', years)
                ) + templates.suffix;
    };

    var elements = document.getElementsByClassName('timeago');
    for (var i in elements) {
        var $this = elements[i];
        if (typeof $this === 'object') {
            $this.innerHTML = timer($this.getAttribute('date') || $this.getAttribute('datetime'));
        }
    }
    // update time every minute
    setTimeout(timeAgo, 30000);

})();
