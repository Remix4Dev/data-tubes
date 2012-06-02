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
 * @version 0.1.0
 * @package view
 */

namespace googlecharttools\view;

use googlecharttools\model\DataTable;
use googlecharttools\exception\CodeGenerationException;

/**
 * Abstract base class for all charts supported by the Google Chart Tools.
 *
 * @package view
 */
abstract class Chart {

    /** @var string */
    private $id;

    /** @var DataTable */
    private $data;

    /** @var string */
    private $title;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /** @var string */
    private $additionalOptions;

    /**
     * Creates a new chart.
     * @param string $id
     *              The chart's ID. As this will be used as part of the chart reference
     *              names in the generated JavaScript code, the IDs have to be unique.
     * @param string $title
     *              The chart's title
     * @param int $width
     *              The chart's widht (in pixel)
     * @param int $height
     *              The chart's height (in pixel)
     */
    public function __construct($id, $title = null, $width = 700, $height = 300) {
        $this->id = $id;
        $this->setTitle($title);
        $this->setWidth($width);
        $this->setHeight($height);
    }

    /**
     * Gets the chart's ID

     * @return string
     *              The chart's ID.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets the chart's data.
     * The data will be used by the API to draw the chart.
     *
     * @param DataTable $data
     *              Data used for this chart
     */
    public function setData(DataTable $data) {
        $this->data = $data;
    }

    /**
     * Sets the chart's title.
     * The title will be displayed in above the graph.
     *
     * @param string $title
     *              The chart's title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Sets the chart's width used when the chart is displayed
     *
     * @param int $width
     *              Positive humber that represents the chart's width in pixel
     */
    public function setWidth($width) {
        if (is_numeric($width) && $width > 0) {
            $this->width = $width;
        }
    }

    /**
     * Sets the chart's height used when the chart is displayed
     *
     * @param int $height
     *              Positive humber that represents the chart's height in pixel
     */
    public function setHeight($height) {
        if (is_numeric($height) && $height > 0) {
            $this->height = $height;
        }
    }

    /**
     * Sets additional options that are currently not supported by the set-methods.
     *
     * Through this method, additional option-code can be added to the generated
     * JavaScript-code. This is usefull, when options should be set that are
     * currently not directly supported by this API (through set...() methods).
     *
     * The options have to have be in the format
     * <pre>
     * option1: value,
     * option2: value,
     * ...
     * </pre>
     *
     * <b>Example:</b>
     * <code>
     * animation:{
     *   duration: 1000,
     *   easing: 'out',
     *  },
     * vAxis: {minValue:0, maxValue:1000}
     * </code>
     *
     * @param string $options
     *              Additional options
     */
    public function setAdditionalOptions($options) {
        $this->additionalOptions = $options;
    }

    /**
     * Generates the JavaCode that will be used inside the script-element.
     *
     * The script-element is typically located inside the <head> HTML element.
     * The generated JavaScript code contains a prepare method that will set the
     * chart's data, options and chart itself. There is typically no need to call
     * this method manually. Instead, this method is called for every chart that
     * has been added to the {@link ChartManager} when
     * {@link ChartManager::getJavaScriptCode()} is called
     *
     * @return string
     *              The generated JavaScript code
     * @throws CodeGenerationException
     *              Thrown, if the JavaScript code coudn't be generated, because
     *              no data ({@link setData()}) has been assigned to the chart
     */
    public function getJavaScriptCode() {
        if ($this->data == null) {
            throw new CodeGenerationException("Cannot create JavaScript code for chart \"" . $this->id . "\": No data given");
        }

        $classname = substr(get_class($this), strlen(__NAMESPACE__) + 1);

        $code = "var " . $this->id . "Data;\n" .
                "var " . $this->id . "Options;\n" .
                "var " . $this->id . "Chart;\n\n";

        // Prepare function
        $code .= "/**\n" .
                " * Prepares the \"" . $this->id . "\" chart for usage\n" .
                " */\n" .
                "function " . $this->id . "ChartPrepare() {\n" .
                "  " . $this->id . "Data = new google.visualization.DataTable(" . $this->data->toJsonObject() . ");\n" .
                "  " . $this->id . "Options = " . $this->getJsonOptions() . ";\n" .
                "  " . $this->id . "Chart = new google.visualization." .
                $classname . "(document.getElementById(\"" . $this->id . "\"));\n" .
                "}";


        return $code;
    }

    /**
     * Genertes the options-JSON-string that will be iserted inside the JS preparation function.
     *
     * @return string
     *              The generated code
     */
    private function getJsonOptions() {
        $code = "{\n";
        if ($this->title != null) {
            $code .= "  \"title\": \"" . $this->title . "\,\n";
        }

        $code .= "    \"width\": " . $this->width . ",\n" .
                "    \"height\": " . $this->height . "";

        if ($this->additionalOptions != null && strlen($this->additionalOptions) > 0) {
            $code .= ",\n" .
                     "    " . $this->additionalOptions;
        }

        $code .= "}";
        return $code;
    }

    /**
     * Gets the HTML <div> container in which the chart will be displayed.
     *
     * @return string
     *              The HTML container
     */
    public function getHtmlContainer() {
        return "<div id=\"" . $this->id . "\" style=\"width:" . $this->width . "px; height:" . $this->height . "px;\"></div>\n";
    }

}

?>
