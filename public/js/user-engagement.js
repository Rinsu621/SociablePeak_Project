
// $(document).ready(function() {
//     // Retrieve the start time and elapsed time from local storage
//     let startTime = localStorage.getItem('startTime');
//     let elapsedStored = parseInt(localStorage.getItem('elapsedTime'), 10) || 0;
//     let storedTime = localStorage.getItem('currentTime');
//     let storedDate = localStorage.getItem('storedDate');
//     let timerActive = false;
//     let tabSwitchCounter = 0; //store record of tab switches by the user
//     let interval;

//     function storeCurrentTime() {
//          // Get the current time
//         let currentTime = new Date();

//         // Get hours, minutes, and seconds
//         let hours = currentTime.getHours();
//         let minutes = currentTime.getMinutes();
//         let seconds = currentTime.getSeconds();

//         // Format time as HH:MM:SS
//         let formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

//         // Store formatted time in local storage
//         localStorage.setItem('currentTime', formattedTime);
//         console.log(formattedTime);
//         storedTime = formattedTime;
//     }

//     if (storedTime === null){
//         storeCurrentTime();
//     }
//     function storeUserData() {
//         var updateData = {
//             'date': storedDate,
//             'start_time': storedTime,
//             'elapsed_time': elapsedStored,
//             'tab_switch': tabSwitchCounter
//         };
//         console.log(updateData);
//         $.ajax({
//             url: 'store-user-data',
//             type: 'POST',
//             contentType: 'application/json',
//             headers: {
//                 'X-CSRF-TOKEN': csrfToken
//             },
//             data: JSON.stringify(updateData),
//             success: function(data) {
//                 // toastr.success(data.message);
//             },
//             error: function(xhr, status, error) {
//                 toastr.error(error);
//             }
//         });
//     }

//     function startTimer() {
//         if (!timerActive) {
//             // Save today's date in Y-m-d format in local storage
//             let currentDate = new Date().toISOString().slice(0, 10);
//             localStorage.setItem('currentDate', currentDate);

//             // Check if today's date matches the date stored in local storage
//             if (storedDate === null || storedDate !== currentDate) {
//                 // If not same, update stored date in local storage
//                 if (storedDate !== null) { //this means user is launching this site for the first time this day, so store the previous days data
//                     storeUserData();
//                     tabSwitchCounter = 0;
//                 }else{
//                     storeCurrentTime();
//                 }
//                 storedDate = currentDate;
//                 localStorage.setItem('storedDate', currentDate);
//                 elapsedStored = 0;
//             } else {
//                 console.log("Same Date - Do nothing");
//             }
//             // Update or set start time based on previously stored elapsed time
//             startTime = Date.now() - elapsedStored * 1000;
//             localStorage.setItem('startTime', startTime);
//             interval = setInterval(updateTime, 1000);
//             timerActive = true;
//         }
//     }

//     function stopTimer() {
//         if (timerActive) {
//             tabSwitchCounter++;//record tab switch data
//             storeUserData(); //we will also store the data of user as soon as the timer stops
//             clearInterval(interval);
//             updateTime(); // Final update before pausing
//             timerActive = false;
//         }
//     }

//     function updateTime() {
//         let elapsed = Math.floor((Date.now() - startTime) / 1000); // Convert to seconds
//         let hours = Math.floor(elapsed / 3600);
//         let minutes = Math.floor((elapsed % 3600) / 60);
//         let seconds = elapsed % 60;

//         // Formatting time to HH:MM:SS
//         let formattedTime = [
//             hours.toString().padStart(2, '0'),
//             minutes.toString().padStart(2, '0'),
//             seconds.toString().padStart(2, '0')
//         ].join(':');

//         localStorage.setItem('elapsedTime', elapsed);
//         elapsedStored = elapsed;
//         // console.log('elapsed'+elapsed);
//         $('#timerDisplay').text('Timer: ' + formattedTime);
//     }

//     // Start timer initially
//     startTimer();

//     // Event listener for tab changes
//     $(document).on('visibilitychange', function() {
//         if (document.visibilityState === 'visible') {
//             startTimer();
//         } else {
//             stopTimer();
//         }
//     });


//     // Additional focus and blur events for handling app switching
//     $(window).on('focus', startTimer);
//     $(window).on('blur', stopTimer);

