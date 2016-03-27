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
 * @version $Id: ChartManager.class.php 29 2012-06-16 20:52:56Z patrick.strobel.swt@gmail.com $
 * @package default
 */

namespace googlecharttools\view;

/**
 * The ChartManager manages all charts that will be displayed on the page.
 *
 * The ChartManager is responsible for the generation of the correct header-code
 * that has to be inserte into the page's <head> element. The purpose
 * of this code is to contact the Google server to fetch all required, external
 * JavaScript code used to render the charts. It also contains code, that will
 * assign the data and options to the charts and to create the chart themselfs.
 *
 * @package view
 */
class ChartManager {

    /** @var Chart[] */
    private $charts = array();

    /** @var string */
    private $additionalCodeBegin = null;

    /** @var string */
    private $additionalCodeEnd = null;

    /**
     * Adds another chart to the manager.
     *
     * All charts that should be displayed on the page have to be added to the manager.
     * Please make sure, that all chart have their own, unique ID. If a chart is added
     * having the same ID as a chart that has been added before, the "older" chart will be
     * overwritten.
     *
     * @param Chart $chart
     *              Chart, that will be displayed on the page.
     */
    public function addChart(Chart $chart) {
        $this->charts[$chart->getId()] = $chart;
    }

    /**
     * Adds additional code at the beginning of the generated JavaScript
     * onLoad-function.
     *
     * @param string $code
     *              Additonal JavaScript code.
     */
    public function addOnLoadJavaScriptCodeBegin($code) {
        $this->additionalCodeBegin = $code;
    }

    /**
     * Adds additional code at the end of the generated JavaScript
     * onLoad-function.
     *
     * @param string $code
     *              Additonal JavaScript code.
     */
    public function addOnLoadJavaScriptCodeEnd($code) {
        $this->additionalCodeEnd = $code;
    }

    /**
     * Genarates the JavaScript code that will prepare all charts.
     *
     * This method will generate a preparation JavaScript-function for all charts
     * that have been added. This functions assign the chart's data, options and the
     * charts themselve to a global variable.
     * The generated code doesn't contain the required code to load the
     * packages from Google's server but a callback function that prepares and
     * displayes all charts automatically as soon as all packages have been loaded.
     *
     * @return string
     *              The generated JavaScript code.
     * @throws CodeGenerationException
     *              Thrown, if the JavaScript code coudn't be generated, because
     *              no data ({@link Chart::setData()}) has been assigned to at
     *              least one chart.
     */
    public function getJavaScriptCode() {
        $packages = array();

        // Add additional code at the beginning of the onLoad-function
        if ($this->additionalCodeBegin != null) {
            $chartsOnLoadCode = $this->additionalCodeBegin . "\n\n";
        } else {
            $chartsOnLoadCode = "";
        }

        $chartsPrepareCode = "";

        foreach ($this->charts as $chart) {

            // Build package array
            $quotedPackage = "\"" . $chart->getPackage() . "\"";
            if (!in_array($quotedPackage, $packages)) {
                $packages[] = $quotedPackage;
            }

            // Code for the chartsOnLoad() function
            $id = $chart->getId();
            $chartsOnLoadCode .= "  " . $id . "ChartPrepare();\n";
            $chartsOnLoadCode .= "  " . $id . "Chart.draw(" . $id . "Data, " . $id . "Options);\n";

            // Code for the chart's preparation function
            // -----------------------------------------
            $chartsPrepareCode .= $chart->getJavaScriptCode() . "\n\n";
        }

        // Add additional code at the end of the onLoad-function
        if ($this->additionalCodeEnd != null) {
            $chartsOnLoadCode .= "\n" . $this->additionalCodeEnd . "\n";
        }

        // Build code
        return "google.load(\"visualization\", \"1\", {packages:[" . implode(",", $packages) . "]});\n" .
                "google.setOnLoadCallback(chartsOnLoad);\n\n" .
                "/**\n" .
                " * Callback function used to prepare and automatically draw the charts\n" .
                " */\n" .
                "function chartsOnLoad() {\n" .
                $chartsOnLoadCode .
                "}\n\n" .
                "// Chart preparation code\n" .
                "// ----------------------\n\n" .
                $chartsPrepareCode;
    }

    /**
     * Encapsulates the code generated by {@link getJavaScriptCode()} in a valid HTML-code.
     *
     * The generated code is encapsulated in a <script> element.
     *
     * @param boolean $loadJsapi
     *              If set to true, a <script> element that loads Google's
     *              JSAPI will be included in the first code-line, too.
     * @return string
     *              The generated HTML-/JavaScript code. This can be directly
     *              included in the HTML-page's <head> element.
     * @throws CodeGenerationException
     *              Thrown, if the JavaScript code coudn't be generated, because
     *              no data ({@link Chart::setData()}) has been assigned to at
     *              least one chart.
     */
    public function getHtmlHeaderCode($loadJsapi = true) {
        if ($loadJsapi) {
            $code = "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>\n";
        } else {
            $code = "";
        }
        $code .= "<script type=\"text/javascript\">\n";
        $code .= $this->getJavaScriptCode();
        $code .= "</script>\n";
        return $code;
    }

}

?>
