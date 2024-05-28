<?php


use Illuminate\Support\Carbon;

if (!function_exists('convertToTimeAgo')) {
    function convertToTimeAgo($datetime)
    {
        // Parse the datetime string into a Carbon instance
        $datetime = Carbon::parse($datetime);

        // Get the difference in seconds between the datetime and now
        $difference = $datetime->diffInSeconds(Carbon::now());

        // Determine the appropriate time unit based on the difference
        if ($difference < 60) {
            // Less than a minute
            return $difference . ' seconds ago';
        } elseif ($difference < 3600) {
            // Less than an hour
            $minutes = round($difference / 60);
            return $minutes . ' minutes ago';
        } elseif ($difference < 86400) {
            // Less than a day
            $hours = round($difference / 3600);
            return $hours . ' hours ago';
        } else {
            // More than a day
            $days = round($difference / 86400);
            return $days . ' days ago';
        }
    }
}

if (!function_exists('convertTimezone')) {
    function convertTimezone($timezone,$date)
    {
        // Create a new DateTime object with the given date
        $dateTime = new DateTime($date);

        // Set the timezone for the DateTime object
        $dateTime->setTimezone(new DateTimeZone($timezone));

        // Format the date to be more readable
        return $dateTime->format('Y-m-d H:i:s');
    }
}
