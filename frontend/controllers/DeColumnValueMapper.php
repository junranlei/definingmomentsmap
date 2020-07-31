<?php

/*
 * This file is part of the 2amigos/yii2-exportable-widget project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace frontend\controllers;

use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use dosamigos\exportable\helpers\TypeHelper;
use dosamigos\exportable\mappers\ColumnValueMapper;

class DeColumnValueMapper extends ColumnValueMapper
{
    /**
     * @var array column definitions from GridView
     */
    protected $columns = [];
    /**
     * @var array the exportable column names
     */
    protected $exportableColumns = [];
    /**
     * @var bool whether we render HTML or not
     */
    protected $isHtml;

    protected $exportType;

    /**
     * ColumnValueMapper constructor.
     *
     * @param array $columns
     * @param array $exportableColumns
     * @param bool $isHtml whether we need to render HTML or not
     */
    public function __construct(array $columns, array $exportableColumns = [], $isHtml = false,$exportType=TypeHelper::JSON)
    {
        $this->columns = $columns;
        $this->exportableColumns = $exportableColumns;
        $this->isHtml = $isHtml;
        $this->exportType = $exportType;
    }

    /**
     * Fetch data from the data provider and create the rows array
     *
     * @param mixed $model
     * @param $index
     *
     * @return array
     */
    public function map($model, $index)
    {
        $row = [];
        foreach ($this->columns as $column) {
            if ($this->isColumnExportable($column)) {
                /** @var DataColumn $column */
                $key = $model instanceof ActiveRecordInterface
                    ? $model->getPrimaryKey()
                    : $model[$column->attribute];

                $value = $this->getColumnValue($column, $model, $key, $index);

                $header = $this->getColumnHeader($column);
                $row[$header] = $value;
            }
        }

        return $row;
    }

    protected function getColumnValue($column, $model, $key, $index)
    {
        //$value = $column->renderDataCell($model, $key, $index);
        $modelA = $model->toArray();
        $attr = $column['attribute'];
        $format = isset($column['format'])?$column['format']:"";
        $hide = isset($column['hide'])?$column['hide']:[];
        if($format=='json'&&$this->exportType==TypeHelper::JSON){
            $features=$model->{$attr};         
            $row=[];      
            //ture json into sub row           
            foreach($features as $feature){
                $row=$feature->toArray();
                //unset hidden sub column
                foreach($hide as $h){
                    if($feature->hasAttribute($h)){
                        unset($row[$h]);
                    }
                }
                if($feature->hasAttribute("geojson")){
                    $row['geojson']=json_decode($feature->geojson, true);
                }
                //array_push($featureA,$feature->toArray());
            }
            $value = $row;

        }else if($format=='json'&&($this->exportType==TypeHelper::CSV||$this->exportType==TypeHelper::TXT)){
            $features=$model->{$attr};         
            $featureA=[];      
            //turn sub object array into string   
            $value="";        
            foreach($features as $feature){               
                //array_push($featureA,$feature->toArray());
                $f = $feature->toArray();
                foreach($f as $key=>$val){
                    // hide sub column             
                    if(!in_array($key,$hide)){
                        $value = $value.$key.":".$val." ";
                    }
                    
                }

            }
            //$value = json_encode($featureA);
        }else
            $value=$model->{$attr};
        //if (!$this->isHtml) {
            //$value = strip_tags($value);
        //}

        return $value;
    }

    /**
     * Returns column headers
     *
     * @param $model
     *
     * @return array
     */
    public function getHeaders($model)
    {
        $headers = [];
        /** @var Column $column */
        foreach ($this->columns as $column) {
            if ($this->isColumnExportable($column)) {
                $headers[] = $this->getColumnHeader($column);
            }
        }

        return $headers;
    }

    /**
     * Checks whether the column is exportable or not
     *
     * @param Column $column
     *
     * @return bool
     */
    protected function isColumnExportable($column)
    {
        /*if ($column instanceof ActionColumn || $column instanceof CheckboxColumn) {
            return false;
        }*/

        if (empty($this->exportableColumns)) {
            return true;
        }

        return in_array($column->attribute, $this->exportableColumns);
    }

    /**
     * Gets columns header
     *
     * @param $column
     * @param $model
     *
     * @return string
     */
    protected function getColumnHeader($column)
    {
        $header = $column['label'];
        //if (!$this->isHtml) {
            //$header = strip_tags($header);
        //}

        return $header;
    }
}
