<?php
    class FilterConfigurator {
        //PROPERTIES
        private static $instance = null;
        public static $availableFieldsForSelect = null;

        //CONSTRUCTORS
        private function __construct() {
        }

        public static function getInstance() {
            if(self::$instance == null) {
                self::$instance = new FilterConfigurator();
            }
            return self::$instance;
        }


        /**
         * @return all filters names
         */
        public function getFiltersTitles() {
            if(self::$availableFieldsForSelect ==  null) {
                self::$availableFieldsForSelect = self::makeQueryForAvailableFiltersData(array());
            }
            return self::$availableFieldsForSelect;
        }

        /**
         * @param $filtersArray current applied filters
         * @return the same @param $filtersArray, but with the allPossible values populated for each filter
         */
        public function configureFilters($filtersArray) {
            //var_dump($filtersArray);
            $filtersMap = self::mapFilters($filtersArray);

            $availableFiltersMap = self::makeQueryForAvailableFiltersData($filtersMap);

            foreach($filtersArray as $filterObj) {
                if($filterObj -> id != null) {

                    $filterObj -> allPossibleValues = $availableFiltersMap[$filterObj -> pointingToCategory];
                }
            }
            return $filtersArray;
        }

        /**
         * @param $filtersArray current applied filters
         * @return a map having the filters groupped for query (aka prepares filters for query)
        */
        public function mapFilters($filtersArray) {
            $filtersOptions = array();
                foreach($filtersArray as $filterId => $filterObj) {
                    if($filterObj -> currentSelectedValue != "any" && $filterObj -> id != null && $filterObj -> isValid == true) {
                        if($filterObj -> isInterval && $filterObj -> otherIntervalId != null) {
                            if(!isset($filtersOptions["intervals"])) {
                                $filtersOptions["intervals"] = array();
                            }
                            
                            if(!isset($filtersOptions["intervals"][$filterObj -> pointingToCategory])) {
                                $filtersOptions["intervals"][$filterObj -> pointingToCategory] = array();
                            }
                            array_push($filtersOptions["intervals"][$filterObj -> pointingToCategory], $filterObj -> currentSelectedValue);
                            array_push($filtersOptions["intervals"][$filterObj -> pointingToCategory], $filtersArray[$filterObj -> otherIntervalId] -> currentSelectedValue);
                        } else if(!$filterObj -> isInterval) {
                                if(!isset($filtersOptions[$filterObj -> pointingToCategory])) {
                                    $filtersOptions[$filterObj -> pointingToCategory] = array();
                                }
                                array_push($filtersOptions[$filterObj -> pointingToCategory],$filterObj -> currentSelectedValue);
                        }
                }
            }
            return $filtersOptions;
        }

        
        /**
         * Perform query to api with filters 
         */
        public function makeQueryForAvailableFiltersData($withFilterMap) {
            $availableFiltersValues = null;
            $availableFiltersMapByCategories = array();
            if(empty($withFilterMap)) {
                $jsonString = file_get_contents("http://localhost/attacks/availablefilters/all");
                urldecode($jsonString);
                $availableFiltersValues = json_decode($jsonString,true);
            } else {
                $filtersJson = json_encode($withFilterMap);
                $post_options = array(
                    'http' => array(
                        'method' => 'POST',
                        'content' => $filtersJson,
                        'header'=>  "Content-Type: application/json\r\n"
                    )
                    );
                $context  = stream_context_create( $post_options );
                $jsonString = file_get_contents( "http://localhost/attacks/availablefilters", false, $context );
                $availableFiltersValues = json_decode( $jsonString, true );
            }
            foreach($availableFiltersValues as $column => $valuesArray) {
                if(!isset($availableFiltersMapByCategories[$column])) {
                    $availableFiltersMapByCategories[$column] = array();
                }
                foreach($valuesArray as $filterValue => $rand_data) {
                    array_push($availableFiltersMapByCategories[$column], $filterValue);
                }
            }
            return $availableFiltersMapByCategories;
        } 
     }
?>
