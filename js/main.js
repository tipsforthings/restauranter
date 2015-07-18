jQuery(document).ready(
  
  /* This is the function that will get executed after the DOM is fully loaded */
  function () {
    jQuery( "input.date" ).datepicker({
      changeMonth: true,
      changeYear: true,
      maxDate: '+1y', 
      minDate: '0', 
    });
    jQuery('input.time').timepicker({
        'scrollDefault': 'now',
        'minTime': '11:30am',
        'maxTime': '11:30pm',
    });
  }


);
