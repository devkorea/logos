/// <reference path="angular-1.5.8/angular.js" />
/// <reference path="./example/plugins/angular-1.5.8/angular-route.js" />



// ngRoute vs ui-router (ui-router 를 이용한 코드)
var app = angular.module('Demo', ['ui.router']);
app.config(function($stateProvider, $urlMatcherFactoryProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/home');
    $urlMatcherFactoryProvider.caseInsensitive(true);
    $stateProvider
        .state('home', {
            url:'/home',
            templateUrl: 'skin/home.html',
            controller: 'homeController',
            controllerAs: 'homeCtrl', 
            data: {
                customData1: 'home Data 1',
                customData2: 'home Data 2',
            }
        })
        .state('lecture', {
            url:'/lecture',
            templateUrl: 'skin/lecture.html',
            controller: 'lectureController',
            controllerAs: 'lecCtrl', 
            data: {
                customData1: 'Lecture Data 1',
                customData2: 'Lecture Data 2',
            }
        })
        .state('studentParent', {
            url:'/student',
            controller: 'studentPController',
            controllerAs: 'studentPCtrl',
            templateUrl: 'skin/studentP.html',
            resolve: {
                studentTotals: function($http) {   
                    app.wrapBodyBack(true);                 
                    return $http({method:'GET', url:'./module/proc/data.html?mode=gender'}).then(function(response_) {
                        return response_.data;
                    }, function(reason_) {
                        return reason_.data;
                    });
                }
            },
            abstract: true
       })
        .state('studentParent.student', {
            url:'/',
            views: {
                'studentData': {
                    templateUrl: 'skin/student.html',
                    controller: 'studentController',
                    controllerAs: 'stdCtrl',
                    resolve: {
                        studentList: function($http) {   
                            app.wrapBodyBack(true);                 
                            return $http({method:'GET', url:'./module/proc/data.html?mode=student'}).then(function(response_) {
                                return response_.data;
                            }, function(reason_) {
                                return reason_.data;
                            });
                        }
                    }
                },
                'totalData' : {
                    controller: 'studentTotalController',
                    controllerAs: 'studentTotCtrl',
                    templateUrl: 'skin/studentTot.html',
                }
            },
        })
        .state('studentParent.studentD', {
            url:'/:id',
            views: {
                studentData: {
                    templateUrl: 'skin/studentD.html',
                    controller: 'studentDController',
                    controllerAs: 'detlCtrl'
                }                    
            }
        })
        // $locationProvider.html5Mode(true)  // 이 부분에 대한 오류를 지속적으로 찾아볼것, htaccess, rewrite 오류와 함께(2016.08.27)
});

app.wrapBodyBack = function(bShow) {
    if (bShow === true) {
        $('#wrapBody').oLoader({
            wholeWindow: false,
            lockOverflow: true,
            backgroundColor: '#000',
            fadeInTime: 0,
            fadeLevel: 0.7,
            image: './module/img/ajax-loader.gif',
        });
    } else {
        setTimeout(function() {    
            $('#wrapBody').oLoader('hide'); 
        }, 250);
    }
}

app.controller('homeController', function($state) {
    this.message = 'Welcome to main';
    this.homeCustomData1 = $state.current.data.customData1;
    this.homeCustomData2 = $state.current.data.customData2;
    this.lectureCustomData1 = $state.get('lecture').data.customData1;
    this.lectureCustomData2 = $state.get('lecture').data.customData2;
});

app.controller('lectureController', function($http) {
    var $scp = this;
    app.wrapBodyBack(true); 
    $http({method:'GET', url:'./module/proc/data.html?mode=lecture'}).then(function(response_) {
        $scp.lectures = response_.data;
        app.wrapBodyBack(false); 
    }, function(reason_) {
        $scp.error = reason_.data;
    });
});

app.controller('studentTotalController', function(studentTotals) {
    this.totals = studentTotals[2].total;
});

app.controller('studentPController', function(studentTotals) {
    this.males = studentTotals[0].total;
    this.females = studentTotals[1].total;
    this.totals = studentTotals[2].total;
});

// app.controller('studentPController', function($http, $stateParams) { 
//     var $scp = this;
//     app.wrapBodyBack(true);
//     $http({
//         method:'GET', 
//         url:'./module/proc/data.html',
//         params: {mode:'studentD', id:$stateParams.id},
//     }).then(function(response_) {    
//         $scp.student = response_.data[0];
//         app.wrapBodyBack(false);
//     }, function(reason_) {
//         $scp.error = reason_.data;
//     });
// });

app.controller('studentController', function(studentList, $state, $location, studentTotals) {
    var $scp = this;
    $scp.reloadData = function() {      
        $state.reload();
    }

    $scp.students = studentList; 
    $scp.studentTotals = studentTotals; 
    app.wrapBodyBack(false);

    this.search = function(item) { // 검색함수 지정
        if ($scp.searchText == undefined) { // 검색 텍스트를 검사
            return true;
        } else {         
            if (item.name.toLocaleLowerCase().indexOf($scp.searchText.toLowerCase()) > -1 ||
                item.cityName.toLocaleLowerCase().indexOf($scp.searchText.toLowerCase()) > -1) {
                return true;
            }
        }
        return false;
    }
});

app.controller('studentDController', function($http, $stateParams) { 
    var $scp = this;
    app.wrapBodyBack(true);
    $http({
        method:'GET', 
        url:'./module/proc/data.html',
        params: {mode:'studentD', id:$stateParams.id},
    }).then(function(response_) {    
        $scp.student = response_.data[0];
        app.wrapBodyBack(false);
    }, function(reason_) {
        $scp.error = reason_.data;
    });
});

// app.controller('studentDController', function($http, $routeParams) {
//     var $scp = this;
//     $http({method:'get', url:'./module/proc/data.html', params:{mode:'studentD',id:$routeParams.id}}).then(function(response_) {
//         $scp.student = response_.data[0];    
//     }, function(reason_) {
//         $scp.error = reason_.data;
//     });
// });    
app.filter('gender', function() {   // 필터 추가
    return function(sGender) {
        switch (sGender) {
            case '1': return '男';
            case '2': return '女';
            default: return 'X';
        }
    }
});