//     // Before unload: handle tab close/navigate away , also handles reloads
//     $(window).on('beforeunload', function() {
//         stopTimer();
//     });
// });

$(document).ready(function() {
    // Retrieve the start time and elapsed time from local storage
    let startTime = localStorage.getItem('startTime');
    let elapsedStored = parseInt(localStorage.getItem('elapsedTime'), 10) || 0;
    let storedTime = localStorage.getItem('currentTime');
    let storedDate = localStorage.getItem('storedDate');
    let timerActive = false;
    let tabSwitchCounter = parseInt(localStorage.getItem('tabSwitchCounter'), 10) || 0;
    let interval;

    // Session storage to differentiate between tab switches and page reloads
    let isPageReload = sessionStorage.getItem('isPageReload') === 'true';

    function storeCurrentTime() {
        let currentTime = new Date();
        let hours = currentTime.getHours();
        let minutes = currentTime.getMinutes();
        let seconds = currentTime.getSeconds();
        let formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        localStorage.setItem('currentTime', formattedTime);
        storedTime = formattedTime;
    }

    if (storedTime === null) {
        storeCurrentTime();
    }

    function storeUserData() {
        var updateData = {
            'date': storedDate,
            'start_time': storedTime,
            'elapsed_time': elapsedStored,
            'tab_switch': tabSwitchCounter
        };
        console.log(updateData);
        $.ajax({
            url: '{{ route("storeUserData") }}',
            type: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: JSON.stringify(updateData),
            success: function(data) {
                console.log(data.message);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function resetDailyData() {
        storedDate = new Date().toISOString().slice(0, 10);
        localStorage.setItem('storedDate', storedDate);
        elapsedStored = 0;
        tabSwitchCounter = 0;
        localStorage.setItem('elapsedTime', elapsedStored);
        localStorage.setItem('tabSwitchCounter', tabSwitchCounter);
        storeCurrentTime();
    }

    function startTimer() {
        if (!timerActive) {
            let currentDate = new Date().toISOString().slice(0, 10);
            if (storedDate === null || storedDate !== currentDate) {
                if (storedDate !== null) {
                    storeUserData();
                }
                resetDailyData();
            } else {
                console.log("Same Date - Do nothing");
            }
            startTime = Date.now() - elapsedStored * 1000;
            localStorage.setItem('startTime', startTime);
            interval = setInterval(updateTime, 1000);
            timerActive = true;
        }
    }

    function stopTimer(isTabSwitch = false) {
        if (timerActive) {
            // Increment tab switch counter only if it's an actual tab switch, not a page reload or button click
            if (isTabSwitch && !isPageReload) {
                tabSwitchCounter++;
                localStorage.setItem('tabSwitchCounter', tabSwitchCounter);
            }
            storeUserData();
            clearInterval(interval);
            updateTime();
            timerActive = false;
        }
    }

    function updateTime() {
        let elapsed = Math.floor((Date.now() - startTime) / 1000);
        let hours = Math.floor(elapsed / 3600);
        let minutes = Math.floor((elapsed % 3600) / 60);
        let seconds = elapsed % 60;
        let formattedTime = [
            hours.toString().padStart(2, '0'),
            minutes.toString().padStart(2, '0'),
            seconds.toString().padStart(2, '0')
        ].join(':');
        localStorage.setItem('elapsedTime', elapsed);
        elapsedStored = elapsed;
        $('#timerDisplay').text('Timer: ' + formattedTime);
    }

    startTimer();

    $(document).on('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            startTimer();
        } else {
            stopTimer(true); // Mark it as a tab switch
        }
    });

    $(window).on('focus', startTimer);
    $(window).on('blur', function() {
        stopTimer(true); // Mark it as a tab switch
    });

    // Before unload: handle tab close/navigate away, also handles reloads
    $(window).on('beforeunload', function() {
        // Mark the page as being reloaded in session storage
        sessionStorage.setItem('isPageReload', 'true');
        stopTimer(false); // Do not mark it as a tab switch
    });

    // On load, clear the reload flag from session storage
    sessionStorage.setItem('isPageReload', 'false');

    // Handle Generate Data button click
    $('#generateDataButton').on('click', function() {
        $.ajax({
            url: '{{ route("seedUserEngagement") }}',
            type: 'GET',
            success: function(response) {
                console.log(response.message);
                // Optionally, update the table or other UI elements here
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
