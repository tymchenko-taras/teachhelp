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

    // regular_past_verb
    // regular_continuous_verb
    // irregular_past_verb
    // irregular_participle_verb
    // irregular_equal_verb
    //

    protected function getExpressions($ids = []){
        $parts = [
            'have-has' => "(have|has|'ve|hasn't|haven't|have not|has not)",
            'indefinite-adverbs' => '(always|usually|often|sometimes|rarely|never)',
            'regular-continuous-verb' => '(?!\b(understanding|wing|king|interesting|something|spring|hawking|thing|anything|everything|nothing|ceiling|building|dressing|dwelling|feeling|filling|longing|meaning|morning|evening|pudding|shilling|wedding)\b)(\w+ing)',
            'todo' => '(greeting|meeting|landing|opening|clearing|painting|saying|singing|swimming|suffering|warning|writing|hardening)',
            'irregular_past_verb' => '\b(\w+ed|arose|awakened|awoke|backslid|was|were|bore|beat|became|began|bent|bet|betted|bid|bade|bid|bound|bit|bled|blew|broke|bred|brought|broadcast|broadcasted|browbeat|built|burned|burnt|burst|busted|bust|bought|cast|caught|chose|clung|clothed|clad|came|cost|crept|crossbred|cut|daydreamed|daydreamt|dealt|dug|disproved|dove|dived|dived|dove|did|drew|dreamed|dreamt|drank|drove|dwelt|dwelled|ate|fell|fed|felt|fought|found|fitted|fit|fit|fitted|fled|flung|flew|forbade|forecast|forewent|foresaw|foretold|forgot|forgave|forsook|froze|frostbit|got|gave|went|ground|grew|hand-fed|handwrote|hung|had|heard|hewed|hid|hit|held|hurt|inbred|inlaid|input|inputted|interbred|interwove|interweaved|interwound|jerry-built|kept|knelt|kneeled|knitted|knit|knew|laid|led|leaned|leant|leaped|leapt|learned|learnt|left|lent|let|lay|lied|lit|lighted|lip-read|lost|made|meant|met|miscast|misdealt|misdid|misheard|mislaid|misled|mislearned|mislearnt|misread|misset|misspoke|misspelled|misspelt|misspent|mistook|mistaught|misunderstood|miswrote|mowed|offset|outbid|outbred|outdid|outdrew|outdrank|outdrove|outfought|outflew|outgrew|outleaped|outleapt|outlied|outrode|outran|outsold|outshined|outshone|outshot|outsang|outsat|outslept|outsmelled|outsmelt|outspoke|outsped|outspent|outswore|outswam|outthought|outthrew|outwrote|overbid|overbred|overbuilt|overbought|overcame|overdid|overdrew|overdrank|overate|overfed|overhung|overheard|overlaid|overpaid|overrode|overran|oversaw|oversold|oversewed|overshot|overslept|overspoke|overspent|overspilled|overspilt|overtook|overthought|overthrew|overwound|overwrote|partook|paid|pleaded|pled|prebuilt|predid|premade|prepaid|presold|preset|preshrank|proofread|proved|put|quick-froze|quit|quitted|read|reawoke|rebid|rebound|rebroadcast|rebroadcasted|rebuilt|recast|recut|redealt|redid|redrew|refit|refitted|refitted|refit|reground|regrew|rehung|reheard|reknitted|reknit|relaid|relayed|relearned|relearnt|relit|relighted|remade|repaid|reread|reran|resold|resent|reset|resewed|retook|retaught|retore|retold|rethought|retread|retrofitted|retrofit|rewoke|rewaked|rewore|rewove|reweaved|rewed|rewedded|rewet|rewetted|rewon|rewound|rewrote|rid|rode|rang|rose|roughcast|ran|sand-cast|sawed|said|saw|sought|sold|sent|set|sewed|shook|shaved|sheared|shed|shined|shone|shit|shat|shitted|shot|showed|shrank|shrunk|shut|sight-read|sang|sank|sunk|sat|slew|slayed|slayed|slept|slid|slung|slinked|slunk|slit|smelled|smelt|sneaked|snuck|sowed|spoke|sped|speeded|spelled|spelt|spent|spilled|spilt|spun|spit|spat|split|spoiled|spoilt|spoon-fed|spread|sprang|sprung|stood|stole|stuck|stung|stunk|stank|strewed|strode|struck|struck|strung|strove|strived|sublet|sunburned|sunburnt|swore|sweat|sweated|swept|swelled|swam|swung|took|taught|tore|telecast|told|test-drove|test-flew|thought|threw|thrust|trod|typecast|typeset|typewrote|unbent|unbound|unclothed|unclad|underbid|undercut|underfed|underwent|underlay|undersold|underspent|understood|undertook|underwrote|undid|unfroze|unhung|unhid|unknitted|unknit|unlearned|unlearnt|unsewed|unslung|unspun|unstuck|unstrung|unwove|unweaved|unwound|upheld|upset|woke|waked|waylaid|wore|wove|weaved|wed|wedded|wept|wet|wetted|whetted|won|wound|withdrew|withheld|withstood|wrung|wrote)\b',
            'irregular_participle_verb' => '\b(\w+ed|arisen|awakened|awoken|backslidden|backslid|been|born|borne|beaten|beat|become|begun|bent|bet|betted|bidden|bid|bound|bitten|bled|blown|broken|bred|brought|broadcast|broadcasted|browbeaten|browbeat|built|burned|burnt|burst|busted|bust|bought|cast|caught|chosen|clung|clothed|clad|come|cost|crept|crossbred|cut|daydreamed|daydreamt|dealt|dug|disproved|disproven|dived|dived|done|drawn|dreamed|dreamt|drunk|driven|dwelt|dwelled|eaten|fallen|fed|felt|fought|found|fitted|fit|fit|fitted|fled|flung|flown|forbidden|forecast|foregone|foreseen|foretold|forgotten|forgot|forgiven|forsaken|frozen|frostbitten|gotten|got|given|gone|ground|grown|hand-fed|handwritten|hung|had|heard|hewn|hewed|hidden|hit|held|hurt|inbred|inlaid|input|inputted|interbred|interwoven|interweaved|interwound|jerry-built|kept|knelt|kneeled|knitted|knit|known|laid|led|leaned|leant|leaped|leapt|learned|learnt|left|lent|let|lain|lied|lit|lighted|lip-read|lost|made|meant|met|miscast|misdealt|misdone|misheard|mislaid|misled|mislearned|mislearnt|misread|misset|misspoken|misspelled|misspelt|misspent|mistaken|mistaught|misunderstood|miswritten|mowed|mown|offset|outbid|outbred|outdone|outdrawn|outdrunk|outdriven|outfought|outflown|outgrown|outleaped|outleapt|outlied|outridden|outrun|outsold|outshined|outshone|outshot|outsung|outsat|outslept|outsmelled|outsmelt|outspoken|outsped|outspent|outsworn|outswum|outthought|outthrown|outwritten|overbid|overbred|overbuilt|overbought|overcome|overdone|overdrawn|overdrunk|overeaten|overfed|overhung|overheard|overlaid|overpaid|overridden|overrun|overseen|oversold|oversewn|oversewed|overshot|overslept|overspoken|overspent|overspilled|overspilt|overtaken|overthought|overthrown|overwound|overwritten|partaken|paid|pleaded|pled|prebuilt|predone|premade|prepaid|presold|preset|preshrunk|proofread|proven|proved|put|quick-frozen|quit|quitted|read|reawaken|rebid|rebound|rebroadcast|rebroadcasted|rebuilt|recast|recut|redealt|redone|redrawn|refit|refitted|refitted|refit|reground|regrown|rehung|reheard|reknitted|reknit|relaid|relayed|relearned|relearnt|relit|relighted|remade|repaid|reread|rerun|resold|resent|reset|resewn|resewed|retaken|retaught|retorn|retold|rethought|retread|retrofitted|retrofit|rewaken|rewaked|reworn|rewoven|reweaved|rewed|rewedded|rewet|rewetted|rewon|rewound|rewritten|rid|ridden|rung|risen|roughcast|run|sand-cast|sawed|sawn|said|seen|sought|sold|sent|set|sewn|sewed|shaken|shaved|shaven|sheared|shorn|shed|shined|shone|shit|shat|shitted|shot|shown|showed|shrunk|shut|sight-read|sung|sunk|sat|slain|slayed|slayed|slept|slid|slung|slinked|slunk|slit|smelled|smelt|sneaked|snuck|sown|sowed|spoken|sped|speeded|spelled|spelt|spent|spilled|spilt|spun|spit|spat|split|spoiled|spoilt|spoon-fed|spread|sprung|stood|stolen|stuck|stung|stunk|strewn|strewed|stridden|stricken|struck|stricken|strung|striven|strived|sublet|sunburned|sunburnt|sworn|sweat|sweated|swept|swollen|swelled|swum|swung|taken|taught|torn|telecast|told|test-driven|test-flown|thought|thrown|thrust|trodden|trod|typecast|typeset|typewritten|unbent|unbound|unclothed|unclad|underbid|undercut|underfed|undergone|underlain|undersold|underspent|understood|undertaken|underwritten|undone|unfrozen|unhung|unhidden|unknitted|unknit|unlearned|unlearnt|unsewn|unsewed|unslung|unspun|unstuck|unstrung|unwoven|unweaved|unwound|upheld|upset|woken|waked|waylaid|worn|woven|weaved|wed|wedded|wept|wet|wetted|whetted|won|wound|withdrawn|withheld|withstood|wrung|written)\b',
        ];
// після while та when це partciple
        //перед participle та gerund не буде артикля, артикль неред герундієм не ставиться
        // some any this my his (pronoun) - перед герундієм не ставиться
        $expressions = [
            [
                'name' => 'Gerund plus be',
                'expression' => implode('', [
                    "\b{$parts['regular-continuous-verb']}\b",
                    "(?!.*\b(to|us|at|as|it|\.|,|!|when).*\b)(.{0,20})",
                    "\s+\b(is|was|has been|will be)\b"
                ]),
            ],
            [
                'name' => 'present perf contin',
                'expression' => implode('', [
                    "{$parts['have-has']}",
                    "(?=.*\b{$parts['indefinite-adverbs']}.*\b)(.{0,20})",
                    "\s+\bbeen\b",
                    "\s+\b{$parts['regular-continuous-verb']}\b",
                ]),
            ],
            [
                'name' => 'present perf contin',
                'expression' => implode('', [
                    "(?!\b(to)\b){$parts['have-has']}",         //"have", but not "have to"
                    "(\s+{$parts['indefinite-adverbs']}\s)?",   // ever, never
                    "(?!\s+been\s\w+ing)\s+\b{$parts['irregular_participle_verb']}\b",  // participle_verb but not "been doing", however been is participle_verb as well
                ]),
            ],
        ];

        if($ids){
            $expressions = array_intersect_key($expressions, array_flip($ids));
        }

        return $expressions;
    }

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

    private function buildSphinxConfigLines2(){
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


        echo 'regexp_filter = \b('. implode('|', $past) .')\b => irregular_past_verb', '<br>';
        echo 'regexp_filter = \b('. implode('|', $participle) .')\b => irregular_participle_verb', '<br>';
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

	private function readTatoebaSentences(){
        //11380

        $update = [];
        $i = 0;
        $handle = fopen('/var/www/sentences.tar/sentences/sentences.csv', 'r');
        if ($handle) {
            while (!feof($handle)) {
                if($line = fgets($handle)) {
                    $line = explode("\t", $line);
                    if(!empty($line[0]) && !empty($line[1]) && !empty($line[2])){
                        if($line[1] == 'eng'){
                            $update[] = ['external_id' => $line[0], 'content' => $line[2], 'book_id' => 11380];

                            if(($i++ % 1000) == 0){
                                DB::table('sentence') -> insert($update);
                                $update = [];
                            }
                        }
                    }
                }
            }

            if($update){
                DB::table('sentence') -> insert($update);
            }

            fclose($handle);
        }
    }



	public function buildDatFile(){
        set_time_limit(1200);

$nums = [];
        for($i = 0;;$i += 10000) {
            $sentences = DB::select($sql = "select `id`, `content` from `sentence` LIMIT $i, 10000");
            if(empty($sentences)) break;

            foreach($sentences as $sentence){
                foreach($this->getExpressions() as $alias => $expression) {
                    if($result = preg_match('#'. $expression['expression'] .'#i', $sentence->content)) {
                        //echo $sentence->content, '<br>';
                        if (!isset($nums[$alias])) {
                            $nums[$alias] = pack('N', $sentence->id);
                        } else {
                            $nums[$alias] .= pack('N', $sentence->id);
                        }
                    }
                }
            }
            file_put_contents('test.txt', $sql.PHP_EOL, FILE_APPEND);
        }

        ksort($nums);
        // Сохраняем данные в файл
        $fh = fopen('passports.dat', 'wb');
        $data = (count($nums)*2) + 2;
        $index = pack('n', $data);
        fseek($fh, $data);
        foreach($nums as $num){ // Цикл по всем номерам паспортов
            $data += fwrite($fh, $num);
            $index .= pack('n', $data);
        }



        fseek($fh, 0);
        fwrite($fh, $index);
	}

    public function getFormDatFile($expressionIds){
        $itemIds = [];
        if($expressionIds) {
            $fn = fopen('passports.dat', 'rb');
            foreach($expressionIds as $expressionId) {
                fseek($fn, $expressionId * 2);
                $seek = unpack('nbegin/nend', fread($fn, 4));
                fseek($fn, $seek['begin']);
                $ids = fread($fn, $seek['end'] - $seek['begin']);
                if($ids = unpack('N*', $ids)){
                    $itemIds = array_merge($itemIds, $ids);
                }
            }

            return array_unique($itemIds);
        }
    }

	public function build( $ability, $arguments = []){
        $this -> buildDatFile();
        exit('done');
    }

	public function index( $ability, $arguments = [])
    {





        $result = 'No results :(';
        $matches = [];
        $patterns = [];
        $searchword = null;

        if(!empty($_POST['pattern'])){
            $items = DB::table('pattern')->whereIn('id', array_keys($_POST['pattern'])) -> get();
            foreach($items as $item){
                $patterns[] = '(' . $item -> value . ')';
            }
        }
        if (!empty($_POST['searchword'])) {
            $searchword = $_POST['searchword'];
        }

        $query = trim($searchword .' '. implode('|', $patterns));
//exit($query);
        if (1||$query) {
            $expressionIds = [0];
            $client = new \SphinxClient();
            $client -> SetServer('container-sphinx', 9312);
            $client->SetMatchMode(SPH_MATCH_EXTENDED);
            $client->SetLimits(0, 1000);
            $client->SetFilter('itemid', array_slice($this -> getFormDatFile($expressionIds), 0, 4096), 0);

            $data = $client->Query($query, 'paragraph');

            if (!empty($data['matches'])) {

                foreach ($data['matches'] as $id => $match) {
                    if (!empty($match['attrs']['content'])) {
                        foreach($this -> getExpressions($expressionIds) as $expression) {
                            $matches[] = [
                                'id' => $id,
                                'content' => preg_replace_callback(
                                    '#' . $expression['expression'] . '#i',
                                    function($matches){return '<b>'.$matches[0].'</b>';},
                                    $match['attrs']['content']
                                ),
                            ];
                        }
                    }
                }

                $result = view('result_table', ['matches' => $matches]);
            }
        }

        if (!empty($_POST['ajax'])) {
            echo $result;
        } else {
            $patterns = DB::select('select * from `pattern`');
            $groups = DB::select('select * from `group`');
            return view('layout', ['content' => view('search', [
                'patterns' => $patterns,
                'result' => $result,
                'searchword' => $searchword,
                'groups' => $groups,
            ])]);
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
