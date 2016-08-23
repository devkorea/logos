/// <reference path="angular-1.5.8/angular.js" />
/// <reference path="./example/plugins/angular-1.5.8/angular-route.js" />

var app = angular.module('Demo', ['ngRoute']);
app.config(function($routeProvider, $locationProvider) {
    $routeProvider
        .when('/home', {
            templateUrl: 'skin/home.html',
            controller: 'homeController'
        })
        .when('/lecture', {
            templateUrl: 'skin/lecture.html',
            controller: 'lectureController'
        })
        .when('/student', {
            templateUrl: 'skin/student.html',
            controller: 'studentController'
        })
        .otherwise({
            redirectTo: '/home'
        });
    $locationProvider.html5Mode(true);
});

app.controller('homeController', function($scope) {
    $scope.message = 'Welcome to main';
});


app.controller('lectureController', function($scope, $http) {
    $http({method:'GET', url:'./module/proc/data.html?mode=lecture'}).then(function(response_) {
        $scope.lectures = response_.data;
    }, function(reason_) {
        $scope.error = reason_.data;
    });});


app.controller('studentController', function($scope, $http) {
    $http({method:'GET', url:'./module/proc/data.html?mode=student'}).then(function(response_) {
        $scope.students = response_.data;
    }, function(reason_) {
        $scope.error = reason_.data;
    });

    $scope.search = function(item) { // 검색함수 지정
        if ($scope.searchText == undefined) { // 검색 텍스트를 검사
            return true;
        } else {         
            if (item.name.toLocaleLowerCase().indexOf($scope.searchText.toLowerCase()) > -1 ||
                item.cityName.toLocaleLowerCase().indexOf($scope.searchText.toLowerCase()) > -1) {
                return true;
            }
        }
        return false;
    }
});

app.filter('gender', function() {
    return function(sGender) {
        switch (sGender) {
            case '1': return '男';
            case '2': return '女';
            default: return 'X';
        }
    }
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
