var RES_SIZE = 12;
var RES_SKIP = 0;
var PAGE_SIZE = RES_SIZE;
var PLANS = [
{
    'val': 'OMT1,OMT2',
    'name': 'OMNIA'
}, {
    'val': 'OMT1,OMT2',
    'name': 'OMNIA Bronze'
}, {
    'val': 'OMT1,OMT2',
    'name': 'OMNIA Gold'
}, {
    'val': 'OMT1,OMT2',
    'name': 'OMNIA HSA'
}, {
    'val': 'OMT1,OMT2',
    'name': 'OMNIA Platinum'
}, {
    'val': 'OMT1,OMT2',
    'name': 'OMNIA Silver'
}, {
    'val': 'OMT1,OMT2',
    'name': 'OMNIA Silver HSA'
}, {
    'val': 'ADVN',
    'name': 'Horizon Advance EPO'
}, {
    'val': 'MGCN',
    'name': 'Horizon Advantage EPO'
}, {
    'val': 'MGCN',
    'name': 'Horizon Direct Access'
}, {
    'val': 'MGCN',
    'name': 'Horizon EPO/EPO PLUS'
}, {
    'val': 'MGCN',
    'name': 'Horizon HMO'
}, {
    'val': 'MCBL',
    'name': 'Horizon Medicare Advantage PPO'
}, {
    'val': 'MCBL',
    'name': 'Horizon Medicare Blue Choice'
}, {
    'val': 'MBPC',
    'name': 'Horizon Medicare Blue Patient Centered'
}, {
    'val': 'MCBL',
    'name': 'Horizon Medicare Blue value'
}, {
    'val': 'MGCN',
    'name': 'Horizon MyWay HRA Direct Access'
}, {
    'val': 'TRAD',
    'name': 'Horizon MyWay HRA PPO'
}, {
    'val': 'MGCN',
    'name': 'Horizon MyWay HSA Direct Access'
}, {
    'val': 'TRAD',
    'name': 'Horizon MyWay HSA PPO'
}, {
    'val': 'PCMH,ACO',
    'name': 'Horizon Patient-Centered Adv EPO'
}, {
    'val': 'MGCN',
    'name': 'Horizon POS'
}, {
    'val': 'TRAD',
    'name': 'Horizon PPO'
}, {
    'val': 'MGCN',
    'name': 'NJ DIRECT'
}];
var GENDER = [{
    'val': '1',
    'name': 'Male'
}, {
    'val': '0',
    'name': 'Female'
}]
var DISTANCE = [{
    'val': '5',
    'name': 'Within 5 Miles'
}, {
    'val': '15',
    'name': 'Within 15 Miles'
}, {
    'val': '25',
    'name': 'Within 25 Miles'
}, {
    'val': '50',
    'name': 'Within 50 Miles'
}]
var LANGUAGE = ['Arabic', 'Chinese', 'Filipino', 'French', 'German', 'Greek', 'Gujarati', 'Hebrew', 'Hindi', 'Italian', 'Japanese', 'Korean', 'Polish', 'Portuguese', 'Punjabi', 'Russian', 'Spanish', 'Tagalog', 'Urdu', 'Yiddish']
var first_req = true;
var DEFAULT_PARAM = {
    'size': RES_SIZE,
    'skip': RES_SKIP
};
 var dhf = angular.module("dhf", ['autocomplete', 'ngRoute', 'ngAnimate', 'ui.router','bw.paging']);
