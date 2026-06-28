<?php
$path = 'app/Models/CfCourseFile.php';
$content = file_get_contents($path);

// remove the last closing brace
$pos = strrpos($content, '}');
if ($pos !== false) {
    $content = substr_replace($content, "    public function documents()\n    {\n        return \$this->hasMany(CfCourseFileDocument::class, 'course_file_id');\n    }\n}\n", $pos, 1);
    file_put_contents($path, $content);
    echo "Added relationship.\n";
}
