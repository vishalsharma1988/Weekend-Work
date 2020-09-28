jQuery(document).ready( function() {
   jQuery("#export-button").click( function(e) {
   // console.log('asdfasdf');
      e.preventDefault(); 
      jQuery.ajax({
         type : "post",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : {action: "export_all_posts",my_val : "1"},
         success: function(response) {
         //	console.log( response );
          }
      });
   });
});