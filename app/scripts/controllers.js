'use strict';

angular.module('bookMyClassApp')
    .controller('HeaderController', function($scope, $rootScope, $state) {
        $scope.tab = 1;
        $rootScope.uid = 1;
        $scope.$on('$stateChangeSuccess', function() {
            if ($state.$current == 'app.aboutus')
                $scope.tab = 3;
            else if ($state.$current == 'app.availability' ||
                $state.$current == 'app.week')
                $scope.tab = 2;
            else
                $scope.tab = 1;
        });
        $scope.isSelected = function(checkTab) {
            return ($scope.tab === checkTab);
        };
    })
    .controller('MyBookController', function($scope, $rootScope, $state, $uibModal, $log, BookingService) {
        $scope.Slots = [[],[],[],[],[],[]];
        $scope.days = BookingService.days;
        $scope.fillMyBookings = function () {
            $scope.myBookings = [];
            for (var i = 0; i < $scope.Slots.length; i++) {
                var obj = $scope.Slots[i];
                for (var j = 0; j < obj.length; j++) {
                    if (obj[j].user_id === $rootScope.uid && !obj[j].available) {
                        $scope.myBookings.push(obj[j]);
                    }
                }
            }
        };
        BookingService.getBookings().query(
            function(response) {
                $scope.Slots = [[],[],[],[],[],[]];
                for(var i=0;i<response.length;i++) {
                    $scope.Slots[response[i].day].push(response[i]);
                }
                $scope.fillMyBookings();
            },
            function(response) {
                $scope.message = "Error: " + response.status + " " + response.statusText;
            }
        )

        $scope.showModal = function(val) {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'views/cancelModal.html',
                controller: 'CancelModalInstanceCtrl',
                resolve: {
                    appointment: function() {
                        return $scope.myBookings[val];
                    },
                    day: function() {
                        return 'hohoho';$scope.day_id;
                    }
                }
            });

            modalInstance.result.then(function(selectedItem) {
                $scope.selected = selectedItem;
                console.log(selectedItem);
                $scope.myBookings.splice($scope.myBookings.indexOf(selectedItem),1);
                // remove the selected appointment from mybookings
            }, function() {
                $log.info('Modal dismissed at: ' + new Date());
            });
        };
    })
    .controller('WeekController', function($scope, $rootScope, BookingService) {
        $scope.days = BookingService.days;
    })
    .controller('AvailabilityController', function($scope, $rootScope, $stateParams, $uibModal, $log, BookingService) {
        $scope.Slots = [[],[],[],[],[],[]];
        $scope.days = BookingService.days;
        $scope.day_id = parseInt($stateParams.id, 10);
        $scope.day = $scope.days[$scope.day_id];
        $scope.openings = [];

        $scope.fillopenings = function () {
            $scope.openings = [];
            for(var j=0;j<$scope.Slots[$scope.day_id].length;j++) {
                if($scope.Slots[$scope.day_id][j].available)
                    $scope.openings.push($scope.Slots[$scope.day_id][j]);
            }
        };
        BookingService.getBookings().query(
            function(response) {
                $scope.Slots = [[],[],[],[],[],[]];
                for(var i=0;i<response.length;i++) {
                    $scope.Slots[response[i].day].push(response[i]);
                }
                $scope.fillopenings();
            },
            function(response) {
                $scope.message = "Error: " + response.status + " " + response.statusText;
            }
        );

        $scope.isClassAvailable = function(setTab) {
            if ($scope.openings.length > 0)
                return true;
            return false;
        };

        $scope.showModal = function(val) {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'views/bookModal.html',
                controller: 'BookingModalInstanceCtrl',
                resolve: {
                    appointment: function() {
                        return $scope.openings[val];
                    },
                    day: function() {
                        return $scope.day_id;
                    }
                }
            });

            modalInstance.result.then(function(selectedItem) {
                $scope.selected = selectedItem;
            }, function() {
                $log.info('Modal dismissed at: ' + new Date());
            });
        };
    })
    .controller('AboutController', ['$scope', function($scope) {
        console.log("AboutController is running");
    }])
    .controller('BookingModalInstanceCtrl', function($scope, $rootScope, $state, $uibModalInstance, appointment, day, BookingService) {

        $scope.appointment = appointment;
        BookingService.getBookings().query(
            function(response) {
                $scope.Slots = [[],[],[],[],[],[]];
                for(var i=0;i<response.length;i++) {
                    $scope.Slots[response[i].day].push(response[i]);
                }
            },
            function(response) {
                $scope.message = "Error: " + response.status + " " + response.statusText;
            }
        );

        $scope.ok = function() {
            $scope.appointment.available = false;
            $scope.appointment.user_id = $rootScope.uid;
            BookingService.bookAppointment().update({id:$scope.appointment.id}, appointment)
            .$promise
            // define success and error callbacks.
            .then(function(result) {
                // after update is successful, going back to first page.
                $uibModalInstance.close($scope.appointment);
                $state.go('app');
            });
        };
        $scope.cancel = function() {
            $uibModalInstance.dismiss('cancel');
        };
    })
    .controller('CancelModalInstanceCtrl', function($scope, $rootScope, $state, $uibModalInstance, appointment, BookingService) {

        $scope.appointment = appointment;

        $scope.ok = function() {
            $scope.appointment.available = true;
            $scope.appointment.user_id = 0; // default user, not assigned to anyone
            BookingService.bookAppointment().update({id:$scope.appointment.id}, appointment)
            .$promise
            // define success and error callbacks.
            .then(function(result) {
                // after update is successful, going back to first page.
                $uibModalInstance.close($scope.appointment);
                $state.go('app');
            });
        };
        $scope.cancel = function() {
            $uibModalInstance.dismiss('cancel');
        };
    });