<?php

namespace App\Import;

use App\Models\Customer;
use App\Models\Bncc;
use App\Models\Cargo;
use App\Models\Escola;
use App\Models\FrequenciaFuncionarioConsolidado;
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
            if ($i == 0) {
                $data = $read->getSheet($i)->toArray();

                $line=0;
                $created=0;
                $alterados=0;

                foreach($data as $item){
                    $line++;
                    if($line > 1){ // && $line <= 500
                        if ($item[0] != '') {
                            $matricula = trim($item[0]);
                            $diasAcumulados = $item[5];

                            $funcionario = Funcionario::query()->select(['tbfuncionarios_id'])->where('tbfuncionarios_matricula', $matricula)->first();

                            if ($funcionario != null) {
                                $dados = [
                                    'acumulado' => $diasAcumulados,
                                ];
                                FrequenciaFuncionarioConsolidado::query()
                                    ->where('tbfuncionarios_id', $funcionario->tbfuncionarios_id)
                                    ->where('ano', 2022)
                                    ->update($dados);
                            }

                            // dd($item);
                            // dump($line .' - '. $matricula);
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
