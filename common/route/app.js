angular.module("ui.tinymce",[]).value("uiTinymceConfig",{}).directive("uiTinymce",["$rootScope","$compile","$timeout","$window","$sce","uiTinymceConfig",function(a,b,c,d,e,f){f=f||{};var g="ui-tinymce";return f.baseUrl&&(tinymce.baseURL=f.baseUrl),{require:["ngModel","^?form"],priority:599,link:function(h,i,j,k){function l(a){a?(m(),o&&o.getBody().setAttribute("contenteditable",!1)):(m(),o&&!o.settings.readonly&&o.getDoc()&&o.getBody().setAttribute("contenteditable",!0))}function m(){o||(o=tinymce.get(j.id))}if(d.tinymce){var n,o,p=k[0],q=k[1]||null,r={debounce:!0},s=function(b){var c=b.getContent({format:r.format}).trim();c=e.trustAsHtml(c),p.$setViewValue(c),a.$$phase||h.$digest()};j.$set("id",g+"-"+(new Date).valueOf()),n={},angular.extend(n,h.$eval(j.uiTinymce));var t=function(a){var b;return function(d){c.cancel(b),b=c(function(){return function(a){a.isDirty()&&(a.save(),s(a))}(d)},a)}}(400),u={setup:function(b){b.on("init",function(){p.$render(),p.$setPristine(),p.$setUntouched(),q&&q.$setPristine()}),b.on("ExecCommand change NodeChange ObjectResized",function(){return r.debounce?void t(b):(b.save(),void s(b))}),b.on("blur",function(){i[0].blur(),p.$setTouched(),a.$$phase||h.$digest()}),b.on("remove",function(){i.remove()}),f.setup&&f.setup(b,{updateView:s}),n.setup&&n.setup(b,{updateView:s})},format:n.format||"html",selector:"#"+j.id};angular.extend(r,f,n,u),c(function(){r.baseURL&&(tinymce.baseURL=r.baseURL);var a=tinymce.init(r);a&&"function"==typeof a.then?a.then(function(){l(h.$eval(j.ngDisabled))}):l(h.$eval(j.ngDisabled))}),p.$formatters.unshift(function(a){return a?e.trustAsHtml(a):""}),p.$parsers.unshift(function(a){return a?e.getTrustedHtml(a):""}),p.$render=function(){m();var a=p.$viewValue?e.getTrustedHtml(p.$viewValue):"";o&&o.getDoc()&&(o.setContent(a),o.fire("change"))},j.$observe("disabled",l),h.$on("$tinymce:refresh",function(a,c){var d=j.id;if(angular.isUndefined(c)||c===d){var e=i.parent(),f=i.clone();f.removeAttr("id"),f.removeAttr("style"),f.removeAttr("aria-hidden"),tinymce.execCommand("mceRemoveEditor",!1,d),e.append(b(f)(h))}}),h.$on("$destroy",function(){m(),o&&(o.remove(),o=null)})}}}}]);

var gmmrApp = angular.module('gmmrApp', ['ui.router', 'ngIdle', 'ui.bootstrap', 'ui.tinymce', 'ng-toast']);

gmmrApp.config(function($stateProvider, $urlRouterProvider, IdleProvider, KeepaliveProvider, $locationProvider) {
    $locationProvider.hashPrefix('');

    IdleProvider.idle(60000);
    IdleProvider.timeout(5);
    KeepaliveProvider.interval(70000);

    $urlRouterProvider.otherwise('/dashboard');

    $stateProvider

    .state('gque_opd', {
        url: '/gque_opd'
        , templateUrl: 'pages/gque_opd.php'
        , controller: 'queCtrl'
    })

    .state('dashboard', {
        url: '/dashboard'
        , templateUrl: 'pages/dashboard.php'
        , controller: 'dashboardCtrl'
    })
});

//use in auto logout/idle
gmmrApp.run(['Idle', function(Idle) {
  Idle.watch();
}]);


gmmrApp.filter('beginning_data', function() {
    return function(input, begin) {
        if (input) {
            begin = +begin;
            return input.slice(begin);
        }
        return [];
    }
});
