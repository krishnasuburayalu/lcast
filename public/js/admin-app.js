var RES_SIZE = 12;
var RES_SKIP = 0;
var PAGE_SIZE = RES_SIZE;
var adhf = angular.module("adhf", ['autocomplete', 'ngRoute', 'ngAnimate', 'ui.router', 'bw.paging']);
var list_load = false;
adhf.config(function($stateProvider, $urlRouterProvider) {
    $stateProvider.
    state('home', {
        url: '/home',
        views: {
            "content@": {
                templateUrl: "/partials/admin-dashboard.html"
            },
            "sidebar@": {
                templateUrl: "/partials/admin-sidebar.html"
            },
        },
        controller: 'HomeCtrl'
    }).state('type', {
        url: '/type/:t_name',
        views: {
            "content@": {
                templateUrl: "partials/admin-type-dashboard.html"
            },
            "sidebar@": {
                templateUrl: "partials/admin-sidebar.html"
            },
        },
        //template: LIST_TPL,
        controller: 'SearchCtrl'
    });
     $urlRouterProvider.otherwise('home');
});
adhf.filter('arrayToString', function() {
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
adhf.filter('arrayToCommaSeprated', function() {
    return function(input) {
        if (input != undefined) return input.join(', ');
    }
});
adhf.filter('filterBIDString', function() {
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
adhf.controller("HomeCtrl", function($scope, $http, $stateParams, $location) {
    $scope.factreq = [];
    $scope.aggregations = [];
    $scope.factreq.fcfields = 'type,omt2,omt1,network,specialties,group_name,gender,language,county,state';
    $http.get('/profile/facets', {
        "params": $scope.factreq
    }).
    success(function(data1, status) {
        $scope.error = data1.error;
        if (!$scope.error) { // with results
            $scope.aggregationstotal = parseInt(data1.response.total);
            $scope.aggregations['type'] = data1.response.type.terms || [];
            $scope.aggregations['omt2'] = data1.response.omt2.terms || [];
            $scope.aggregations['network'] = data1.response.network.terms || [];
            $scope.aggregations['specialties'] = data1.response.specialties.terms || [];
            $scope.aggregations['gender'] = data1.response.gender.terms || [];
            $scope.aggregations['group_name'] = data1.response.group_name.terms || [];
            $scope.aggregations['language'] = data1.response.language.terms || [];
            $scope.aggregations['county'] = data1.response.county.terms || [];
            $scope.aggregations['omt1'] = data1.response.omt1.terms || [];
            $scope.aggregations['state'] = data1.response.state.terms || [];
            $scope.genderTotal = data1.response.gender.total || 0;
            buildbarchart($scope.aggregations['network'], '#barChart');
            buildbarchart($scope.aggregations['specialties'], '#barChartspecialties');
            buildbarchart($scope.aggregations['language'], '#barChartLanguages');
            buildbarchart($scope.aggregations['county'], '#barChartCounty');
            buildpieChart($scope.aggregations['type'], '#pieChart');
            buildpieChart($scope.aggregations['state'], '#pieChartState');
        }
    }).error(function(data1, status) {
        $scope.aggregations = [];
    });
});
var buildpieChart = function(data, ele) {
    var src_data = [];
    src_data['labels'] = [];
    src_data['data'] = [];
    var PieData = [];
    for (var i = 0, len = data.length; i < len; i++) {
        if(data[i]['term'] == 'D') {data[i]['term'] = 'Doctor'}
        if(data[i]['term'] == 'S') {data[i]['term'] = 'Ancillaries'}
        if(data[i]['term'] == 'H') {data[i]['term'] = 'Hospitals'}
        var clr = getRandomColor();
        PieData.push({'value': data[i]['count'], 'label': data[i]['term'],   color: clr , highlight: clr});
    }
    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $(ele).get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var pieOptions = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",
        //Number - The width of each segment stroke
        segmentStrokeWidth: 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: "easeOutBounce",
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,
        // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
}

var  getRandomColor = function() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}


var buildbarchart = function(data, ele) {
    var src_data = [];
    src_data['labels'] = [];
    src_data['data'] = [];
    for (var i = 0, len = data.length; i < len; i++) {
        src_data.labels.push(data[i]['term']);
        src_data.data.push(data[i]['count']);
    }
    var areaChartData = {
        labels: src_data.labels,
        datasets: [{
            label: "Digital Goods",
            fillColor: "#00c0ef",
            strokeColor: "#00c0ef",
            pointColor: "#3c8dbc",
            pointStrokeColor: "rgba(60,141,188,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(60,141,188,1)",
            data: src_data.data
        }]
    };
    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $(ele).get(0).getContext("2d");
    var barChart = new Chart(barChartCanvas);
    var barChartData = areaChartData;
    var barChartOptions = {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,
        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - If there is a stroke on each bar
        barShowStroke: true,
        //Number - Pixel width of the bar stroke
        barStrokeWidth: 2,
        //Number - Spacing between each of the X value sets
        barValueSpacing: 5,
        //Number - Spacing between data sets within X values
        barDatasetSpacing: 1,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        //Boolean - whether to make the chart responsive
        responsive: true,
        maintainAspectRatio: true
    };
    barChartOptions.datasetFill = true;
    barChart.Bar(barChartData, barChartOptions);
}
adhf.animation('.reveal-animation', function() {
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
adhf.directive('akModal', function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            scope.$watch(attrs.akModal, function(value) {
                element.modal({
                    backdrop: 'static',
                    keyboard: false
                });
                if (value) {
                    element.modal('show');
                } else {
                    element.modal('hide');
                }
            });
        }
    };
});
adhf.directive('loading', function() {
        return {
            restrict: 'E',
            replace: true,
            template: '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>',
            link: function(scope, element, attr) {
                scope.$watch('loading', function(val) {
                    if (val) $(element).show();
                    else $(element).hide();
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