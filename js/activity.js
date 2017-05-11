// window.alert('hello from the file');

CRM.$(function($) {
  $(document).on('crmLoad', function(){
    var $el = $('[data-crm-custom="Security_Rating:Location"]');
    // If element exists but is blank, then...
    if ($el.length && !$el.val()) {
      navigator.geolocation.getCurrentPosition(function(p){
        $el.val(p.coords.latitude + ',' + p.coords.longitude);
      });
    }
  });
});