/// <reference path="angular-1.5.8/angular.js" />
/// <reference path="./example/plugins/angular-1.5.8/angular-route.js" />


// ngRoute vs ui-router (ngRoute 를 이용한 코드)
var app = angular.module('Demo', ['ngRoute']);
app.config(function($routeProvider, $locationProvider) {
    $routeProvider.caseInsensitiveMatch = true;
    $routeProvider
        .when('/home', {
            template: '<div style="color:red">Inline Template</div>',
            // templateUrl: 'skin/home.html',
            controller: 'homeController',
            controllerAs: 'homeCtrl'
        })
        .when('/lecture', {
            templateUrl: 'skin/lecture.html',
            controller: 'lectureController',
            controllerAs: 'lecCtrl'
        })
        .when('/student', {
            templateUrl: 'skin/student.html',
            controller: 'studentController',
            controllerAs: 'stdCtrl',
            resolve: {
                studentList: function($http) {
                    $('body').oLoader({
                        wholeWindow: true,
                        lockOverflow: true,
                        backgroundColor: '#000',
                        fadeInTime: 0,
                        fadeLevel: 0.7,
                        image: './module/img/ajax-loader.gif',
                    });

                    return $http({method:'GET', url:'./module/proc/data.html?mode=student'}).then(function(response_) {
                        return response_.data;
                    }, function(reason_) {
                        return reason_.data;
                    });
                }
            }
        })
        .when('/student/:id/', {
            templateUrl: 'skin/studentD.html',
            controller: 'studentDController',
            controllerAs: 'detlCtrl'
        })        
        .otherwise({
            redirectTo: '/'
        });
    $locationProvider.html5Mode(true);
});

app.controller('homeController', function() {
    this.message = 'Welcome to main';
});


app.controller('lectureController', function($http) {
    var $scp = this;
    $http({method:'GET', url:'./module/proc/data.html?mode=lecture'}).then(function(response_) {
        $scp.lectures = response_.data;
    }, function(reason_) {
        $scp.error = reason_.data;
    });
});


app.controller('studentController', function(studentList, $route) {
    // $rootScope.$on('$routeChangeStart', function(event, next, current) {
    //     $log.debug('$routeChangeStart');
    //     $log.debug(event);
    //     $log.debug(next);
    //     $log.debug(current);
    // });
    // $rootScope.$on('$locationChangeStart', function(event, next, current) {
    //     $log.debug('$locationChangeStart');
    //     $log.debug(event);
    //     $log.debug(next);
    //     $log.debug(current);
    // });

    // $rootScope.$on('$locationChangeSuccess', function() {
    //     $log.debug('$locationChangeSuccess');
    // });
    // $rootScope.$on('$routeChangeSuccess', function() {
    //     $log.debug('$routeChangeSuccess');
    // });
    // 페이지 이동시 물어보기      
    // // $scope.$on('$routeChangeStart', function(event, next, current) {
    // //     if (!confirm('Are you sure you want to navigate away from '+next.$$route.originalPath) == true) {
    // $scope.$on('$locationChangeStart', function(event, next, current) {
    //     if (!confirm('Are you sure you want to navigate away from '+next)) {
    //         event.preventDefault();
    //     }
    // });

    var $scp = this;
    $scp.reloadData = function() {      
        $route.reload();
    }
    
    this.students = studentList;
    $('body').oLoader('hide');
    // $http({method:'GET', url:'./module/proc/data.html?mode=student'}).then(function(response_) {
    //     $scp.students = response_.data;
    // }, function(reason_) {
    //     $scp.error = reason_.data;
    // });

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

app.controller('studentDController', function($http, $routeParams) {
    var $scp = this;
    $http({method:'get', url:'./module/proc/data.html', params:{mode:'studentD',id:$routeParams.id}}).then(function(response_) {
        $scp.student = response_.data[0];    
    }, function(reason_) {
        $scp.error = reason_.data;
    });
});    
app.filter('gender', function() {   // 필터 추가
    return function(sGender) {
        switch (sGender) {
            case '1': return '男';
            case '2': return '女';
            default: return 'X';
        }
    }
});


app.controller('parent1Controller', function() {
    this.name = 'parent1';
});
app.controller('parent2Controller', function() {
    this.name = 'parent2';
});
app.controller('parent3Controller', function() {
    this.name = 'parent3';
});


// POST 방식으로 데이터 전송하는 방법
// $http({
//     method: 'POST' ,
//     url: 'www.example.com',
//     data: $.param({
//         Name: '이름',
//         Age: '나이',
//         City: '지역'
//     }),
//     headers: {
//         'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
//     }
// }).success(function(response) {
//     console.log('Success');
// }).finally(function() {
//     console.log('Complete');
// });

// Module 외부에서 Controller 내 함수 호출하는 방법
// angular.module('MainApp', [])
//     .controller('MainController', function($scope) {
//         $scope.start = function() {
//             alert('Scope Start Function');
//         }
//     });
//
// function Scope() {
//     var scope = angular.element(document.getElementById("Container")).scope();
//     return scope;
// }
//
// $(function () {
//     Scope().$apply(function () {
//         Scope().start();
//     });
// });

