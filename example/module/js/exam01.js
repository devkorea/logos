/// <reference path="/example/plugins/angular-1.5.8/angular.js" />
/// <reference path="/example/plugins/angular-1.5.8/angular-route.js" />

var app = angular.module('myModule', []);

app.controller('myController', function($scope, $http, $log, $location, $anchorScroll, stringService) {
    // var employees = [
    //     {name:'Ben', dateOfBirth: new Date('1980-11-23'), gender:'남', salary:50000, city:'Seoul'},
    //     {name:'Sara', dateOfBirth: new Date('1970-05-05'), gender:'여', salary:60000, city:'Busan'},
    //     {name:'Mark', dateOfBirth: new Date('1974-09-15'), gender:'남', salary:70000, city:'Jeju'},
    //     {name:'Pam', dateOfBirth: new Date('1979-10-27'), gender:'여', salary:80000, city:'Masan'},
    //     {name:'Todd', dateOfBirth: new Date('1983-12-30'), gender:'남', salary:90000, city:'DaeGu'}
    // ];

    $http({method:'GET', url:'./module/proc/emp.html'}).then(function(response_) {
        $scope.employees = response_.data;
    }, function(reason_) {
        $scope.error = reason_.data;
    });
    // employees.push({name:'John', dateOfBirth: new Date('1966-12-25'), gender:'남', salary:100000, city:'Inchon'});
    // $scope.employees = employees;
    $scope.sortColumn = 'name';
    $scope.reverseSort = false;
    $scope.sortData = function(column) {
        $scope.reverseSort = ($scope.sortColumn == column) ? !$scope.reverseSort : false;
        $scope.sortColumn = column;
    }
    $scope.getSortClass = function(column) {
        if ($scope.sortColumn == column) {
            return $scope.reverseSort ? 'arrow-down' : 'arrow-up';
        }
        return '';
    }
    $scope.search = function(item) {
        if ($scope.searchText == undefined) {
            return true;
        } else {
            if (item.name.toLocaleLowerCase().indexOf($scope.searchText.toLowerCase()) != -1 ||
                item.city.toLocaleLowerCase().indexOf($scope.searchText.toLowerCase()) != -1) {
                return true;
            }
        }
        return false;
    }

    $http({method:'GET', url:'./module/proc/nation.html'}).then(function(response_) {
        $scope.nations = response_.data;
        console.log($scope.nations);
        // $scope.cities = [];
    }, function(reason_) {
        $scope.error = reason_.data;
    });
    // var conturies = [
    //     {name:'Seoul', cities:[{name:'동작구'},{name:'용산구'},{name:'동대문구'},{name:'영등포구'}]},
    //     {name:'Busan', cities:[{name:'영도구'},{name:'수영구'},{name:'해운대구'}]},
    //     {name:'Jeju', cities:[{name:'서귀포구'},{name:'김녕구'},{name:'애월구'}]},
    //     {name:'Masan', cities:[{name:'성산구'},{name:'진해구'},{name:'합포구'}]},
    //     {name:'DaeGu', cities:[{name:'달서구'},{name:'수성구'},{name:'달성구'}]}
    // ];
    // $scope.conturies = conturies;

    $scope.searchCity = function(id) {
        angular.forEach($scope.nations, function(val, idx) {
            if (val.id == id) {
                $scope.cities = $scope.nations[id - 1].cities;
                return false;
            }
        });
    }

    $scope.transformString = function(input) {
        $scope.output = stringService.process(input);
    }

    $scope.scrollTo = function(scrollLocation) {
        $location.hash(scrollLocation);
        $anchorScroll.yOffset = 0;
        $anchorScroll();
    }
});
