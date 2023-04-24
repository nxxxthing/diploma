<?php

namespace App\Imports;

use App\Models\Translation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TranslationsImport implements ToModel,WithHeadingRow
{
    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    /**
    * @param array $row
    *TranslationsImport
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!empty($row['key']))
        {
            $translation = Translation::where('locale',$this->locale)->where('key',$row['key'])->first();

            if(!$translation)
            {
                return new Translation([
                    'key'       => $row['key'],
                    'locale'    => $this->locale,
                    'group'     => 'site_labels',
                    'value'     => $row[$this->locale]
                ]);
            }
            else
            {
                return $translation->fill(
                    [
                        'value'     => $row[$this->locale]
                    ]
                );
            }
        }
        return null;
    }
}
