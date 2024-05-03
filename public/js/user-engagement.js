
$(document).ready(function() {
    // Retrieve the start time and elapsed time from local storage
    let startTime = localStorage.getItem('startTime');
    let elapsedStored = parseInt(localStorage.getItem('elapsedTime'), 10) || 0;
    let storedTime = localStorage.getItem('currentTime');
    let storedDate = localStorage.getItem('storedDate');
    let timerActive = false;
    let tabSwitchCounter = 0; //store record of tab switches by the user
    let interval;

    function storeCurrentTime() {
         // Get the current time
        let currentTime = new Date();

        // Get hours, minutes, and seconds
        let hours = currentTime.getHours();
        let minutes = currentTime.getMinutes();
        let seconds = currentTime.getSeconds();

        // Format time as HH:MM:SS
        let formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        // Store formatted time in local storage
        localStorage.setItem('currentTime', formattedTime);
        console.log(formattedTime);
        storedTime = formattedTime;
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
            url: 'store-user-data',
            type: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: JSON.stringify(updateData),
            success: function(data) {
                toastr.success(data.message);
            },
            error: function(xhr, status, error) {
                toastr.error(error);
            }
        });
    }

    function startTimer() {
        if (!timerActive) {
            // Save today's date in Y-m-d format in local storage
            let currentDate = new Date().toISOString().slice(0, 10);
            localStorage.setItem('currentDate', currentDate);

            // Check if today's date matches the date stored in local storage
            if (storedDate === null || storedDate !== currentDate) {
                // If not same, update stored date in local storage
                if (storedDate !== null) { //this means user is launching this site for the first time this day, so store the previous days data
                    storeUserData();
                    tabSwitchCounter = 0;
                }else{
                    storeCurrentTime();
                }
                storedDate = currentDate;
                localStorage.setItem('storedDate', currentDate);
                elapsedStored = 0;
            } else {
                console.log("Same Date - Do nothing");
            }

            // Update or set start time based on previously stored elapsed time
            startTime = Date.now() - elapsedStored * 1000;
            localStorage.setItem('startTime', startTime);
            interval = setInterval(updateTime, 1000);
            timerActive = true;
        }
    }

    function stopTimer() {
        if (timerActive) {
            tabSwitchCounter++;//record tab switch data
            storeUserData(); //we will also store the data of user as soon as the timer stops
            clearInterval(interval);
            updateTime(); // Final update before pausing
            timerActive = false;
        }
    }

    function updateTime() {
        let elapsed = Math.floor((Date.now() - startTime) / 1000); // Convert to seconds
        let hours = Math.floor(elapsed / 3600);
        let minutes = Math.floor((elapsed % 3600) / 60);
        let seconds = elapsed % 60;

        // Formatting time to HH:MM:SS
        let formattedTime = [
            hours.toString().padStart(2, '0'),
            minutes.toString().padStart(2, '0'),
            seconds.toString().padStart(2, '0')
        ].join(':');

        localStorage.setItem('elapsedTime', elapsed);
        elapsedStored = elapsed;
        // console.log('elapsed'+elapsed);
        $('#timerDisplay').text('Timer: ' + formattedTime);
    }

    // Start timer initially
    startTimer();

    // Event listener for tab changes
    $(document).on('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            startTimer();
        } else {
            stopTimer();
        }
    });


    // Additional focus and blur events for handling app switching
    $(window).on('focus', startTimer);
    $(window).on('blur', stopTimer);

    // Before unload: handle tab close/navigate away , also handles reloads
    $(window).on('beforeunload', function() {
        stopTimer();
    });
});