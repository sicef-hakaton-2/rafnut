"use strict";

/*
  init function
  create an _R object before using any of the features;
    eg:
      var _R = new _R();
*/
function _R(){
  this.version =  "_R.js v0.1";
}

/*
  _R.log(data) is a fancy-pants version of the console.log command
    where:
      data => the data you wish to print
*/

_R.prototype.log = function(data){
  if (typeof(data) === 'object'){
    if (Array.isArray(data)){
      var i = 0;
      for(i; i<data.length; i++){
        console.log("Array["+i.toString()+"] => " + data[i]);
      }
    }
    else{
      var i = 0;
      $.each(data, function(key, value) {
        console.log("Object ["+i.toString()+"]: ", key, value);
        i++;
      });
    }
  }
  else{
    console.log(data);
  }
  return;
}

/*
  _R.addToast(toastContent, toastType) creates a toast in the upper-right corner
    where:
      toastContent => html content to place inside of the toast
      toastType => the type of the toast itself
        values:
          1: green success toast
          -1: red error toast
          0: yellow warning toast

  Required css class: .r-toast
*/

_R.prototype.addToast = function(toastContent, toastType){
  var toastColor = "";
  switch(toastType) {
    case 1:
      toastColor = "rgba(46, 204, 113, 0.8)";
      break;
    case -1:
      toastColor = "rgba(231, 76, 60, 0.8)"
      break;
    case 0:
      toastColor = "rgba(241, 196, 15, 0.8)";
      break;
  }

  $('<div/>').addClass("r-toast").prependTo($('body')).html(toastContent)
        .queue(function(next) {
            $(this).css({
                'opacity': 1,
                'background': toastColor
            });
            var topOffset = 15;
            $('.r-toast').each(function() {
                var $this = $(this);
                var height = $this.outerHeight();
                var offset = 15;

                $this.css('top', topOffset + 'px');

                topOffset += height + offset;
            });
            next();
        })
        .delay(3000)
        .queue(function(next) {
            var $this = $(this);
            var width = $this.outerWidth() + 20;
            $this.css({
                'right': '-' + width + 'px',
                'opacity': 0
            });
            next();
        })
        .delay(600)
        .queue(function(next) {
            $(this).remove();
            next();
        });

}

/*
  _R.addLoading(element) hides the element (typically the body or a very large block) and shows a fancy-pants loader
  _R.removeLoading(element) removed the loader and shows the element again

  Required css class: .r-loading, .r-loaded
  Required html elements in the body: <div class="r-dots-loader"></div>

*/

_R.prototype.addLoading = function(element) {
  $(element).addClass("r-loading");
};

_R.prototype.removeLoading = function(element) {
  $(element).removeClass("r-loading").addClass('r-loaded');
}

/*
  _R.sendPOST(apiEndpoint, data, successFunction, errorFunction) issues a POST request and binds callback functions
    where:
      apiEndpoint => the url of the POST request endpoint
      data => JSON strucuture
      successFunction => success function to bind
      errorFunction => errorFunction to bind

      Just use _R.log if you don't have a dedicated error/success handler :)
*/

_R.prototype.sendPOST = function(apiEndpoint, data, successFunction, errorFunction){
  $.ajax({
    type: "POST",
    url: apiEndpoint,
    data: data,
    success: function(data) {
      successFunction(data);
    },
    error: function(data){
      errorFunction(data);
    }
  });
}


_R.prototype.sendGET = function(apiEndpoint, successFunction, errorFunction){
  $.ajax({
    type: "GET",
    url: apiEndpoint,
    success: function(data) {
      successFunction(data);
    },
    error: function(data){
      errorFunction(data);
    }
  });
}
