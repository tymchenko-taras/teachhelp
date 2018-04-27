<?php

namespace App\Service\Render\_Base;
/**
 * Created by PhpStorm.
 * User: ttymchenko
 * Date: 26.04.2018
 * Time: 19:27
 */

	/**
     * Рендерер набору даних (довільного)
     *
     * @author Levko Kravets
     * @package Render
     */
	abstract class Recordset extends AbstractRenderer {

        protected $processBatchSize = 1000;
        protected $saveBatchSize = 1000;

        /**
         * Викликається перед роботою. В цьому методі можна здійснити підготовчі операції
         */
        protected function Prepare() {
        }

        /**
         * Отримання ID записів. Також тут можна створити тимчасові таблиці чи інші потрібні структури
         *
         *	@returns array Масив ID
         */
        public function GetIds() {
            return array();
        }

        /**
         * Отримує масив записів з набором полів за замовчуванням. Записи при потребі доповнюютсья іншим методом
         *
         * @param array ID записів
         * @return array Масив записів
         */
        protected function DefaultRender($ids) {
            $result = array();
            foreach ($ids as $id) {
                $result[$id] = array(
                    'id' => $id,
                );
            }
            return $result;
        }

        /**
         * Доповнення записів нестандартними полями
         *
         * @param array Масив записів
         */
        protected function CustomRender(&$records) {
        }

        /**
         * Зберігає сформиований масив записів
         *
         * @param array Масив записів
         */
        protected function Save($records) {
        }


        /**
         * Створення індексів для даних
         */
        protected function BuildIndexes() {
        }

        /**
         * Цей метод викликається в кінці роботи. Можна здійснити очистку тимчасових ресурсів, побудувати індекси та ін.
         * В метод передаються всі ID, отримані з getIds()
         *
         * @param array Всі ID, отримані з ::getIds()
         */
        protected function Cleanup($ids) {
            static::StartTimer('BuildIndexes');
            $this -> BuildIndexes();
            static::StopTimer('BuildIndexes');
        }

        /**
         * Виконує рендеринг записів
         *
         * @param integer Розмір однієї порції записів
         */
        public function Render() {
            static::StartTimer('Total');

            static::StartTimer('Prepare');
            $this -> Prepare();
            static::StopTimer('Prepare');

            static::StartTimer('GetIds');
            $ids = $this -> GetIds();
            static::StopTimer('GetIds');

            if ($this -> processBatchSize > 0) {
                $batches = array_chunk($ids, $this -> processBatchSize, false);
            } else {
                $batches = array($ids);
            }

            foreach ($batches as $batch) {
                static::StartTimer('DefaultRender');
                $records = $this -> DefaultRender($batch);
                static::StopTimer('DefaultRender');

                static::StartTimer('CustomRender');
                $this -> CustomRender($records);
                static::StopTimer('CustomRender');

                static::StartTimer('Save');
                if ($this -> saveBatchSize > 0) {
                    $chunks = array_chunk($records, $this -> saveBatchSize, true);
                    foreach ($chunks as $chunk) {
                        $this -> Save($chunk);
                    }
                } else {
                    $this -> Save($records);
                }
                static::StopTimer('Save');
            }

            static::StartTimer('Cleanup');
            $this -> Cleanup($ids);
            static::StopTimer('Cleanup');

            static::StopTimer('Total');
        }

    }
