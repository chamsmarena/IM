$(document).ready(function(){

    
    var today = new Date();
    getActualite(today.getFullYear()+"/01/01",today.getFullYear()+"/12/31","","");
    
    

    
    
    $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $( "#dateActualite" ).datepicker('update', today);
    $( "#results" ).hide();
   
    $("#PostLocation").on('keyup',function () {
        var key = $(this).val();

        $.ajax({
            url:'fetch.php',
            type:'GET',
            data:'keyword='+key+'&idAdmin0='+$("#PostPays").val(),
            beforeSend:function () {
                $("#results").hide();
            },
            success:function (data) {
                $("#results").html(data);
                $("#results").show();
                //$("#results").slideDown('fast');
            }
        });
    });


	ResizeBlocs();
});

$("#PostTypeActu,#PostMenace").change(function(){
    console.log($("#PostTypeActu").val());
    $("#iconPreview").attr("src", "../images/events/"+$("#PostTypeActu").val()+$("#PostMenace").val()+"Group.svg");
});
$("#dateEnd,#dateStart").change(function(){
	FiltrerActualite();
	$(".datepicker").hide();
});
$("#menulogOut").click(function(){
    logOut();
});
$("#addNews").click(function(){
    postActualite();
});
$(window).resize(function() {
	ResizeBlocs();
	ShowActualiteDetails();
});



