//window.alert('hello from the file');

CRM.$(function($) {
 // $(document).on('crmLoad', function(e){console.log(e);
 $(document).on('crmLoad', function() {
    var $el = $('[data-crm-custom="Activity_Location:Geolocation"]');
    // If element exists but is blank, then...
    if ($el.length && !$el.val()) {
      navigator.geolocation.getCurrentPosition(function(p){
        $el.val(p.coords.latitude + ',' + p.coords.longitude);
      });
    }
  });
});