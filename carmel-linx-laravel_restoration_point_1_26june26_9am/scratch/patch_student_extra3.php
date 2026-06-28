<?php

$content = file_get_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_mentoring_scripts.blade.php");

$oldPayloadEx = '    extracurricular: Array.from(document.querySelectorAll(\'.extra-row\')).map(row => ({
      semester: row.querySelector(\'.ex-sem\').value,
      activity_name: row.querySelector(\'.ex-act\').value,
      achievement: row.querySelector(\'.ex-ach\').value
    })).filter(ex => ex.activity_name !== \'\'),';

$newPayloadEx = '    // Extra-curricular handled via separate popup modal now
    extracurricular: [],';

$content = str_replace($oldPayloadEx, $newPayloadEx, $content);

file_put_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_mentoring_scripts.blade.php", $content);
echo "Updated saveStudentMentoringData\n";
