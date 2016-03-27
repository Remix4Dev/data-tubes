<?php

/*
 * Copyright 2012 Patrick Strobel.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @author Patrick Strobel
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link http://code.google.com/p/googlecharttools-php
 * @version $Id: Chart.class.php 29 2012-06-16 20:52:56Z patrick.strobel.swt@gmail.com $
 * @package view
 */

namespace googlecharttools\view;

use googlecharttools\model\DataTable;
use googlecharttools\view\options\OptionStorage;
use googlecharttools\exceptions\CodeGenerationException;

/**
 * Abstract base class for all charts supported by the Google Chart Tools.
 *
 * @package view
 */
abstract class Chart extends OptionStorage {

    /** @var string */
    private $id;

    /** @var DataTable */
    private $data;

    /** @var string */
    private $additionalCode = null;

    /** @var string */
    private $additionalPrepareCode = null;

    /**
     * Creates a new chart.
     *
     * @param string $id
     *              The chart's ID. As this will be used as part of the chart reference
     *              names in the generated JavaScript code, the IDs have to be unique.
     * @param DataTable $data
     *              The data that will be visualized through this chart.
     * @param int $width
     *              The chart's widht (in pixels).
     * @param int $height
     *              The chart's height (in pixels).
     */
    public function __construct($id, DataTable $data = null, $width = 700, $height = 300) {
        $this->id = $id;
        $this->data = $data;
        $this->setWidth($width);
        $this->setHeight($height);
    }

    /**
     * Gets the chart's ID.

     * @return string
     *              The chart's ID.
     */
    public function getId() {
        return $this->id;
    }

    public abstract function getPackage();

    /**
     * Sets the chart's data.
     *
     * The data will be used by the API to draw the chart.
     *
     * @param DataTable $data
     *              Data used for this chart.
     */
    public function setData(DataTable $data) {
        $this->data = $data;
    }

    /**
     * Sets the chart's width used when the chart is displayed.
     *
     * @param int $width
     *              Positive humber that represents the chart's width in pixels.
     */
    public function setWidth($width) {
        if ($width > 0) {
            $this->setOptionNumeric("width", $width);
        }
    }

    /**
     * Sets the chart's height used when the chart is displayed.
     *
     * @param int $height
     *              Positive humber that represents the chart's height in pixels.
     */
    public function setHeight($height) {
        if ($height > 0) {
            $this->setOptionNumeric("height", $height);
        }
    }

    /**
     * Adds additional code to the generated JavaScript code.
     *
     * The code will be added outside the prepare-function.
     *
     * @param string $code
     *              Additonal JavaScript code.
     */
    public function addJavaScriptCode($code) {
        $this->additionalCode = $code;
    }

    /**
     * Adds additional code to the generated JavaScript prepare-function.
     *
     * The code will be added at the end of the function.
     *
     * @param string $code
     *              Additonal JavaScript code.
     */
    public function addPrepareJavaScriptCode($code) {
        $this->additionalPrepareCode = $code;
    }

    /**
     * Generates the JavaCode that will be used inside the script-element.
     *
     * The script-element is typically located inside the <head> HTML element.
     * The generated JavaScript code contains a prepare method that will set the
     * chart's data, options and chart itself. There is typically no need to call
     * this method manually. Instead, this method is called for every chart that
     * has been added to the {@link ChartManager} when
     * {@link ChartManager::getJavaScriptCode()} is called.
     *
     * @return string
     *              The generated JavaScript code.
     * @throws CodeGenerationException
     *              Thrown, if the JavaScript code coudn't be generated, because
     *              no data ({@link setData()}) has been assigned to the chart.
     */
    public function getJavaScriptCode() {
        if ($this->data == null) {
            throw new CodeGenerationException("Cannot create JavaScript code for chart \"" . $this->id . "\": No data given");
        }

        $classname = substr(get_class($this), strlen(__NAMESPACE__) + 1);

        $code = "var " . $this->id . "Data;\n" .
                "var " . $this->id . "Options;\n" .
                "var " . $this->id . "Chart;\n\n";

        if ($this->additionalCode != null) {
            $code .= $this->additionalCode . "\n\n";
        }

        // Prepare function
        $code .= "/**\n" .
                " * Prepares the \"" . $this->id . "\" chart for usage\n" .
                " */\n" .
                "function " . $this->id . "ChartPrepare() {\n" .
                "  " . $this->id . "Data = new google.visualization.DataTable(" . $this->data->toJson() . ");\n" .
                "  " . $this->id . "Options = " . $this->encodeOptions() . ";\n" .
                "  " . $this->id . "Chart = new google.visualization." .
                $classname . "(document.getElementById(\"" . $this->id . "\"));\n";

        if ($this->additionalPrepareCode != null) {
            $code .= "\n" . $this->additionalPrepareCode . "\n";
        }

        $code .= "}";

        return $code;
    }

    /**
     * Gets the HTML <div> container in which the chart will be displayed.
     *
     * @return string
     *              The HTML container.
     */
    public function getHtmlContainer() {
        return "<div id=\"" . $this->id . "\" style=\"width:" . $this->getOption("width") .
                "px; height:" . $this->getOption("height") . "px;\"></div>\n";
    }

}

?>
