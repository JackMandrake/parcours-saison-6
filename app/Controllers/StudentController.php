<?php 

namespace App\Controllers;

use App\Models\Student;

class StudentController extends CoreController
{
    /**
     * Page permettant d'afficher la liste des catégories
     *
     * @return void
     */
    public function list()
    {
        $students = Student::findAll();

        $dataToDisplay = [
            'students' => $students
        ];
        $this->show('student/list', $dataToDisplay);
    }

    
}
