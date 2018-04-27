<?php


namespace App\Service\Render\_Base;
	/**
	* Базовий рендерер
	*
	* @author Levko Kravets
	* @package Render
	*/
	abstract class AbstractRenderer {

		static private $languages = null;

		static protected $translationConfig = array();

		static private $text = null;

		static protected function StartTimer($name) {
//			if (render_profiling()) {
//				RenderingLog::StartTimer(':: ' . $name);
//				RenderingLog::StartTimer(get_called_class() . ' :: ' . $name);
//			}
		}

		static protected function StopTimer($name) {
//			if (render_profiling()) {
//				RenderingLog::StopTimer(get_called_class() . ' :: ' . $name);
//				RenderingLog::StopTimer(':: ' . $name);
//			}
		}

		static public function t($key, $page, $default = null, $languages = null) {
			if (is_null($languages)) {
				if (is_null(self::$languages)) {
					self::$languages = LiteDB::Read('Language', null, null, null, 'Id', 'Id');
				}
				$languages = self::$languages;
			}

			if (is_null(self::$text)) {
				$text = LiteTextManager::GetRawItems();
				if (count($text) > 0) {
					$text = reset($text);
					self::$text = $text['__translation']['Text'];
				} else {
					self::$text = array();
				}
			}

			if (!$default) $default = "/*{$key}|{$page}*/";
			$key = trim($key); $page = trim($page);
			$ui = mb_convert_case("/*{$key}|{$page}*/", MB_CASE_LOWER, 'utf-8');
			$result = array();
			foreach ($languages as $lang) {
				$result[$lang] = $default;
				if (array_key_exists($lang, self::$text)) {
					if (array_key_exists($ui, self::$text[$lang])) {
						$result[$lang] = self::$text[$lang][$ui];
					}
				}
			}
			return $result;
		}

		/**
		* Отримує переклади для вказаних екземплярів сутностей, для вказаних полів
		*
		* Кожен елемент результуючого масиву місить поля, імена яких вказані в третьому параметрі, якщо для цього поля
		* було знайдено хоча б один переклад. Тобто елементи результуючого масиву можуть мати різний набір полів,
		* але обов'язково із заданого списку. Ключами результуючого масиву є ID сутностей.
		* Передавати можна як імена полів і сутностей, так і ідентифікатори (хеші). Якщо передано імена - вони будуть
		* перетворені в хеші при використанні, але в результаті роботи будуть вказані оригінальні значення (ті, які були
		* передані в метод)
		*
		* @param string | integer Ім'я або ID сутності (таблиці)
		* @param array Масив ID сутностей
		* @param array of string | array of integer Масив імен або ID полів, які потрібно перекласти
		* @param integer ID мови
		*
		* @return array
		*/
		static protected function GetTranslations($essence, $originalRecords, $fields, $languages) {
			return RenderConfig::GetDataSourceAdapter() -> GetTranslations($essence, $originalRecords, $fields, $languages,
				static::$translationConfig);
		}

		static protected function GetConfigurations($essence, $ids = null) {
			return RenderConfig::GetDataSourceAdapter() -> GetConfigurations($essence, $ids);
		}

		/**
		* Перетворює LiteInstance в вигляд, потрібний для внесення в базу Mongo.
		*
		* @param LiteInstance
		* @return array
		*/
		static public function AsLiteInstance($instance) {
			if (!is_object($instance)) return null;
			return array(
				'Type' => get_class($instance),
				'Value' => $instance -> AsRawData(),
			);
		}

		/**
		* Перетворює масив LiteInstance в вигляд, потрібний для внесення в базу Mongo.
		*
		* @param array
		* @return array
		*/
		static public function AsLiteInstances($instances) {
			$instances = array_filter($instances, 'is_object');
			if (count($instances) <= 0) return null;

			$classes = array_keys(array_flip(array_map('get_class', $instances)));
			if (count($classes) > 1) {
				SystemLog::Warning('All instances must be of one class');
				return null;
			}
			$class = reset($classes);

			foreach ($instances as &$item) {
				$item = $item -> AsRawData();
			}
			unset($item);

			return array(
				'Type' => $class . '[]',
				'Value' => $instances,
			);
		}

		static public function RecordAsLiteInstance($record, $class = 'LiteInstance') {
			if (!is_array($record)) return null;
			return array(
				'Type' => $class,
				'Value' => $record,
			);
		}

		static public function ItemsAsLiteManager($ids, $class = 'LiteManager') {
			if (is_array($ids)) $class .= '[]';
			return array(
				'Type' => $class,
				'Value' => $ids,
			);
		}

		static public function RecordsAsLiteInstances($records, $class = 'LiteInstance') {
			if (!is_array($records)) return null;
			$records = array_filter($records, 'count');
			return array(
				'Type' => $class . '[]',
				'Value' => $records,
			);
		}

		public function RunOnlyOnce() {
			return true;
		}

		/**
		* Запуск рендерера
		*
		* @param integer Розмір однієї порції записів
		*/
		public function Render() {
		}

	}