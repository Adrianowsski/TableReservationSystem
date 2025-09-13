<?php
// includes/time_options.php

function generateTimeOptions($selectedTime = "") {
    $options = "";
    $start = new DateTime("13:00");
    $end   = new DateTime("22:00");
    while ($start <= $end) {
        $timeValue = $start->format("H:i");
        $selected = ($timeValue === $selectedTime) ? ' selected' : '';
        $options .= "<option value=\"$timeValue\"$selected>$timeValue</option>\n";
        $start->modify("+15 minutes");
    }
    return $options;
}
