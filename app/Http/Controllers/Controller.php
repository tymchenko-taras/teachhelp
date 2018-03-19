<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs;

    protected $params = [
        'irregular_past_simple' => [
            'pretty' => '+ed / II col',
            'replace-key' => 'irregular_past_simple',
            'search-key' => ['irregular_past_simple', 'irregular_equal_verb', 'regular_past_verb'],
        ],
        'irregular_past_participle' => [
            'pretty' => '+ed / III col',
            'replace-key' => 'irregular_past_participle',
            'search-key' => ['irregular_past_participle', 'irregular_equal_verb', 'regular_past_verb'],
        ],
        'have_has' => [
            'pretty' => 'have / has',
            'replace-key' => '',
            'search-key' => ['have', 'has', "'ve'", "'s'"],
        ],
        'was_were' => [
            'pretty' => 'was / were',
            'replace-key' => '',
            'search-key' => ['was', 'were'],
        ],
    ];

    private function buildSphinxConfigLines(){
        $participle = $past = [];
        $words = DB::select("select `word`, `form` from word where type = 'verb' AND form IN ('irregular_past_simple', 'irregular_past_participle')");
        foreach($words as $word){
            if($word -> form == 'irregular_past_simple'){
                $past[] = $word -> word;
            }
            if($word -> form == 'irregular_past_participle'){
                $participle[] = $word -> word;
            }
        }
        $equal = array_intersect($past, $participle);
        $past = array_diff($past, $equal);
        $participle = array_diff($participle, $equal);

        echo 'regexp_filter = \b('. implode('|', $past) .')\b => irregular_past_verb', '<br>';
        echo 'regexp_filter = \b('. implode('|', $participle) .')\b => irregular_participle_verb', '<br>';
        echo 'regexp_filter = \b('. implode('|', $equal) .')\b => irregular_equal_verb', '<br>';
    }

    private function insertIrregularVerbsIntoDb(){
        $inf = '/var/www/teachhelp/irregular_infinitife.txt';
        $past = '/var/www/teachhelp/simple_past.txt';
        $participle = '/var/www/teachhelp/past_participle.txt';

        foreach(file($inf) as $i => $line){
            $line = explode(' ', $line);
            $verb = trim(array_shift($line));
            $comment = implode(' ', $line);
            $id = DB::table('word')->insertGetId(
                array(
                    'type' => 'verb',
                    'form' => 'irregular_infinitive',
                    'word' => $verb,
                    'comment' => $comment,
                )
            );

            $array = file($past);
            if(!empty($array[ $i ])){
                foreach(explode('/', $array[ $i ]) as $verbs){
                    foreach(explode(',', $verbs) as $verb){
                        DB::table('word')->insertGetId(
                            array(
                                'parent_id' => $id,
                                'type' => 'verb',
                                'form' => 'irregular_past_simple',
                                'word' => trim($verb),
                            )
                        );
                    }
                }
            }

            $array = file($participle);
            if(!empty($array[ $i ])){
                foreach(explode('/', $array[ $i ]) as $verbs){
                    foreach(explode(',', $verbs) as $verb){
                        DB::table('word')->insertGetId(
                            array(
                                'parent_id' => $id,
                                'type' => 'verb',
                                'form' => 'irregular_past_participle',
                                'word' => trim($verb),
                            )
                        );
                    }
                }
            }
        }
    }

    private function splitAndInsertSentancesFromBooks(){
        $books = DB::select('select id from book');
        foreach($books as $book){
            $result = DB::select('select * from paragraph where book_id = ' . $book -> id);
            $update = [];
            foreach($result as $row){
                $sentences = preg_split('/(?<=[.?!])\s*(?=[a-z])/i', $row -> content);
                foreach($sentences as $sentence){
                    $sentence = str_replace(array("\r","\n"), ' ', $sentence);
                    $sentence = preg_replace('#\s+#', ' ', $sentence);
                    if(!$sentence = trim($sentence)) continue;
                    if(strpos($sentence, ' ') === false) continue;

                    $update[] = ['content' => $sentence, 'book_id' => $row -> book_id];
                }
            }

            foreach(array_chunk($update, 1000) as $chunk){
                DB::table('sentence') -> insert($chunk);
            }
        }
    }

	public function split( $ability, $arguments = []){

	}

	public function index( $ability, $arguments = [])
    {
        $result = $matches = $searchword = null;
        if (!empty($_POST['searchword'])) {
            $searchword = $_POST['searchword'];
            $client = new \SphinxClient();

            $data = $client->Query($searchword, 'paragraph');
            if (!empty($data['matches'])) {
                foreach ($data['matches'] as $id => $match) {
                    if (!empty($match['attrs']['content'])) {
                        $matches[] = [
                            'id' => $id,
                            'content' => $match['attrs']['content'],
                        ];
                    }
                }
                $result = view('result_table', ['matches' => $matches]);
            }

        }
        if (!empty($_POST['ajax'])) {
            echo $result;
        } else {
            return view('lalala', ['params' => $this->params, 'result' => $result, 'searchword' => $searchword]);
        }

//
//		exit('work');
//		$result = DB::select('select * from book limit 10');
//
//		print_r($result);
//		exit;
//
//		echo view('lalala');
	}

	protected function getResults(){

	}
}
