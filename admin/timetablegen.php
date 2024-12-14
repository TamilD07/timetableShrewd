<?php

function generate_time_table($con, $courseid, $s, $department_name, $semester_name){

    // Select subjects along with their lecture_per_week count
    $query = "SELECT * FROM subject WHERE department_id = $courseid AND sem_id = $s AND lecture_per_week > 0";
    $que = mysqli_query($con, $query);
    $rows = mysqli_num_rows($que);

    if($rows != 0){
    
        $subjects = array();
        
        while($row = mysqli_fetch_assoc($que)){
            array_push($subjects, $row);
        }
        
        $weekTimeTable = array();
        
        for($i = 0; $i <= 4; $i++){
        
            $dayTimeTable = array();
            $allocatedSubjects = array(); // Array to store allocated subjects for each day
            shuffle($subjects);
            $pointer = 0;
        
            for($j = 0; $j <= 7; $j++){
        
                try{
                    // Find the next subject with available lectures and lecture_per_week > 0
                    while($pointer < count($subjects) && ($subjects[$pointer]['lecture_per_week'] === 0 || in_array($subjects[$pointer]['subject_id'], $allocatedSubjects))){
                        $pointer++;
                    }
        
                    // Check if there are still subjects available
                    if($pointer >= count($subjects)){
                        break; // No more subjects with lectures for the week or all subjects are allocated for the day
                    }
        
                    // Allocate the subject if it meets the conditions
                    if($subjects[$pointer]['type'] === "Lab"){
                        // Check if the current slot is a lab slot (1st, 2nd, or 4th slot)
                        if(in_array($j, [1, 2, 4])){
                            array_push($dayTimeTable, $subjects[$pointer]);
                            array_push($dayTimeTable, $subjects[$pointer]);
                            $subjects[$pointer]['lecture_per_week']--;
                            $allocatedSubjects[] = $subjects[$pointer]['subject_id']; // Add subject to allocated list
                        }
                        else{
                            // Skip lab subject if it cannot be scheduled in the current slot
                            continue;
                        }
                    }
                    else if($subjects[$pointer]['type'] == "Theory"){
                        array_push($dayTimeTable, $subjects[$pointer]);
                        $subjects[$pointer]['lecture_per_week']--;
                        $allocatedSubjects[] = $subjects[$pointer]['subject_id']; // Add subject to allocated list
                    }

                    // Move to the next subject
                    $pointer++;
                }
                catch(Exception $e){
                    array_push($dayTimeTable, "Empty");
                }
            }
        
            array_push($weekTimeTable, $dayTimeTable);
        
        }

        // Define constraints for backtracking
        $labSlots = [1, 2, 4];
        
        // Function to check if lab subject is scheduled in its slot
        function isLabScheduled($subject, $dayTimeTable, $labSlots) {
            foreach ($labSlots as $slot) {
                if ($dayTimeTable[$slot] === $subject) {
                    return true;
                }
            }
            return false;
        }

        // Backtracking to ensure lab subjects are scheduled in their slots
        foreach ($weekTimeTable as &$day) {
            foreach ($day as &$slot) {
                if ($slot !== 'Empty' && $slot['type'] === "Lab" && !isLabScheduled($slot, $day, $labSlots)) {
                    // Backtrack
                    foreach ($day as &$backtrackSlot) {
                        if ($backtrackSlot === 'Empty' && in_array(key($day), $labSlots)) {
                            $backtrackSlot = $slot;
                            break;
                        }
                    }
                }
            }
        }

        // Check and schedule remaining lectures for subjects if necessary
        foreach ($weekTimeTable as $day) {
            $scheduledCount = array_count_values(array_column($day, 'subject_id'));
            
            foreach ($scheduledCount as $subjectId => $count) {
                $subject = array_filter($subjects, function ($sub) use ($subjectId) {
                    return $sub['subject_id'] == $subjectId;
                });

                if (!empty($subject)) {
                    $subject = reset($subject);
                    $requiredCount = $subject['lecture_per_week'];
                    $scheduled = isset($scheduledCount[$subjectId]) ? $scheduledCount[$subjectId] : 0;

                    while ($scheduled < $requiredCount) {
                        foreach ($day as &$slot) {
                            if ($slot === 'Empty' && $scheduled < $requiredCount) {
                                $slot = $subject;
                                $scheduled++;
                            }
                        }
                    }
                }
            }
        }
        
        return $weekTimeTable;
    
    }
    else{
        return false;
    }

}

?>
