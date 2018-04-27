<?php
/**
 * Created by PhpStorm.
 * User: ttymchenko
 * Date: 27.04.2018
 * Time: 14:07
 */

namespace App\Service;

class Sentence {

    public static function getExpressions($ids = []){
        $parts = [
            'have-has' => "(have|has|'ve|hasn't|haven't|have not|has not)",
            'indefinite-adverbs' => '(always|usually|often|sometimes|rarely|never)',
            'regular-continuous-verb' => '(?!\b(wing|king|interesting|something|spring|hawking|thing|anything|everything|nothing|ceiling|building|dressing|dwelling|feeling|filling|longing|meaning|morning|evening|pudding|shilling|wedding)\b)(\w+ing)',
            'todo' => '(greeting|meeting|landing|opening|clearing|painting|saying|singing|swimming|suffering|warning|writing|hardening)',
            'irregular_past_verb' => '\b(\w+ed|arose|awakened|awoke|backslid|was|were|bore|beat|became|began|bent|bet|betted|bid|bade|bid|bound|bit|bled|blew|broke|bred|brought|broadcast|broadcasted|browbeat|built|burned|burnt|burst|busted|bust|bought|cast|caught|chose|clung|clothed|clad|came|cost|crept|crossbred|cut|daydreamed|daydreamt|dealt|dug|disproved|dove|dived|dived|dove|did|drew|dreamed|dreamt|drank|drove|dwelt|dwelled|ate|fell|fed|felt|fought|found|fitted|fit|fit|fitted|fled|flung|flew|forbade|forecast|forewent|foresaw|foretold|forgot|forgave|forsook|froze|frostbit|got|gave|went|ground|grew|hand-fed|handwrote|hung|had|heard|hewed|hid|hit|held|hurt|inbred|inlaid|input|inputted|interbred|interwove|interweaved|interwound|jerry-built|kept|knelt|kneeled|knitted|knit|knew|laid|led|leaned|leant|leaped|leapt|learned|learnt|left|lent|let|lay|lied|lit|lighted|lip-read|lost|made|meant|met|miscast|misdealt|misdid|misheard|mislaid|misled|mislearned|mislearnt|misread|misset|misspoke|misspelled|misspelt|misspent|mistook|mistaught|misunderstood|miswrote|mowed|offset|outbid|outbred|outdid|outdrew|outdrank|outdrove|outfought|outflew|outgrew|outleaped|outleapt|outlied|outrode|outran|outsold|outshined|outshone|outshot|outsang|outsat|outslept|outsmelled|outsmelt|outspoke|outsped|outspent|outswore|outswam|outthought|outthrew|outwrote|overbid|overbred|overbuilt|overbought|overcame|overdid|overdrew|overdrank|overate|overfed|overhung|overheard|overlaid|overpaid|overrode|overran|oversaw|oversold|oversewed|overshot|overslept|overspoke|overspent|overspilled|overspilt|overtook|overthought|overthrew|overwound|overwrote|partook|paid|pleaded|pled|prebuilt|predid|premade|prepaid|presold|preset|preshrank|proofread|proved|put|quick-froze|quit|quitted|read|reawoke|rebid|rebound|rebroadcast|rebroadcasted|rebuilt|recast|recut|redealt|redid|redrew|refit|refitted|refitted|refit|reground|regrew|rehung|reheard|reknitted|reknit|relaid|relayed|relearned|relearnt|relit|relighted|remade|repaid|reread|reran|resold|resent|reset|resewed|retook|retaught|retore|retold|rethought|retread|retrofitted|retrofit|rewoke|rewaked|rewore|rewove|reweaved|rewed|rewedded|rewet|rewetted|rewon|rewound|rewrote|rid|rode|rang|rose|roughcast|ran|sand-cast|sawed|said|saw|sought|sold|sent|set|sewed|shook|shaved|sheared|shed|shined|shone|shit|shat|shitted|shot|showed|shrank|shrunk|shut|sight-read|sang|sank|sunk|sat|slew|slayed|slayed|slept|slid|slung|slinked|slunk|slit|smelled|smelt|sneaked|snuck|sowed|spoke|sped|speeded|spelled|spelt|spent|spilled|spilt|spun|spit|spat|split|spoiled|spoilt|spoon-fed|spread|sprang|sprung|stood|stole|stuck|stung|stunk|stank|strewed|strode|struck|struck|strung|strove|strived|sublet|sunburned|sunburnt|swore|sweat|sweated|swept|swelled|swam|swung|took|taught|tore|telecast|told|test-drove|test-flew|thought|threw|thrust|trod|typecast|typeset|typewrote|unbent|unbound|unclothed|unclad|underbid|undercut|underfed|underwent|underlay|undersold|underspent|understood|undertook|underwrote|undid|unfroze|unhung|unhid|unknitted|unknit|unlearned|unlearnt|unsewed|unslung|unspun|unstuck|unstrung|unwove|unweaved|unwound|upheld|upset|woke|waked|waylaid|wore|wove|weaved|wed|wedded|wept|wet|wetted|whetted|won|wound|withdrew|withheld|withstood|wrung|wrote)\b',
            'irregular_participle_verb' => '\b(\w+ed|arisen|awakened|awoken|backslidden|backslid|been|born|borne|beaten|beat|become|begun|bent|bet|betted|bidden|bid|bound|bitten|bled|blown|broken|bred|brought|broadcast|broadcasted|browbeaten|browbeat|built|burned|burnt|burst|busted|bust|bought|cast|caught|chosen|clung|clothed|clad|come|cost|crept|crossbred|cut|daydreamed|daydreamt|dealt|dug|disproved|disproven|dived|dived|done|drawn|dreamed|dreamt|drunk|driven|dwelt|dwelled|eaten|fallen|fed|felt|fought|found|fitted|fit|fit|fitted|fled|flung|flown|forbidden|forecast|foregone|foreseen|foretold|forgotten|forgot|forgiven|forsaken|frozen|frostbitten|gotten|got|given|gone|ground|grown|hand-fed|handwritten|hung|had|heard|hewn|hewed|hidden|hit|held|hurt|inbred|inlaid|input|inputted|interbred|interwoven|interweaved|interwound|jerry-built|kept|knelt|kneeled|knitted|knit|known|laid|led|leaned|leant|leaped|leapt|learned|learnt|left|lent|let|lain|lied|lit|lighted|lip-read|lost|made|meant|met|miscast|misdealt|misdone|misheard|mislaid|misled|mislearned|mislearnt|misread|misset|misspoken|misspelled|misspelt|misspent|mistaken|mistaught|misunderstood|miswritten|mowed|mown|offset|outbid|outbred|outdone|outdrawn|outdrunk|outdriven|outfought|outflown|outgrown|outleaped|outleapt|outlied|outridden|outrun|outsold|outshined|outshone|outshot|outsung|outsat|outslept|outsmelled|outsmelt|outspoken|outsped|outspent|outsworn|outswum|outthought|outthrown|outwritten|overbid|overbred|overbuilt|overbought|overcome|overdone|overdrawn|overdrunk|overeaten|overfed|overhung|overheard|overlaid|overpaid|overridden|overrun|overseen|oversold|oversewn|oversewed|overshot|overslept|overspoken|overspent|overspilled|overspilt|overtaken|overthought|overthrown|overwound|overwritten|partaken|paid|pleaded|pled|prebuilt|predone|premade|prepaid|presold|preset|preshrunk|proofread|proven|proved|put|quick-frozen|quit|quitted|read|reawaken|rebid|rebound|rebroadcast|rebroadcasted|rebuilt|recast|recut|redealt|redone|redrawn|refit|refitted|refitted|refit|reground|regrown|rehung|reheard|reknitted|reknit|relaid|relayed|relearned|relearnt|relit|relighted|remade|repaid|reread|rerun|resold|resent|reset|resewn|resewed|retaken|retaught|retorn|retold|rethought|retread|retrofitted|retrofit|rewaken|rewaked|reworn|rewoven|reweaved|rewed|rewedded|rewet|rewetted|rewon|rewound|rewritten|rid|ridden|rung|risen|roughcast|run|sand-cast|sawed|sawn|said|seen|sought|sold|sent|set|sewn|sewed|shaken|shaved|shaven|sheared|shorn|shed|shined|shone|shit|shat|shitted|shot|shown|showed|shrunk|shut|sight-read|sung|sunk|sat|slain|slayed|slayed|slept|slid|slung|slinked|slunk|slit|smelled|smelt|sneaked|snuck|sown|sowed|spoken|sped|speeded|spelled|spelt|spent|spilled|spilt|spun|spit|spat|split|spoiled|spoilt|spoon-fed|spread|sprung|stood|stolen|stuck|stung|stunk|strewn|strewed|stridden|stricken|struck|stricken|strung|striven|strived|sublet|sunburned|sunburnt|sworn|sweat|sweated|swept|swollen|swelled|swum|swung|taken|taught|torn|telecast|told|test-driven|test-flown|thought|thrown|thrust|trodden|trod|typecast|typeset|typewritten|unbent|unbound|unclothed|unclad|underbid|undercut|underfed|undergone|underlain|undersold|underspent|understood|undertaken|underwritten|undone|unfrozen|unhung|unhidden|unknitted|unknit|unlearned|unlearnt|unsewn|unsewed|unslung|unspun|unstuck|unstrung|unwoven|unweaved|unwound|upheld|upset|woken|waked|waylaid|worn|woven|weaved|wed|wedded|wept|wet|wetted|whetted|won|wound|withdrawn|withheld|withstood|wrung|written)\b',
        ];
// після while та when це partciple
        //перед participle та gerund не буде артикля, артикль неред герундієм не ставиться
        // some any this my his (pronoun) - перед герундієм не ставиться
        $expressions = [
            [
                'id' => 1,
                'name' => 'Gerund plus be',
                'expression' => implode('', [
                    "\b{$parts['regular-continuous-verb']}\b",
                    "(?!.*\b(to|us|at|as|it|\.|,|!|when).*\b)(.{0,20})",
                    "\s+\b(is|was|has been|will be)\b"
                ]),
            ],
            [
                'id' => 2,
                'name' => 'present perf contin',
                'expression' => implode('', [
                    "{$parts['have-has']}",
                    "(?=.*\b{$parts['indefinite-adverbs']}.*\b)(.{0,20})",
                    "\s+\bbeen\b",
                    "\s+\b{$parts['regular-continuous-verb']}\b",
                ]),
            ],
            [
                'id' => 3,
                'name' => 'present perf contin',
                'expression' => implode('', [
                    "(?!\b(to)\b){$parts['have-has']}",         //"have", but not "have to"
                    "(\s+{$parts['indefinite-adverbs']}\s)?",   // ever, never
                    "(?!\s+been\s\w+ing)\s+\b{$parts['irregular_participle_verb']}\b",  // participle_verb but not "been doing", however been is participle_verb as well
                ]),
            ],
        ];


        return $expressions;
    }
}