var list_load = false;
dhf.config(function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('search');
    $stateProvider.state('home', {
        url: '/',
        template: '<div class="col-md-12"><h1> Please enter criteria for at least one of the required fields. </h1></div>',
        controller: 'searchall'
    }).state('search', {
        url: '/search',
        views: {
            "content@": {
                templateUrl: "partials/list.tpl.html"
            },
            "sidebar@": {
                templateUrl: "partials/sidebar.html"
            },
        },
        //template: LIST_TPL,
        controller: 'SearchCtrl'
    }).state('searchall', {
        url: '/search/:page',
        views: {
            "content@": {
                templateUrl: "partials/list.tpl.html"
            },
             "sidebar@": {
                templateUrl: "partials/sidebar.html"
            },
        },
        //template: LIST_TPL,
        controller: 'SearchCtrl'
    }).state('compare', {
        url: '/compare/:ids',
        views: {
            "content@": {
                templateUrl: "partials/compare.html"
            }
        },
        controller: 'SearchCtrl'
    })

    /*.state('profile', {
        url: '/profile/:page/:id',
        controller: 'SearchCtrl',
        views: {
            "profile@": {
                templateUrl: "partials/profile.html"
            },
        }
        //template: PROFILE_TPL
    })*/
});
dhf.filter('arrayToString', function() {
    return function(input) {
        var newinput = [];
        // do some bounds checking here to ensure it has that index
        angular.forEach(input, function(value, key) {
            path = value.split('|');
            if (path[0] != undefined) {
                newinput.push(path[0]);
            }
        });
        return newinput.join(' ');
    }
});
dhf.filter('arrayToCommaSeprated', function() {
    return function(input) {
        if (input != undefined) return input.join(', ');
    }
});
dhf.filter('filterBIDString', function() {
    return function(input) {
        if (input != undefined) {
            path = input.split('|');
            // do some bounds checking here to ensure it has that index
            if (path[0] !== undefined) {
                return path[0];
            }
        }
        return '';
    }
});
dhf.controller("homeCtrl", function($scope, $http, $stateParams) {
    $scope.req = DEFAULT_PARAM;
});
dhf.controller("SearchCtrl", function($scope, $http, $stateParams, $location) {
    /*Defult values*/
    $scope.listload = true;
    $scope.firstload = true;
    $scope.req = [];
    $scope.aggregations = [];
    $scope.suggestion = [];
    $scope.details = [];
    $scope.plans = PLANS;
    $scope.distsnce = DISTANCE;
    $scope.language = LANGUAGE;
    $scope.gender = GENDER;
    $scope.total = 0;
    $scope.page = 1;
    $scope.showModal = false;
    $scope.compareModal = false;
    $scope.selectionBID = [];
    $scope.pagination = {
        'total': 0,
        'current_page': 1,
        'total_pages': 1
    };
    $scope.providers = [];

    var path = $location.path().split('/');
    if (path[1] !== undefined) {
        $scope.param1 = path[1];
    }
    if (path[2] !== undefined) {
        $scope.param2 = path[2];
    }
    $scope.clearSearch = function() {
        $scope.req=[];
        $scope.location= null;
        $scope.name = null;
        $scope.specialty_sel = null;
        $scope.group_sel = null;
        $scope.doSearch(1);
    }

$scope.getBoxClass = function(type) {
    if(type == undefined){
         return 'box-primary';
    }
         switch(type){
            case 'S':
            return 'box-danger';
            break;
             case 'H':
            return 'box-warning ';
            break;
             case 'D':
            return 'box-primary';
            break;
         }
    }

    $scope.getBtnClass = function(type) {
    if(type == undefined){
         return 'btn-primary';
    }
         switch(type){
            case 'S':
            return 'btn-danger';
            break;
             case 'H':
            return 'btn-warning ';
            break;
             case 'D':
            return 'btn-primary';
            break;
         }
    }
    /*Path parts from*/
    $scope.doSearch = function(page) {
        list_load = true;
        $scope.firstload = false;
        var path = $location.path().split('/');
        if (path[1] !== undefined) {
            $scope.current_path = path[1];
        }
        if (path[2] !== undefined) {
            $scope.page = path[2];
        } else {
            $scope.page = 1;
        }
        if (page != undefined) {
            $scope.page = parseInt(page);
        }

        $scope.req.skip = ($scope.page * RES_SIZE) - RES_SIZE;
        $scope.req.size = RES_SIZE;
        $scope.suggestion = [];
        $scope.req.name_raw = null;
        $scope.req.zip = null;
       // $scope.req.specialties = null;
        if ($scope.location != undefined && $scope.location != '') {
            $scope.req.zip = $scope.location.split(' - ')[1];
        }
        if ($scope.name != undefined && $scope.name != '') {
            $scope.req.name = $scope.name;
        }

        if ($scope.specialty_sel != undefined && $scope.specialty_sel != '') {
            $scope.req.specialties = $scope.specialty_sel;
        }

         if ($scope.group_sel != undefined && $scope.group_sel != '') {
            $scope.req.group_name = $scope.group_sel;
        }

        $scope.loading = true;
        $http.get('/profile/search', {
            "params": $scope.req
        }).
        success(function(data, status) {
            $scope.error = data.error;
            $scope.total = 0;
            if (!$scope.error) { // with results
                $scope.total = parseInt(data.response.total);
                $scope.providers = data.response.hits;
                $scope.getAggregations();
            }
            if ($scope.total <= 0 && $scope.name != undefined && $scope.name != '') {
                //get suggestion
                $http.get('profile/suggest?q=' + $scope.name).
                success(function(data2, status) {
                    $scope.error = data2.error;
                    if (!$scope.error) { // with results
                        $scope.suggestiontotal = parseInt(data2.response.size);
                        $scope.suggestion = data2.response || [];
                    }
                }).error(function(data2, status) {
                    $scope.suggestion = [];
                });
            }
            if ($scope.total <= 0 && $scope.specialty_sel != undefined && $scope.specialty_sel != '' && $scope.suggestion.length <=0 ) {
                //get suggestion
                $http.get('profile/suggest?field=specialties_suggest&q=' + $scope.specialty_sel).
                success(function(data2, status) {
                    $scope.error = data2.error;
                    if (!$scope.error) { // with results
                        $scope.suggestiontotal = parseInt(data2.response.size);
                        $scope.suggestion = data2.response || [];
                    }
                }).error(function(data2, status) {
                    $scope.suggestion = [];
                });
            }
            $scope.loading = false;
        }).error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.total = 0;
            $scope.status = status;
            $scope.loading = false;
        });
    };
    $scope.getAggregations = function() {
        $scope.aggregations = [];
        $scope.factreq = $scope.req;
        $scope.factreq.fcfields = 'language,county,network,specialties,gender,type';
        $http.get('/profile/facets', {
            "params": $scope.factreq
        }).
        success(function(data1, status) {
            $scope.error = data1.error;
            if (!$scope.error) { // with results
                $scope.aggregationstotal = parseInt(data1.response.total);
                $scope.aggregations['language'] = data1.response.language.terms || [];
                $scope.aggregations['county'] = data1.response.county.terms || [];
                $scope.aggregations['network'] = data1.response.network.terms || [];
                $scope.aggregations['specialties'] = data1.response.specialties.terms || [];
                $scope.aggregations['gender'] = data1.response.gender.terms || [];
            }
        }).error(function(data1, status) {
            $scope.aggregations = [];
        });
    };
    // gives another movie array on change
    $scope.updateLocation = function(typed) {
        if (typed == undefined ) {
            $scope.locations = [];
            return false;
        }
        $scope.locationurl = '/geo/search?q='; // The url of our search
        $http.get($scope.locationurl + '*' + typed + '*').
        success(function(data, status) {
            $scope.geoerror = data.error;
            if (!$scope.geoerror) { // with results
                $scope.geototal = data.response.total;
                $scope.locations = [];
                angular.forEach(data.response.hits, function(item) {
                    $scope.locations.push(item._source.city + ', ' + item._source.state + ' - ' + item._source.postal);
                });
            }
        }).
        error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.status = status;
            $scope.locations = [];
        });
    };

     $scope.updateGroup = function(typed) {
         if (typed == undefined) {
            $scope.names = [];
            return false;
        }
        $scope.nameurl = '/profile/search?fields=group_name&group_name_auto='; // The url of our search
        $http.get($scope.nameurl + '*' + typed +'*').
        success(function(data, status) {
            $scope.nameerror = data.error;
            if (!$scope.nameerror) { // with results
                $scope.groupstotal = data.response.total;
                $scope.groups = [];
                angular.forEach(data.response.hits, function(item) {
                    if ($scope.groups.indexOf(item._source.group_name) == -1) {
                        $scope.groups.push(item._source.group_name);
                    }
                });
            }
        }).
        error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.status = status;
            $scope.names = [];
        });
    };

    // gives another movie array on change
    $scope.updateName = function(typed) {
        if (typed == undefined) {
            $scope.names = [];
            return false;
        }
        $scope.nameurl = '/profile/search?fields=name&name_auto='; // The url of our search
        $http.get($scope.nameurl + typed).
        success(function(data, status) {
            $scope.nameerror = data.error;
            if (!$scope.nameerror) { // with results
                $scope.namestotal = data.response.total;
                $scope.names = [];
                angular.forEach(data.response.hits, function(item) {
                    if ($scope.names.indexOf(item._source.name) == -1) {
                        $scope.names.push(item._source.name);
                    }
                });
            }
        }).
        error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.status = status;
            $scope.names = [];
        });
    };
    $scope.updateSpecialty = function(typed) {
        if (typed == undefined) {
            $scope.names = [];
            return false;
        }
        $scope.nameurl = '/profile/search?fields=specialties&specialties_auto='; // The url of our search
        $http.get($scope.nameurl + typed).
        success(function(data, status) {
            $scope.nameerror = data.error;
            if (!$scope.nameerror) { // with results
                $scope.specialtiestotal = data.response.total;
                $scope.specialties = [];
                angular.forEach(data.response.hits, function(item) {
                    if ($scope.specialties.indexOf(item._source.specialties[0]) == -1) {
                        $scope.specialties.push(item._source.specialties[0]);
                    }
                });
            }
        }).
        error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.status = status;
            $scope.names = [];
        });
    };
    $scope.Range = function(start, end) {
        var result = [];
        for (var i = parseInt(start); i <= parseInt(end); i++) {
            result.push(i);
        }
        return result;
    };


    // toggle selectionBID for a given Provider
    $scope.toggleSelection = function(bid) {
        var idx = $scope.selectionBID.indexOf(bid);
        // is currently selected
        if (idx === -1) {
            $scope.selectionBID.push(bid);
        } else {
            $scope.selectionBID.splice(idx, 1);
        }
    };

    $scope.compareProfile = function() {
        $scope.profileShow = 'show';
        if ($scope.selectionBID == undefined || $scope.selectionBID.size == 0 ) {
            return false;
        }
        id = $scope.selectionBID.join(' ');
        url = '/profile/search?&fields=group_name,specialties,board_certified,name,state,omt2,omt1,address1,phone,fax,zip,zip4,type,city,degree,county,gender,language,bid&bid=' +  id;
        $http.get(url).
        success(function(data, status) {
            $scope.error = data.error;
            if (!$scope.error) { // with results
                $scope.comparTotal = data.response.total;
                $scope.compareProviders = data.response.hits;
                $scope.compareModal = true;
            }
        }).error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.status = status;
            $scope.error = true;
        });
    };

    $scope.showProfile = function(id) {
        if (id != undefined) {
            $scope.profile_id = id
        }
        $scope.showModal = true;
        $http.get('/profile/show/' + $scope.profile_id).
        success(function(data, status) {
            $scope.error = data.error;
            if (!$scope.error) { // with results
                $scope.details = data.response;
                 $scope.showModal = true;
            }
        }).error(function(data, status) {
            $scope.data = data || "Request failed";
            $scope.status = status;
            $scope.error = true;
            $scope.showModal = false;
        });
    };
    $scope.closeModule =  function(){
        $scope.showModal = false;
        $scope.compareModal = false;
    }
    if ($scope.firstload) {
        $scope.doSearch(1);
    }
});
dhf.animation('.reveal-animation', function() {
    return {
        enter: function(element, done) {
            element.css('display', 'none');
            element.fadeIn(500, done);
            return function() {
                element.stop();
            }
        },
        leave: function(element, done) {
            element.fadeOut(500, done)
            return function() {
                element.stop();
            }
        }
    }
})



