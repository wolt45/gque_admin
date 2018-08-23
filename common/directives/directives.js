gmmrApp.directive('scrollToBookmark', function() {
    return {
      link: function(scope, element, attrs) {
        var value = attrs.scrollToBookmark;
        element.click(function() {
          scope.$apply(function() {
            var selector = "[scroll-bookmark='"+ value +"']";
            var element = $(selector);
            if(element.length)
              window.scrollTo(0, element[0].offsetTop - 10);  // Don't want the top to be the exact element, -100 will go to the top for a little bit more
          });
        });
      }
    };
});

gmmrApp.directive('dateInput', function(){
    return {
        require: 'ngModel',
        link: function(scope, elem, attr, modelCtrl) {
            modelCtrl.$formatters.push(function(modelValue) {
                if (modelValue){
                    return new Date(modelValue);
                }
                else {
                    return null;
                }
            });
        }
    };
});

