<?php

namespace App\Import;

use App\Models\Customer;
use App\Models\Bncc;
use App\Models\Cargo;
use App\Models\Escola;
use App\Models\FuncaoLaboral;
use App\Models\Funcionario;
use App\Models\Pessoa;
use App\Tools\Sanitize;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

set_time_limit(0);
class CustomerImport
{
    protected $funcionario;
    protected $sanatize;

    // public function __construct(Funcionario $funcionario, Sanitize $sanatize)
    // {
    //     $this->funcionario = $funcionario;
    //     $this->sanatize = $sanatize;
    // }

    public function allData(Request $request)
    {
        $read = IOFactory::load($request->file);

        $sheetCount = $read->getSheetCount();
        for ($i = 0; $i < $sheetCount; $i++) {
            if ($i == 1) {
                $data = $read->getSheet($i)->toArray();

                $line=0;
                $created=0;
                $alterados=0;

                foreach($data as $item){
                    $line++;
                    if($line > 499 && $line <= 1000){ //
                        if ($item[0] != '') {
                            $matricula = trim($item[0]);
                            $disciplina = trim(ucfirst(mb_strtolower($item[2], 'UTF-8')));
                            $escola = explode(".", $item[4]);
                            $dias = $item[8];
                            $qtde_filhos = $item[7];

                            $diasPontos = $dias * 0.005;
                            if ($diasPontos > 5) $diasPontos = 5;

                            $pontos = $diasPontos + $item[10] + $item[11] + $item[12] + $item[13] + $item[14];



                            dump($line .' - '. $pontos);
                        }
                    }
                }

                dd('finalizado!');
            }
        }
        $notification = [
            'message'=> "worksheet_imported",
            // 'created'=> $created,
        ];
    }
}
