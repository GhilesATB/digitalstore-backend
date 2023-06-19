<?php

namespace App\Support\Filter;

use Illuminate\Http\Request;

class Filter
{
    static function stringFilter(Request $request): array
    {
        $cryteria = [];

        if (filled($request->input('field'))) {

            $cryteria = match ($request->input('operator')) {
                'contains' => [$request->input('field'), 'LIKE', "%".$request->input('value')."%"],
                'startWith' => [$request->input('field'), 'LIKE', $request->input('value')."%"],
                'endWith' => [$request->input('field'), 'LIKE', "%".$request->input('value')],
            };
        }

        return $cryteria;
    }
}
