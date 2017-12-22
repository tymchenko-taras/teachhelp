<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs;

	public function split( $ability, $arguments = []){
		exit('stop');
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

		exit('done');
	}

	public function index( $ability, $arguments = [])
	{
		$result = null;
		if(!empty($_POST['searchword'])){
			$client = new \SphinxClient();

			$result = $client -> Query($_POST['searchword'], 'paragraph');

		}

		return view('lalala', ['result' => $result, 'searchword' => $_POST['searchword']]);

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
