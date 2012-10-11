$(function(){
    $("form#TestEntity").submit(function(){
        
        if (is_xmlhttprequest == 0)
            return true;
        
        $.post(urlform,
               { 'name' : $('input[name=name]').val() }, function(itemJson){
                
                var error = false;
                
                if (itemJson.name != undefined){
                    
                    if ($(".element_name ul").length == 0){
                        //prepare ...
                        $(".element_name").append("<ul></ul>");
                    }
                    
                    for(var i=0;i<itemJson.name.length;i++)
                    {
                       if ($(".element_name ul").html().substr(itemJson.name[i]) == '')
                            $(".element_name ul").append('<li>'+itemJson.name[i]+'</li>');
                    }
                    
                    error = true;
                }
                
                if (!error){
                    $("#winpopup").dialog('close');
                    
                    if (itemJson.success == 1){
                        alert('Data saved');   
                    }
                }
                
        }, 'json');
        
        return false;
    });
});    