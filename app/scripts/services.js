'use-strict';
angular.module('bookMyClassApp')
    .constant("baseURL","http://localhost:3000/")
    .service('BookingService', function($resource, baseURL) {
        this.days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        this.Slots = [[],[],[],[],[],[]];

        this.getBookings = function() {
            return $resource(baseURL + 'bookings');//,{
                //query: {method: 'get', isArray: true}});
        };
        this.bookAppointment = function() {
            return $resource(baseURL + 'bookings' + "/:id", null, {
                'update': {
                    method: 'PUT'
                }
            });
        };
        // this.removeSlot = function(obj) {
        //     for (var i = 0; i < 7; i++) {
        //         index = availableSlots[i].indexOf(obj);
        //         if (index > -1)
        //             availableSlots[i].splice(index, 1);
        //     }
        // }
    })
