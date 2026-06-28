<?php

// Patch CfCourseFile.php
$cfCourseFileContent = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfCourseFile extends Model
{
    protected \$table = 'cf_course_files';
    protected \$guarded = [];

    public function batchSubject()
    {
        return \$this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }

    public function sectionA()
    {
        return \$this->hasOne(CfSectionAPlanning::class, 'cf_id');
    }

    public function sectionB()
    {
        return \$this->hasOne(CfSectionBMaterials::class, 'cf_id');
    }

    public function sectionC()
    {
        return \$this->hasOne(CfSectionCAssessments::class, 'cf_id');
    }

    public function sectionD()
    {
        return \$this->hasOne(CfSectionDAttainments::class, 'cf_id');
    }
}
";
file_put_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\app\\Models\\CfCourseFile.php", $cfCourseFileContent);

// Patch CfSectionAPlanning.php
$cfSectionAContent = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfSectionAPlanning extends Model
{
    protected \$table = 'cf_section_a_planning';
    protected \$guarded = [];

    public function courseFile()
    {
        return \$this->belongsTo(CfCourseFile::class, 'cf_id');
    }
}
";
file_put_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\app\\Models\\CfSectionAPlanning.php", $cfSectionAContent);

// Patch CfSectionBMaterials.php
$cfSectionBContent = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfSectionBMaterials extends Model
{
    protected \$table = 'cf_section_b_materials';
    protected \$guarded = [];

    public function courseFile()
    {
        return \$this->belongsTo(CfCourseFile::class, 'cf_id');
    }
}
";
file_put_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\app\\Models\\CfSectionBMaterials.php", $cfSectionBContent);

// Patch CfSectionCAssessments.php
$cfSectionCContent = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfSectionCAssessments extends Model
{
    protected \$table = 'cf_section_c_assessments';
    protected \$guarded = [];

    public function courseFile()
    {
        return \$this->belongsTo(CfCourseFile::class, 'cf_id');
    }
}
";
file_put_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\app\\Models\\CfSectionCAssessments.php", $cfSectionCContent);

// Patch CfSectionDAttainments.php
$cfSectionDContent = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfSectionDAttainments extends Model
{
    protected \$table = 'cf_section_d_attainments';
    protected \$guarded = [];

    public function courseFile()
    {
        return \$this->belongsTo(CfCourseFile::class, 'cf_id');
    }
}
";
file_put_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\app\\Models\\CfSectionDAttainments.php", $cfSectionDContent);

echo "Updated CF models.\n";
