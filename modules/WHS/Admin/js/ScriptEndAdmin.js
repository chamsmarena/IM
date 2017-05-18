$(document).ready(function(){

    
    var today = new Date();
    getActualite(today.getFullYear()+"/01/01",today.getFullYear()+"/12/31","","");
    
    

    
    
    $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    $( "#dateStart" ).datepicker('update', "01/01/"+today.getFullYear());
    $( "#dateEnd" ).datepicker('update', "31/12/"+today.getFullYear());
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


    //RECUPERATION DES VALEURS DE SELECT
    var x = document.getElementById("Pays").options;
    for(i=0; i < x.length; i++){
        listePays.push([x[i].value,x[i].label]);
    }
    var x = document.getElementById("typeActu").options;
    for(i=0; i < x.length; i++){
        listeThemes.push([x[i].value,x[i].label]);
    }
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



