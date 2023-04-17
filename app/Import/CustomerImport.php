<?php

namespace App\Import;

use App\Models\BaseCurricular;
use App\Models\Inscricao;
use App\Models\Customer;
use App\Models\Bncc;
use App\Models\Cargo;
use App\Models\Escola;
use App\Models\FrequenciaFuncionarioConsolidado;
use App\Models\FuncaoLaboral;
use App\Models\Funcionario;
use App\Models\InscricaoEscola;
use App\Models\Pessoa;
use App\Models\ProcessoSeletivoExterno\ProcessoSeletivoExterno;
use App\Tools\Sanitize;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

set_time_limit(0);
class CustomerImport
{
    protected $funcionario;
    protected $sanatize;

    public function allData(Request $request)
    {
        $read = IOFactory::load($request->file);
        $sheetCount = $read->getSheetCount();


        for ($i = 0; $i < $sheetCount; $i++) {
            if ($i == 0) {
                $data = $read->getSheet($i)->toArray();
                $line=0;

                $count = 0;

                foreach($data as $item){
                    $line++;

                    if ($line > 1) {
                        $arrayItens = [];
                        foreach($item as $coluna) {
                            if ($coluna != '') {
                                array_push($arrayItens, $coluna);
                            }
                        }

                        if (count($arrayItens) >= 4) {
                            if (count($arrayItens) > 4) {
                                $habilidade = trim(str_replace("- ", "", str_replace("\n", " ", $arrayItens[3])));
                                $objetosConhecimento = trim(str_replace("- ", "", str_replace("\n", " ", $arrayItens[4])));
                            } else {
                                $habilidade = trim(str_replace("- ", "", str_replace("\n", " ", $arrayItens[2])));
                                $objetosConhecimento = trim(str_replace("- ", "", str_replace("\n", " ", $arrayItens[3])));
                            }

                            $habilidades = [];
                            $explode = explode("(EF", $habilidade);
                            foreach($explode as $hab) {
                                if ($hab != '') {
                                    $habilidades[] = '(EF' . $hab;
                                }
                            }

                            foreach($habilidades as $hab) {
                                $inicioCod = strpos($hab, '(') + 1;
                                $fimCod = strpos($hab, ')');
                                $codigo = substr($hab, $inicioCod, $fimCod - $inicioCod);

                                $curriculoPaulista = BaseCurricular::query()
                                    ->where('origem', 'Currículo Paulista')
                                    ->where('codigo', $codigo)
                                    ->first();

                                dump($curriculoPaulista);
                                dd($hab);

                                if (!$curriculoPaulista) {
                                    Log::debug('Não encontrado: ' . $hab);
                                } else {
                                    // $curriculoPaulista->update([
                                    //     'habilidade' => $hab,
                                    //     'objeto_conhecimento' => $objetosConhecimento,
                                    // ]);
                                    // Log::debug('atualizado: ' . $hab);
                                }
                            }
                        }
                        // dd('fim');
                    }
                }

                Log::debug('Finalizado!');
                dd('Fim! Linhas = ' . $line);
            }
        }
        $notification = [
            'message'=> "worksheet_imported",
        ];
    }
}
