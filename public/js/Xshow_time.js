function show_time() {
var today = new Date();

// var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
var options = {hour:'numeric',minute:'numeric'};
options.timeZone = 'America/Los_Angeles';
options.timeZoneName = 'short';

var now = today.toLocaleTimeString('en-US',options);
return now;
}
