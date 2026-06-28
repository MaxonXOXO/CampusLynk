<?php

\ = file_get_contents('app/Http/Controllers/MentoringController.php');

\ = "\ = (array) (\->data ?? \);";
\ = "\ = (array) (\->data ?? \);\n        \['student'] = \['profile'] ?? null;";

\ = str_replace(\, \, \);
file_put_contents('app/Http/Controllers/MentoringController.php', \);
echo 'Patched MentoringController print mapping';
