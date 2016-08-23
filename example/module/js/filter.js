/// <reference path="app.js" />

app.filter('gender', function() {
    return function(gender) {
        switch (gender) {
            case '1': return '男'; break;
            case '2': return '女'; break;
            default: return 'X'; break;
        }
    }
});