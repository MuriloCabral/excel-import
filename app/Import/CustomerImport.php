<?php

namespace App\Import;

use App\Models\Customer;
use App\Models\Bncc;
use App\Tools\Sanitize;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

set_time_limit(0);
class CustomerImport
{
    protected $bncc;
    protected $sanatize;

    public function __construct(Bncc $bncc, Sanitize $sanatize)
    {
        $this->bncc = $bncc;
        $this->sanatize = $sanatize;
    }

    public function allData(Request $request)
    {
        //insert bncc in the database
        $read = IOFactory::load($request->file);

        $sheetCount = $read->getSheetCount();
        for ($i = 0; $i < $sheetCount; $i++) {
            if ($i == 0) {
                $data = $read->getSheet($i)->toArray();

                $line=0;
                $created=0;

                foreach($data as $item){
                    if($line>1){
                        if ($item[0] != '') {

                            $arraySeries = [$item[2]];
                            $series = [];

                            foreach($arraySeries as $serie) {
                                switch (trim($serie)) {
                                    case '1º Ano':
                                        $serie = 1;
                                        break;
                                    case '2º Ano':
                                        $serie = 2;
                                        break;
                                    case '3º Ano':
                                        $serie = 3;
                                        break;
                                    case '4º Ano':
                                        $serie = 4;
                                        break;
                                    case '5º Ano':
                                        $serie = 5;
                                        break;
                                }
                                if ($serie != null) {
                                    array_push($series, $serie);
                                }
                            }

                            // dump($item);

                            $series = array_map( function ($serie) {
                                return $serie;
                            }, $series);

                            $bncc = $this->bncc->firstOrCreate([
                                'mapa_turma'                => 1,
                                'tipo_bncc'                 => 1,
                                'disciplina'                => 1,
                                'componente'                => 'Língua Portuguesa',
                                'ano_faixa'                 => trim($item[2]),
                                'campos_de_atuacao'         => null,
                                'praticas_de_linguagem'     => trim($item[0]),
                                'objetos_de_conhecimento'   => null,
                                'habilidades'               => trim($item[1]),
                            ]);
                            $bncc->bnccSeries()->sync($series);
                            
                            $created++;
                        }
                    }
                    $line++;
                }
                // dd('oi');
            }
        }
        $notification = [
            'message'=> "worksheet_imported",
            // 'created'=> $created,
        ];
    }
}
