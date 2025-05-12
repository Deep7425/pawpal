<?php

namespace App\Imports;

use App\Models\Admin\QuizQuestion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SymptomImport implements ToModel, WithHeadingRow {
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row) {
     
        return new QuizQuestion([
            'symptom_id' => $row['symptom_id'],
            'oid' => $row['	id'] ?? null,
            'question' => $row['question'],
			'optionA' => $row['optiona'],
			'optionB' => $row['optionb'],
			'optionC' => $row['optionc'],
			'optionD' => $row['optiond'],
			'correctOption' => $row['correctoption'],
			'question_hindi' => $row['question_hindi'],
			'optionA_hindi' => $row['optiona_hindi'],
			'optionB_hindi' => $row['optionb_hindi'],
			'optionC_hindi' => $row['optionc_hindi'],
			'optionD_hindi' => $row['optiond_hindi'],
            'status' => 1,

        ]);

    }
}
