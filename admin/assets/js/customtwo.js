(function() {
    //document.querySelector('.hideing').style.display = 'none';

// for adding cities
    var i=2;
    numval = 2;
    $("#addmore").on('click',function(){
        var counts = $("#count_items").val();
        var counter = parseInt(counts);
        count=$('#plandesc tr').length;
        var data="<tr><td><input type='checkbox' class='case'/></td>";
        data +="<td><input type='text' class='form-control' placeholder='Enter Subscription Plan Description' name='subscrpldsc[]' id='location"+i+"'/></td></tr>";
        $('#plandesc').append(data);
        i++;
        // document.getElementById("count_items").value = counter+1;
        // numval++;
    });




    $("#delete").on('click', function() {
        // var counts = $("#count_items").val();
        // var counter = parseInt(counts-1);
        $('.case:checkbox:checked').parents("tr").remove();
        $('.check_all').prop("checked", false); 
        // document.getElementById("count_items").value = counter;
        check();
    
    });
    
    
    
        $("#deleteplans").on('click', function() {
            // var counts = $("#count_items").val();
            // var counter = parseInt(counts-1);
            $('.case:checkbox:checked').parents("tr").remove();
            $('.check_all').prop("checked", false); 
            // document.getElementById("count_items").value = counter;
            check();
        
        });


// for services





    $("#addrow").on("click", function(e) {
        e.preventDefault();
        var count = $("#count_items").val();
        var counter = parseInt(count);
        var newRow = $("<div class='row' id='counting"+ counter +"'>");
        var cols = "";
        var oneplus = counter + 1;
        cols += '<div class="col-md-6">';
        cols += '<div class="form-group">';
        cols += '<input type="text" class="form-control" placeholder="Enter Location" name="example-text-input'+ counter +'"></div></div>';
        cols += '<div class="col-md-5">';
        cols += '<div class="form-group">';
        cols += '<input type="text" class="form-control" placeholder="Enter Location" name="example-text-input'+ oneplus +'" ></div></div>';
        cols += '<div class="col-md-1">';
        cols += '';
        cols +=  '<a href="#" id="'+counter+'" class="majora" ><input type="hidden" name="keepcount" id="keepcount" value='+counter+' /> <i  class="fa fa-trash"></i></a></div>';
        newRow.append(cols);
        $("#addanother").append(newRow);
        if(oneplus>=4){
        document.getElementById("count_items").value = oneplus+1;
        }
        else{
            document.getElementById("count_items").value = oneplus;
        }

    });

 

    $(document).on('click', '.majora', function(e){

        console.log(this.id);
              var count = $("#keepcount").val();
        var countval = parseInt(count);
     
      var holder =  document.getElementById("counting"+countval);
      holder.remove();
   

    });



}());

// multislect testing area

$(document).ready(function() {
    $('.category').select2({ width: '100%' });
$(function(e) {
    $('#example').DataTable({
      responsive: true,
      ordering: false
    });

    var table = $('#example1').DataTable(
	{
      responsive: true,
      ordering: false
    });
    
	$('#example2').DataTable(
	{
      responsive: true,
      ordering: false
    });
	
	$('#example3').DataTable(
	{
      responsive: true,
      ordering: false
    });
	
	$('#example4').DataTable(
	{
	responsive: true,
    ordering: false
	});

    $('#example5').DataTable(
        {
        responsive: true,
        ordering: false
        });
	
} );
});







function select_all() {
    $('input[class=case]:checkbox').each(function(){ 
        if($('input[class=check_all]:checkbox:checked').length == 0){ 
            $(this).prop("checked", false); 
        } else {
            $(this).prop("checked", true); 
        } 
    });
}



function check(){
	obj=$('table tr').find('span');
	$.each( obj, function( key, value ) {
	id=value.id;
	$('#'+id).html(key+1);
	});
	}




    function showPreview(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            var contain = document.getElementById("blah");
            preview.src = src;
            preview.style.display = "block";
            contain.style.display = "none";
          }
    }



    function showPreviewcategory(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var previewtwo = document.getElementById("file-ip-2-preview");
            var containtwo = document.getElementById("containtwo");
            previewtwo.src = src;
            previewtwo.style.display = "block";
            containtwo.style.display = "none";
          }
    }








