$(function(){
    $("#showform").click(function(){
        $("#winpopup").dialog({
            draggable:true,
            modal: true,
            autoOpen: false,
            height:300,
            width:400,
            resizable: false,
            title:'Form Ajax',
            position:'center'
        });
        $("#winpopup").load($(this).attr('href'));
        $("#winpopup").dialog("open");
        
        return false;
    });
});