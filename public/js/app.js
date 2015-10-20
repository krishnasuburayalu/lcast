var RES_SIZE = 12;
var RES_SKIP = 0;
var PAGE_SIZE = RES_SIZE;
var PLANS = [{
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
var LIST_TPL = ' <div class="col-md-3">              <div id="accordion" class="box-group">                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->                     <div class="panel box box-default box-solid">                      <div class="box-header with-border">                        <h4 class="box-title">                          <a   data-parent="#accordion" data-toggle="collapse" class="" aria-expanded="true">                          Refine by                          </a>                        </h4>                      </div>                      <div class="panel-collapse collapse in" id="collapseTwo" aria-expanded="true" style="">                        <div class="box-body">                        <h4 class="box-title">Network</h4>                           <ul class="list-unstyled" >                        <li class="text-light-blue" ng-repeat="agr in aggregations.network">                         <input type="checkbox" checklist-model="req.network" />  {{agr.term}}  <a class="pull-right badge bg-light-blue">{{agr.count}}</a>                       </li>                     </ul>                   <hr/>                   <h4 class="box-title">Specialty</h4>                           <ul class="list-unstyled" >                        <li class="text-light-blue" ng-repeat="spc in aggregations.specialties">                         <input type="checkbox" checklist-model="req.specialties" />  {{spc.term}}  <a class="pull-right badge bg-light-blue">{{spc.count}}</a>                       </li>                     </ul>                   <hr/>                        <h4 class="box-title">Language</h4>                        <ul class="list-unstyled" >                       <li class="text-light-blue" ng-repeat="lan in aggregations.language">                         <input type="checkbox" checklist-model="req.language" /> {{lan.term}}  <a class="pull-right  badge bg-light-blue">{{lan.count}}</a>                       </li>                     </ul>                      <hr/>                      <h4 class="box-title">Group</h4>                           <ul class="list-unstyled" >                        <li class="text-light-blue" ng-repeat="agr in aggregations.group_name">                        <input type="checkbox" checklist-model="req.group_name" /> {{agr.term}}  <a class="pull-right badge bg-light-blue">{{agr.count}}</a>                       </li>                     </ul>                     <hr/>                      <h4 class="box-title">City</h4>                           <ul class="list-unstyled" >                        <li class="text-light-blue" ng-repeat="agr in aggregations.city">                         <input type="checkbox" checklist-model="req.city" /> {{agr.term}}  <a class="pull-right badge bg-light-blue">{{agr.count}}</a>                        </li>                     </ul>                        <hr/>                       <h4 class="box-title">County</h4>                           <ul class="list-unstyled" >                        <li class="text-light-blue" ng-repeat="agr in aggregations.county">                          <input type="checkbox" checklist-model="req.county" />  {{agr.term}} <a class="pull-right badge bg-light-blue">{{agr.count}}</a>                       </li>                     </ul>                        </div>                      </div>                    </div>                    <div class="panel box box-default box-solid">                      <div class="box-header with-border">                        <h4 class="box-title">                          <a data-parent="#accordion" data-toggle="collapse" aria-expanded="true" class="">                            Advance Filters                          </a>                        </h4>                      </div>                      <div class="panel-collapse collapse in" id="collapseOne" aria-expanded="true" style="">                        <div class="box-body">                        <div class="form-group">                    <label>Plans</label>                           <select class="form-control select2" style="width: 100%;" ng-model="req.network" ng-options="plan.val as plan.name for plan in plans">                    </select>                    </div>                         <div class="form-group">                    <label>Distance</label>                    <ul class="nav nav-pills nav-stacked">                    <li ng-repeat="dist in distsnce">                      <input type="radio" ng-model="req.radius" value="{{dist.val}}">                      {{dist.name}}</li>                    </ul>                    </div>                     <div class="form-group">                    <label>Gender</label>                       <ul class="nav nav-pills nav-stacked">                    <li ng-repeat="gen in gender">                        <input type="radio" ng-model="req.male" value="{{gen.val}}">                      {{gen.name}}</li>                    </ul>                    </div>                     <div class="form-group">                    <label>Designations</label>                       <ul class="nav nav-pills nav-stacked">                    <li><input type="checkbox" checklist-model="req.newpatent" > Accepting New Patients</li>                    <li><input type="checkbox" checklist-model="req.newpatent" > Doctors in Patient-Centered Programs </li>                    <li><input type="checkbox" checklist-model="req.newpatent" >Language Spoken</li>                  </ul>                    </div>                        </div>                      </div>                    </div>                    <div class="panel box box-default box-solid">                      <div class="box-header with-border">                        <h4 class="box-title">                          <a   data-parent="#accordion" data-toggle="collapse" class="collapsed" aria-expanded="false">                             Filter by Language                          </a>                        </h4>                      </div>                      <div class="panel-collapse collapse" id="collapseThree" aria-expanded="false" style="height: 0px;">                        <div class="box-body">                         <autocomplete  ng-model="req.language"  data="language"   attr-input-class="form-control my-colorpicker1 colorpicker-element"></autocomplete>                        <!--ul class="nav nav-pills nav-stacked">                          <li ng-repeat="lan in language">                          <input type="checkbox" checklist-model="req.language" checklist-value="lan.val"> {{lan.name}}</li>                          </ul-->                        </div>                      </div>                    </div>                  </div>            </div><div class="col-md-9" ng-if="total == 0"> <h3> Your search  did not match any documents.</h3> <H5>Did you mean? </h5>    <ul class="list-unstyled">                    <li ng-repeat="suggest in suggestion">                        <a href="#"><b>{{suggest.text}}</b></a></li>                    </ul>            </div><div class="col-md-9" ng-if="total != 0"><div class="row"> <div class="col-md-6"><ul class="pagination pagination-sm inline pull-left"><li><a href="">Showing  {{stNum}} - {{endNum}} of {{ total }}  In-Network Doctors</a></li><li> <a href="#/compare/{{selectionBID | arrayToString}}"  clas="bg-light-blue-active" ><i class="fa fa-files-o"></i> Compare {{selectionBID.size()}} Doctors</a></li></ul></div> <div class="col-md-6"><ul class="pagination pagination-sm inline pull-right"><li ng-repeat="i in Range(stPGNum, endPGNum)"><a href="#/search/{{i}}">{{i}}</a></li></ul></div></div> <div class="row">   <div class="col-md-4" ng-repeat="provider in providers"><div class="box box-widget widget-user-2 box-solid"><!-- Add the bg color to the header using any of the bg-* classes --><div class="widget-user-header dhf-widget-user-header bg-gray">  <div class="widget-user-image" ng-switch="provider._source.male"><img ng-switch-when="true" alt="User Avatar" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/male.jpg" class="img-circle"/> <img ng-switch-when="false" alt="User Avatar" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/female.jpg" class="img-circle"/>  </div><!-- /.widget-user-image -->  <h4 class="fixed-username dhf-user"><a href="#profile/{{page}}/{{ provider._source.bid }}">{{ provider._source.name }} {{ provider._source.degree }}</a></h4> <p class="widget-user-desc">{{ provider._source.specialties.join(", ")}}</p><p class="widget-user-desc"> <img class="pull-right" ng-if="provider._source.omt1" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/tier-one.png" typeof="foaf:Image"/> <img class="pull-right" ng-if="provider._source.omt2" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/tiertwo.png" typeof="foaf:Image"/></p></div><div class="box-body">  <strong>  <i class="fa fa-phone margin-r-5"></i>Phone</strong><p class="">{{ provider._source.phone }}</p> <strong><i class="fa fa-map-marker margin-r-5"></i>Location</strong><p class=" dhf-addrs">{{ provider._source.address1 }}, {{ provider._source.city }}, {{ provider._source.state }} - {{ provider._source.zip }}-{{ provider._source.zip4 }}</p> <strong><i class="fa fa-car margin-r-5"></i>Distance</strong><p class="">1.9 Miles Away</p><p><input id="{{ provider._source.bid}}" class="" type="checkbox" value="{{provider._source.bid}}" ng-checked="selection.indexOf(provider._source.bid) > -1" ng-click="toggleSelection(provider._source.bid)" /> Add to Compare</p> </div> <div class="box-footer"> <div class="form-group"> <a class="small-box-footer btn bg-light-blue-active btn-flat pull-right btn-block" href="#profile/{{page}}/{{ provider._source.bid}}">  View Profile <i class="fa fa-arrow-circle-right"></i></a></div></div></div></div><!-- /.box --></div><div class="row">    <div class="col-md-12"><ul class="pagination pagination-sm inline pull-right"><li ng-repeat="i in Range(stPGNum, endPGNum)"><a href="#/search/{{i}}">{{i}}</a></li></ul></div></div> </div>  </div>';
var PROFILE_TPL = '   <div class="col-md-12">  <p><a  href="#/search/{{page}}"  class="btn bg-light-blue-active btn-flat margin" ><i class="fa fa-arrow-circle-left"></i> Back to Search result</a></p> </div>             <div class="col-md-9">               <div class="box box-widget widget-user">                <!-- Add the bg color to the header using any of the bg-* classes -->                <div class="widget-user-header bg-light-blue-active">                  <h3 class="widget-user-username col-md-8">{{details.lastname}}, {{details.firstname}}, {{details.degree}}</h3>                  <p class="pull-right">                  <img alt="" ng-if="details.omt1" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/tier-one.png" typeof="foaf:Image"/>                  <img alt="" ng-if="details.omt2" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/tiertwo.png" typeof="foaf:Image"/>                  </p>                  <h5 class="widget-user-desc col-md-6">{{details.specialties.join(", ")}}</h5>                </div>               <div class="widget-user-image" ng-switch="details.male"><img ng-switch-when="true" alt="User Avatar" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/male.jpg" class="img-circle"/> <img ng-switch-when="false" alt="User Avatar" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/female.jpg" class="img-circle"/>  </div>                <div class="box-footer">                  <div class="row">                    <div class="col-sm-6 border-right">                      <div class="description-block">                        <h5 class="description-header"><i class="fa fa-map-marker margin-r-5"></i>{{details.address1}}</h5>                        <span class="description-text">{{details.city}},{{details.state}}, {{details.zip}} -{{details.zip4}} -{{details.county}}  </span>                      </div><!-- /.description-block -->                    </div><!-- /.col -->                    <div class="col-sm-2">                      <div class="description-block">                        <h5 class="description-header"> <i class="fa fa-phone margin-r-5"></i>Phone</h5>                        <span class="description-text">{{details.phone}}</span>                      </div><!-- /.description-block -->                    </div><!-- /.col -->                     <div class="col-sm-2">                      <div class="description-block">                        <h5 class="description-header">FAX</h5>                        <span class="description-text">{{details.fax}}</span>                      </div><!-- /.description-block -->                    </div><!-- /.col -->                     <div class="col-sm-2">                      <div class="description-block">                      <img alt="" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/tier-one.png" typeof="foaf:Image" ng-if="checked"/>                      </div><!-- /.description-block -->                    </div><!-- /.col -->                    </div><!-- /.col -->                  </div><!-- /.row -->                </div>                </div>                 <div class="col-md-3 pull-right">             <!-- Profile Image -->              <div class="box">              <div class="box-header bg-light-blue-active with-border">                  <h3 class="box-title">Plans Accepted</h3>                </div><!-- /.box-header -->                <div class="box-body  box-profile">                 <div class="col-md-12" ng-repeat="plan in plans"><i class="icon fa fa-check text-green"></i> {{plan.name}}</div>                </div><!-- /.box-body -->              </div><!-- /.box -->            </div>                <div class="col-md-9">             <!-- Profile Image -->              <div class="box">                <div class="box-header bg-light-blue-active with-border">                  <h3 class="box-title">Doctor Details</h3>                </div><!-- /.box-header -->                <div class="box-body">                  <div class="row">                    <div class="col-sm-6 border-right">                  <strong>          <i class="fa  fa-genderless margin-r-5"></i>          Gender          </strong>          <p class="text-muted" ng-if="details.male">Male</p>          <p class="text-muted" ng-if="details.female">Female</p>          <hr>           <strong>          <i class="fa fa-language margin-r-5"></i>          Spoken Languages          </strong>          <p class="text-muted">{{details.language}}</p>          <hr>           <strong>          <i class="fa fa-hospital-o margin-r-5"></i>          Location Code           </strong>          <p class="text-muted">{{details.offcode}}</p>          <hr>           <strong>          <i class="fa  fa-medkit margin-r-5"></i>          National Provider Identifier          </strong>          <p class="text-muted">{{details.nationalproviderid}}</p>          <hr>          <strong>          <i class="fa  fa-stethoscope margin-r-5"></i>          Board Certified          </strong>          <p class="text-muted">{{details.board_certified}}</p>          </div>          <div class="col-sm-6">          <strong>          <i class="fa  fa-university margin-r-5"></i>          Education          </strong>          <p class="text-muted">{{details.degree}}</p>          <hr>          <strong>          <i class="fa  fa-group margin-r-5"></i>          Group Affiliation          </strong>          <p class="text-muted">{{details.group_name}}</p>          <hr>          <strong>          <i class="fa fa-sticky-note-o margin-r-5"></i>          Notes          </strong>          <p class="text-muted">{{details.note}}</p>          <hr>          <strong>          <i class="fa  fa-plus-square margin-r-5"></i>          Practice Status          </strong>          <p class="text-muted">{{details.acpt_new_pat}}</p>          </div>                </div><!-- /.box-body -->              </div>            </div>                         </div>                                        <div class="col-md-9">             <div class="box">              <div class="box-body box-profile">             <div id="gmaps"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p> </div>             </div>             </div>      </div>';
var COMPARE_TPL = '<div class="col-md-12">     <div class="box">                <div class="box-header">                <div class="col-md-6">                  <h3 class="box-title">Compare {{comparTotal}} providers</h3>                  </div>                  <div class="col-md-6"><a  href="#/search/{{page}}"  class="small-box-footer btn bg-light-blue-active btn-flat pull-right" ><i class="fa fa-arrow-circle-left"></i> &nbsp;&nbsp;&nbsp;&nbsp;Back to Search result</a></div>                </div><!-- /.box-header -->                <div class="box-body table-responsive no-padding">                  <table class="table table-hover table-bordered table-striped">                    <tbody>                    <tr >                      <th> </th>                      <td ng-repeat="provider in providers">                        <div  ng-switch="provider._source.male"><img ng-switch-when="true" alt="User Avatar" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/male.jpg" class="profile-user-img img-responsive img-circle"/> <img ng-switch-when="false" alt="User Avatar" src="http://doctorfinder.horizonblue.com/sites/all/modules/custom/opd/images/female.jpg" class="profile-user-img img-responsive img-circle"/>  </div>              <h4 class=" text-center"><a href="#/profile/{{page}}/{{ provider._source.bid }}"><strong>{{ provider._source.lastname }}</strong>, {{ provider._source.firstname }} {{ provider._source.mi }} </a></h4>                      </td>                    </tr>                    <tr>                      <th>Spacilty</th>                      <td ng-repeat="provider in providers">{{ provider._source.specialties.join(", ")}}</td>                    </tr>                     <tr>                      <th>Gender</th>                      <td ng-repeat="provider in providers">                      <p class="text-muted" ng-if="provider._source.male">Male</p>          <p class="text-muted" ng-if="provider._source.female">Female</p></td>                    </tr> <tr>                      <th>Spoken Languages</th>                      <td ng-repeat="provider in providers">{{provider._source.language}}</td>                    </tr> <tr>                      <th>Location Code </th>                      <td ng-repeat="provider in providers">{{provider._source.offcode}}</td>                    </tr> <tr>                      <th>National Provider Identifier</th>                      <td ng-repeat="provider in providers">{{provider._source.nationalproviderid}}</td>                    </tr> <tr>                      <th>Board Certified</th>                      <td ng-repeat="provider in providers">{{provider._source.board_certified}}</td>                    </tr> <tr>                      <th>Education</th>                      <td ng-repeat="provider in providers">{{provider._source.degree}}</td>                    </tr> <tr>                      <th>Group Affiliation</th>                      <td ng-repeat="provider in providers">{{provider._source.group_name}}</td>                    </tr> <tr>                      <th>Notes</th>                      <td ng-repeat="provider in providers">{{provider._source.note}}</td>                    </tr>                     <tr>                      <th>Practice Status</th>                      <td ng-repeat="provider in providers">{{provider._source.acpt_new_pat}}</td>                    </tr>                      <tr>                      <th></th>                      <td ng-repeat="provider in providers"><a  href="#/profile/{{page}}/{{ provider._source.bid }}"  class="small-box-footer btn bg-light-blue-active btn-flat pull-right btn-block" ><i class="fa fa-user-md"></i> &nbsp;&nbsp;View Profile</a></td>                    </tr>                  </tbody></table>                </div><!-- /.box-body -->              </div>                </div>';
var dhf = angular.module("dhf", ['autocomplete', 'ngRoute', 'ngAnimate', 'ui.router','bw.paging']);
var list_load = false;
/*
dhf.config(['$routeProvider', '$locationProvider',
  function($routeProvider, $locationProvider) {
    $routeProvider
      .when('/search', {
        templateUrl: 'list.tpl.html',
        controller: 'SearchCtrl',
        controllerAs: 'search'
      }).when('/search/:page', {
        templateUrl: '/partials/list.tpl.html',
        controller: 'SearchCtrl',
        controllerAs: 'search'
      })
      .when('/profile/:page/:id', {
        templateUrl: '/partials/profile.html',
        controller: 'defaultCtrl',
        controllerAs: 'profile'
      });
    $locationProvider.html5Mode(true);
}]).controller('SearchCtrl', function($scope){
$scope.messages = "Test Test Test Test Test Test";
});
*/
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
        $scope.doSearch(1);
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
        $scope.req.specialties = null;
        if ($scope.location != undefined && $scope.location != '') {
            $scope.req.zip = $scope.location.split(' - ')[1];
        }
        if ($scope.name != undefined && $scope.name != '') {
            $scope.req.name_raw = $scope.name;
        }
        if ($scope.specialty_sel != undefined && $scope.specialty_sel != '') {
            $scope.req.specialties = $scope.specialty_sel;
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
            if ($scope.total <= 0 && $scope.name != '') {
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
        if (typed == undefined || typed.length < 3) {
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
    // gives another movie array on change
    $scope.updateName = function(typed) {
        if (typed == undefined || typed.length < 3) {
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
        if (typed == undefined || typed.length < 3) {
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

     $scope.selectionBID = [];
    // toggle selectionBID for a given Provider
    $scope.toggleSelection = function toggleSelection(bid) {
        var idx = $scope.selectionBID.indexOf(bid);
        // is currently selected
        if (idx > -1) {
            $scope.selectionBID.splice(idx, 1);
        }
        // is newly selected
        else {
            $scope.selectionBID.push(bid);
        }
    };

    $scope.compareProfile = function(ids) {
        $scope.profileShow = 'show';
        if (ids == undefined || ids == '') {
            return false;
        }
        id = ids.join(',');
        url = '/profile/search?bid=' +  id;
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