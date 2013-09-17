$(document).ready(function() {
  $( "#Pages-Tabs" ).tabs();
  
  $('.TemplateForm').live('submit', function() {
    $.ajax({
      data: $(this).serialize(),
      type: 'POST',
      url: 'ajax/set_template.php',
      success: function(response){
        alert(response);
      }
    });
    return false;
  })
  
  $('.PagesForm').live('submit', function() {
    $.ajax({
      data: $(this).serialize(),
      type: 'POST',
      url: 'ajax/setpages.php',
      success: function(response){
        alert(response);
      }
    });
    return false;
  })
  
   $('.TickerTapeForm').live('submit', function() {
    $.ajax({
      data: $(this).serialize(),
      type: 'POST',
      url: 'ajax/ticker_tape.php',
      success: function(response){
        alert(response);
      }
    });
    return false;
  })
  
  $('.BlastForm').live('submit', function() {
    $.ajax({
      data: $(this).serialize(),
      type: 'POST',
      url: 'ajax/blast.php',
      success: function(response){
        alert(response);
      }
    });
    return false;
  })
  
  
});