(function() {
    //document.querySelector('.hideing').style.display = 'none';

    $("#selects").on("click", function(e) {
        var ele=document.getElementsByName('menuitem_id[]');  
        for(var i=0; i<ele.length; i++){  
            if(ele[i].type=='checkbox')  
                ele[i].checked=true;  
        }
    });
}());


(function() {
    //document.querySelector('.hideing').style.display = 'none';

    $("#deSelect").on("click", function(e) {
        var ele=document.getElementsByName('menuitem_id[]');  
        for(var i=0; i<ele.length; i++){  
            if(ele[i].type=='checkbox')  
                ele[i].checked=false;  
        }
    });
}());


(function() {
$("#deSelectstwo").on("click", function(e) {
    var bit = $("#max_i").val();
    for(var j=0; j<bit; j++){ 
    var ele=document.getElementsByName('add_permission['+j+']');  
    for(var i=0; i<ele.length; i++){  
        if(ele[i].type=='checkbox')  
            ele[i].checked=false;  
    }
    var ele=document.getElementsByName('edit_permission['+j+']');  
    for(var i=0; i<ele.length; i++){  
        if(ele[i].type=='checkbox')  
            ele[i].checked=false;  
    }
    var ele=document.getElementsByName('delete_permission['+j+']');  
    for(var i=0; i<ele.length; i++){  
        if(ele[i].type=='checkbox')  
            ele[i].checked=false;  
    }

}
});
}());




(function() {
    $("#selectstwo").on("click", function(e) {
        var bit = $("#max_i").val();
        for(var j=0; j<bit; j++){ 
        var ele=document.getElementsByName('add_permission['+j+']');  
        for(var i=0; i<ele.length; i++){  
            if(ele[i].type=='checkbox')  
                ele[i].checked=true;  
        }
        var ele=document.getElementsByName('edit_permission['+j+']');  
        for(var i=0; i<ele.length; i++){  
            if(ele[i].type=='checkbox')  
                ele[i].checked=true;  
        }
        var ele=document.getElementsByName('delete_permission['+j+']');  
        for(var i=0; i<ele.length; i++){  
            if(ele[i].type=='checkbox')  
                ele[i].checked=true;  
        }
    
    }
    });
    }());




    (function() {
        $("#onlyadd").on("click", function(e) {
            var bit = $("#max_i").val();
            for(var j=0; j<bit; j++){ 
            var ele=document.getElementsByName('add_permission['+j+']');  
            for(var i=0; i<ele.length; i++){  
                if(ele[i].type=='checkbox')  
                    ele[i].checked=true;  
            }
        }
        });
        }());


        
    (function() {
        $("#onlyedit").on("click", function(e) {
            var bit = $("#max_i").val();
            for(var j=0; j<bit; j++){ 
            var ele=document.getElementsByName('edit_permission['+j+']');  
            for(var i=0; i<ele.length; i++){  
                if(ele[i].type=='checkbox')  
                    ele[i].checked=true;  
            }
     
        
        }
        });
        }());




        
    (function() {
        $("#onlydelete").on("click", function(e) {
            var bit = $("#max_i").val();
            for(var j=0; j<bit; j++){ 
            var ele=document.getElementsByName('delete_permission['+j+']');  
            for(var i=0; i<ele.length; i++){  
                if(ele[i].type=='checkbox')  
                    ele[i].checked=true;  
            }
        
        }
        });
        }());


    //     (function() {
    //     $( "select[name='city']" ).change(function () {
    //         var stateID = $(this).val();
    //         if(stateID) {
        
        
    //             $.ajax({
    //                 url: '<?php echo base_url("Modules/Admin/Controllers/Addcompany/depedentselect"); ?>',
    //                 dataType: 'Json',
    //                 data: {'id':stateID},
    //                 success: function(data) {
    //                     $('select[name="city"]').empty();
    //                     $.each(data, function(key, value) {
    //                         $('select[name="city"]').append('<option value="'+ key +'">'+ value +'</option>');
    //                     });
    //                 }
    //             });
        
        
    //         }else{
    //             $('select[name="location"]').empty();
    //         }
    //     });
    // }());



    
   // City change
 