dhf.directive('akModal', function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            scope.$watch(attrs.akModal, function(value) {
                element.modal({backdrop: 'static', keyboard: false});
                if (value){
                    element.modal('show');
                }
                else{
                    element.modal('hide');
                }
            });
        }
    };
});
dhf.directive('loading', function () {
      return {
        restrict: 'E',
        replace:true,
        template: '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>',
        link: function (scope, element, attr) {
              scope.$watch('loading', function (val) {
                  if (val)
                      $(element).show();
                  else
                      $(element).hide();
              });
        }
      }
  })

/**
 * Angular's private URL Builder method + unpublished dependencies converted to a public service
 * So we can properly build a GET url with parameters for a JSONP request.
 */
function encodeUriQuery(val, pctEncodeSpaces) {
    return encodeURIComponent(val).
    replace(/%40/gi, '@').
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, (pctEncodeSpaces ? '%20' : '+'));
}
/**
 * Angular's private buildUrl function, patched to refer to the public methods on the angular globals
 */
function buildUrl(url, params) {
    if (!params) return url;
    var parts = [];
    angular.forEach(params, function(value, key) {
        if (value === null || angular.isUndefined(value)) return;
        if (!angular.isArray(value)) value = [value];
        angular.forEach(value, function(v) {
            if (angular.isObject(v)) {
                v = angular.toJson(v);
            }
            parts.push(encodeUriQuery(key) + '=' + encodeUriQuery(v));
        });
    });
    return url + ((url.indexOf('?') == -1) ? '?' : '&') + parts.join('&');
}