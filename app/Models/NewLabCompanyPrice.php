<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NewLabCompanyPrice  implements WithHeadingRow,ToCollection
{
    public function collection(Collection $rows)
    {
        // Iterate over each row
        foreach ($rows as $index => $row) {
//            if ($index === 0) {
//                continue; // Skip the header row
//            }

            $testName = $row['test_name'];
            $defaultLab = DefaultLabs::where('title', 'like', "%$testName%")->first();
            if (!$defaultLab) {
                // Create DefaultLabs if not found
                $defaultLab = DefaultLabs::create([
                    "title" => $testName,
                    "short_name" => $testName
                ]);
            }
            // Get the DefaultLabs ID
            $defaultLabId = $defaultLab->id;

            $labCompanyTitle = $row['company_name'];

            // Get the LabCompany
            $labCompany = LabCompany::where('title', 'like', "%$labCompanyTitle%")->first();
            if (!$labCompany) {
                // Create LabCompany if not found
                $labCompany = LabCompany::create([
                    "title" => $labCompanyTitle
                ]);
            }
            // Get the LabCompany ID
            $labCompanyId = $labCompany->id;

            // Get the amount from the 'amount' column
            $amount = $row['price'];

            // Check if LabPrice entry already exists
            $labPrice = LabPrice::where('company_id', $labCompanyId)
                ->where('lab_id', $defaultLabId)
                ->first();
//            dd($labPrice);

            // Insert data into the price table
            if ($labPrice) {
                // If LabPrice entry exists and the amount is different, update the amount
                if ($labPrice->amount != $amount) {
                    $labPrice->amount = $amount;
                    $labPrice->save();
                }
            } else {
                // Insert new LabPrice entry if it doesn't exist
                $price = new LabPrice();
                $price->company_id = $labCompanyId;
                $price->lab_id = $defaultLabId;
                $price->amount = $amount;
                $price->save();
            }
        }
    }
    public function getHeaderRow()
    {
        return $this->headerRow;
    }

}
