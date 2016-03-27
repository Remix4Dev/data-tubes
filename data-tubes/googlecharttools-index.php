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
 * This scipts draws a example chart from each chart listed at Google's
 * {@link https://google-developers.appspot.com/chart/interactive/docs/gallery ChartGallery}.
 *
 * This shows how to integrate the API into own pages and how to use it.
 *
 * @author Patrick Strobel
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link http://code.google.com/p/googlecharttools-php
 * @version $Revision$
 * @package default
 */
use googlecharttools\model\Cell;
use googlecharttools\model\Column;
use googlecharttools\model\DataTable;
use googlecharttools\model\Row;
use googlecharttools\view\AreaChart;
use googlecharttools\view\BarChart;
use googlecharttools\view\BubbleChart;
use googlecharttools\view\CandlestickChart;
use googlecharttools\view\ColumnChart;
use googlecharttools\view\ComboChart;
use googlecharttools\view\Gauge;
use googlecharttools\view\GeoChart;
use googlecharttools\view\LineChart;
use googlecharttools\view\PieChart;
use googlecharttools\view\ScatterChart;
use googlecharttools\view\SteppedAreaChart;
use googlecharttools\view\Table;
use googlecharttools\view\TreeMap;
use googlecharttools\view\ChartManager;
use googlecharttools\view\options\Axis;
use googlecharttools\view\options\BackgroundColor;
use googlecharttools\view\options\Bubble;
use googlecharttools\view\options\ChartArea;
use googlecharttools\view\options\ColorAxis;
use googlecharttools\view\options\Legend;
use googlecharttools\view\options\Series;
use googlecharttools\view\options\TextStyle;
use googlecharttools\view\options\Tooltip;

error_reporting(E_ALL);
require_once("./googlecharttools/ClassLoader.class.php");


// Prepare data
// ------------
// Used for area, bar and column charts
$companyData = new DataTable();
$companyData->addColumn(new Column(Column::TYPE_STRING, "y", "Year"));
$companyData->addColumn(new Column(Column::TYPE_NUMBER, "s", "Sales"));
$companyData->addColumn(new Column(Column::TYPE_NUMBER, "e", "Expenses"));

$row2004 = new Row();
$row2004->addCell(new Cell(2004));
$row2004->addCell(new Cell(1000));
$row2004->addCell(new Cell(400));
$companyData->addRow($row2004);

$row2005 = new Row();
$row2005->addCell(new Cell(2005));
$row2005->addCell(new Cell(1170));
$row2005->addCell(new Cell(460));
$companyData->addRow($row2005);

$row2006 = new Row();
$row2006->addCell(new Cell(2006));
$row2006->addCell(new Cell(660));
$row2006->addCell(new Cell(1120));
$companyData->addRow($row2006);

$row2007 = new Row();
$row2007->addCell(new Cell(2007));
$row2007->addCell(new Cell(1030));
$row2007->addCell(new Cell(540));
$companyData->addRow($row2007);

// Used for bubble chart
$correlationData = new DataTable();
$correlationData->addColumn(new Column(Column::TYPE_STRING, "i", "ID"));
$correlationData->addColumn(new Column(Column::TYPE_NUMBER, "l", "Life Expectancy"));
$correlationData->addColumn(new Column(Column::TYPE_NUMBER, "f", "Fertility Rate"));
$correlationData->addColumn(new Column(Column::TYPE_STRING, "r", "Region"));
$correlationData->addColumn(new Column(Column::TYPE_NUMBER, "p", "Population"));

$rowCan = new Row();
$rowCan->addCell(new Cell("CAN"));
$rowCan->addCell(new Cell(80.66));
$rowCan->addCell(new Cell(1.67));
$rowCan->addCell(new Cell("North America"));
$rowCan->addCell(new Cell(33739900));
$correlationData->addRow($rowCan);

$rowDeu = new Row();
$rowDeu->addCell(new Cell("DEU"));
$rowDeu->addCell(new Cell(79.84));
$rowDeu->addCell(new Cell(1.36));
$rowDeu->addCell(new Cell("Europe"));
$rowDeu->addCell(new Cell(81902307));
$correlationData->addRow($rowDeu);

$rowDnk = new Row();
$rowDnk->addCell(new Cell("DNK"));
$rowDnk->addCell(new Cell(78.6));
$rowDnk->addCell(new Cell(1.84));
$rowDnk->addCell(new Cell("Europe"));
$rowDnk->addCell(new Cell(5523095));
$correlationData->addRow($rowDnk);

$rowEgy = new Row();
$rowEgy->addCell(new Cell("EGY"));
$rowEgy->addCell(new Cell(72.73));
$rowEgy->addCell(new Cell(2.78));
$rowEgy->addCell(new Cell("Middle East"));
$rowEgy->addCell(new Cell(79716203));
$correlationData->addRow($rowEgy);

$rowGbr = new Row();
$rowGbr->addCell(new Cell("GBR"));
$rowGbr->addCell(new Cell(80.5));
$rowGbr->addCell(new Cell(2));
$rowGbr->addCell(new Cell("Europe"));
$rowGbr->addCell(new Cell(61801570));
$correlationData->addRow($rowGbr);

$rowIrn = new Row();
$rowIrn->addCell(new Cell("IRN"));
$rowIrn->addCell(new Cell(72.49));
$rowIrn->addCell(new Cell(1.7));
$rowIrn->addCell(new Cell("Middle East"));
$rowIrn->addCell(new Cell(73137148));
$correlationData->addRow($rowIrn);

$rowIrq = new Row();
$rowIrq->addCell(new Cell("IRQ"));
$rowIrq->addCell(new Cell(68.09));
$rowIrq->addCell(new Cell(4.77));
$rowIrq->addCell(new Cell("Middle East"));
$rowIrq->addCell(new Cell(31090763));
$correlationData->addRow($rowIrq);

$rowIsr = new Row();
$rowIsr->addCell(new Cell("ISR"));
$rowIsr->addCell(new Cell(81.55));
$rowIsr->addCell(new Cell(2.96));
$rowIsr->addCell(new Cell("Middle East"));
$rowIsr->addCell(new Cell(7485600));
$correlationData->addRow($rowIsr);

$rowRus = new Row();
$rowRus->addCell(new Cell("RUS"));
$rowRus->addCell(new Cell(68.6));
$rowRus->addCell(new Cell(1.54));
$rowRus->addCell(new Cell("Europe"));
$rowRus->addCell(new Cell(141850000));
$correlationData->addRow($rowRus);

$rowUsa = new Row();
$rowUsa->addCell(new Cell("USA"));
$rowUsa->addCell(new Cell(78.09));
$rowUsa->addCell(new Cell(2.05));
$rowUsa->addCell(new Cell("North America"));
$rowUsa->addCell(new Cell(307007000));
$correlationData->addRow($rowUsa);

// Used for candlestick chart
$daysData = new DataTable();
$daysData->addColumn(new Column(Column::TYPE_STRING));
$daysData->addColumn(new Column(Column::TYPE_NUMBER));
$daysData->addColumn(new Column(Column::TYPE_NUMBER));
$daysData->addColumn(new Column(Column::TYPE_NUMBER));
$daysData->addColumn(new Column(Column::TYPE_NUMBER));

$rowMon = new Row();
$rowMon->addCell(new Cell("Mon"));
$rowMon->addCell(new Cell(20));
$rowMon->addCell(new Cell(28));
$rowMon->addCell(new Cell(38));
$rowMon->addCell(new Cell(45));
$daysData->addRow($rowMon);

$rowTue = new Row();
$rowTue->addCell(new Cell("Tue"));
$rowTue->addCell(new Cell(31));
$rowTue->addCell(new Cell(38));
$rowTue->addCell(new Cell(55));
$rowTue->addCell(new Cell(66));
$daysData->addRow($rowTue);

$rowWed = new Row();
$rowWed->addCell(new Cell("Wed"));
$rowWed->addCell(new Cell(50));
$rowWed->addCell(new Cell(55));
$rowWed->addCell(new Cell(77));
$rowWed->addCell(new Cell(80));
$daysData->addRow($rowWed);

$rowThu = new Row();
$rowThu->addCell(new Cell("Thu"));
$rowThu->addCell(new Cell(77));
$rowThu->addCell(new Cell(77));
$rowThu->addCell(new Cell(66));
$rowThu->addCell(new Cell(50));
$daysData->addRow($rowThu);

$rowFri = new Row();
$rowFri->addCell(new Cell("Fri"));
$rowFri->addCell(new Cell(68));
$rowFri->addCell(new Cell(66));
$rowFri->addCell(new Cell(22));
$rowFri->addCell(new Cell(15));
$daysData->addRow($rowFri);

// Used for combo chart
$coffeeData = new DataTable();
$coffeeData->addColumn(new Column(Column::TYPE_STRING, "m", "Month"));
$coffeeData->addColumn(new Column(Column::TYPE_NUMBER, "b", "Bolivia"));
$coffeeData->addColumn(new Column(Column::TYPE_NUMBER, "e", "Ecuador"));
$coffeeData->addColumn(new Column(Column::TYPE_NUMBER, "m", "Madagascar"));
$coffeeData->addColumn(new Column(Column::TYPE_NUMBER, "p", "Papua New Guinea"));
$coffeeData->addColumn(new Column(Column::TYPE_NUMBER, "r", "Rwanda"));
$coffeeData->addColumn(new Column(Column::TYPE_NUMBER, "a", "Average"));

$row2004 = new Row();
$row2004->addCell(new Cell("2004/05"));
$row2004->addCell(new Cell(165));
$row2004->addCell(new Cell(938));
$row2004->addCell(new Cell(522));
$row2004->addCell(new Cell(998));
$row2004->addCell(new Cell(450));
$row2004->addCell(new Cell(614.6));
$coffeeData->addRow($row2004);

$row2005 = new Row();
$row2005->addCell(new Cell("2005/06"));
$row2005->addCell(new Cell(135));
$row2005->addCell(new Cell(1120));
$row2005->addCell(new Cell(599));
$row2005->addCell(new Cell(1268));
$row2005->addCell(new Cell(288));
$row2005->addCell(new Cell(682));
$coffeeData->addRow($row2005);

$row2006 = new Row();
$row2006->addCell(new Cell("2006/07"));
$row2006->addCell(new Cell(157));
$row2006->addCell(new Cell(1167));
$row2006->addCell(new Cell(587));
$row2006->addCell(new Cell(807));
$row2006->addCell(new Cell(397));
$row2006->addCell(new Cell(623));
$coffeeData->addRow($row2006);

$row2007 = new Row();
$row2007->addCell(new Cell("2007/08"));
$row2007->addCell(new Cell(139));
$row2007->addCell(new Cell(1110));
$row2007->addCell(new Cell(615));
$row2007->addCell(new Cell(968));
$row2007->addCell(new Cell(215));
$row2007->addCell(new Cell(609.4));
$coffeeData->addRow($row2007);

$row2008 = new Row();
$row2008->addCell(new Cell("2008/09"));
$row2008->addCell(new Cell(136));
$row2008->addCell(new Cell(691));
$row2008->addCell(new Cell(629));
$row2008->addCell(new Cell(1026));
$row2008->addCell(new Cell(366));
$row2008->addCell(new Cell(569.6));
$coffeeData->addRow($row2008);

// Used for gauge
$pcData = new DataTable();
$pcData->addColumn(new Column(Column::TYPE_STRING, "l", "Label"));
$pcData->addColumn(new Column(Column::TYPE_NUMBER, "v", "Value"));

$rowMem = new Row();
$rowMem->addCell(new Cell("Memory"))->addCell(new Cell(80));
$pcData->addRow($rowMem);

$rowCpu = new Row();
$rowCpu->addCell(new Cell("CPU"))->addCell(new Cell(55));
$pcData->addRow($rowCpu);

$rowNet = new Row();
$rowNet->addCell(new Cell("Network"))->addCell(new Cell(68));
$pcData->addRow($rowNet);

// Used for geo chart
$popularityData = new DataTable();
$popularityData->addColumn(new Column(Column::TYPE_STRING, "c", "Country"));
$popularityData->addColumn(new Column(Column::TYPE_NUMBER, "p", "Popularity"));

$rowGermany = new Row();
$rowGermany->addCell(new Cell("Germany"))->addCell(new Cell(200));
$popularityData->addRow($rowGermany);

$rowUs = new Row();
$rowUs->addCell(new Cell("United States"))->addCell(new Cell(300));
$popularityData->addRow($rowGermany);

$rowBrazil = new Row();
$rowBrazil->addCell(new Cell("Brazil"))->addCell(new Cell(400));
$popularityData->addRow($rowBrazil);

$rowCanada = new Row();
$rowCanada->addCell(new Cell("Canada"))->addCell(new Cell(500));
$popularityData->addRow($rowCanada);

$rowFrance = new Row();
$rowFrance->addCell(new Cell("France"))->addCell(new Cell(600));
$popularityData->addRow($rowFrance);

$rowRu = new Row();
$rowRu->addCell(new Cell("RU"))->addCell(new Cell(700));
$popularityData->addRow($rowRu);

$cityData = new DataTable();
$cityData->addColumn(new Column(Column::TYPE_STRING, "c", "City"));
$cityData->addColumn(new Column(Column::TYPE_NUMBER, "p", "Population"));
$cityData->addColumn(new Column(Column::TYPE_NUMBER, "a", "Area"));

$rowRome = new Row();
$rowRome->addCell(new Cell("Rome"))->addCell(new Cell(2761477))->addCell(new Cell(1285.31));
$cityData->addRow($rowRome);

$rowMilan = new Row();
$rowMilan->addCell(new Cell("Milan"))->addCell(new Cell(1324110))->addCell(new Cell(181.76));
$cityData->addRow($rowMilan);

$rowNaples = new Row();
$rowNaples->addCell(new Cell("Naples"))->addCell(new Cell(959574))->addCell(new Cell(117.27));
$cityData->addRow($rowNaples);

$rowTurin = new Row();
$rowTurin->addCell(new Cell("Turin"))->addCell(new Cell(907563))->addCell(new Cell(130.17));
$cityData->addRow($rowTurin);

$rowPalermo = new Row();
$rowPalermo->addCell(new Cell("Palermo"))->addCell(new Cell(655875))->addCell(new Cell(158.9));
$cityData->addRow($rowPalermo);

$rowGenoa = new Row();
$rowGenoa->addCell(new Cell("Genoa"))->addCell(new Cell(607906))->addCell(new Cell(243.60));
$cityData->addRow($rowGenoa);

$rowBologna = new Row();
$rowBologna->addCell(new Cell("Bologna"))->addCell(new Cell(380181))->addCell(new Cell(140.7));
$cityData->addRow($rowBologna);

$rowFlorence = new Row();
$rowFlorence->addCell(new Cell("Florence"))->addCell(new Cell(380181))->addCell(new Cell(140.7));
$cityData->addRow($rowFlorence);

// Used for pie chart
$activitiesData = new DataTable();
$activitiesData->addColumn(new Column(Column::TYPE_STRING, "t", "Task"));
$activitiesData->addColumn(new Column(Column::TYPE_NUMBER, "h", "Hours per Day"));

$rowWork = new Row();
$rowWork->addCell(new Cell("Work"));
$rowWork->addCell(new Cell(11));
$activitiesData->addRow($rowWork);

$rowEat = new Row();
$rowEat->addCell(new Cell("Eat"));
$rowEat->addCell(new Cell(2));
$activitiesData->addRow($rowEat);

$rowCommute = new Row();
$rowCommute->addCell(new Cell("Commute"));
$rowCommute->addCell(new Cell(2));
$activitiesData->addRow($rowCommute);

$rowWatch = new Row();
$rowWatch->addCell(new Cell("Watch TV"));
$rowWatch->addCell(new Cell(2));
$activitiesData->addRow($rowWatch);

$rowSleep = new Row();
$rowSleep->addCell(new Cell("Sleep"));
$rowSleep->addCell(new Cell(7));
$activitiesData->addRow($rowSleep);

// Used for scatter chart
$weightData = new DataTable();
$weightData->addColumn(new Column(Column::TYPE_NUMBER, "a", "Age"));
$weightData->addColumn(new Column(Column::TYPE_NUMBER, "w", "Weight"));

$row8 = new Row();
$row8->addCell(new Cell(8))->addCell(new Cell(12));
$weightData->addRow($row8);

$row4a = new Row();
$row4a->addCell(new Cell(4))->addCell(new Cell(5.5));
$weightData->addRow($row4a);

$row11 = new Row();
$row11->addCell(new Cell(11))->addCell(new Cell(14));
$weightData->addRow($row11);

$row4b = new Row();
$row4b->addCell(new Cell(4))->addCell(new Cell(5));
$weightData->addRow($row4b);

$row3 = new Row();
$row3->addCell(new Cell(3))->addCell(new Cell(3.5));
$weightData->addRow($row3);

$row6 = new Row();
$row6->addCell(new Cell(6.5))->addCell(new Cell(7));
$weightData->addRow($row6);

// Used for stepped area chart
$ratingData = new DataTable();
$ratingData->addColumn(new Column(Column::TYPE_STRING, "d", "Director (Year)"));
$ratingData->addColumn(new Column(Column::TYPE_NUMBER, "r", "Rotten Tomatoes"));
$ratingData->addColumn(new Column(Column::TYPE_NUMBER, "i", "IMDB"));

$rowHitchcock = new Row();
$rowHitchcock->addCell(new Cell("Alfred Hitchcock (1935)"));
$rowHitchcock->addCell(new Cell(8.4));
$rowHitchcock->addCell(new Cell(7.9));
$ratingData->addRow($rowHitchcock);

$rowThomas = new Row();
$rowThomas->addCell(new Cell("Ralph Thomas (1959)"));
$rowThomas->addCell(new Cell(6.9));
$rowThomas->addCell(new Cell(6.5));
$ratingData->addRow($rowThomas);

$rowSharp = new Row();
$rowSharp->addCell(new Cell("Don Sharp (1978)"));
$rowSharp->addCell(new Cell(6.5));
$rowSharp->addCell(new Cell(6.4));
$ratingData->addRow($rowSharp);

$rowHawes = new Row();
$rowHawes->addCell(new Cell("James Hawes (2008)"));
$rowHawes->addCell(new Cell(4.4));
$rowHawes->addCell(new Cell(6.2));
$ratingData->addRow($rowHawes);

// Used for table
$employeeData = new DataTable();
$employeeData->addColumn(new Column(Column::TYPE_STRING, "n", "Name"));
$employeeData->addColumn(new Column(Column::TYPE_NUMBER, "s", "Salary"));
$employeeData->addColumn(new Column(Column::TYPE_BOOLEAN, "e", "Full Time Employee"));

$rowMike = new Row();
$rowMike->addCell(new Cell("Mike"));
$rowMike->addCell(new Cell(10000, "$10,000"));
$rowMike->addCell(new Cell(true));
$employeeData->addRow($rowMike);

$rowJim = new Row();
$rowJim->addCell(new Cell("Jim"));
$rowJim->addCell(new Cell(8000, "$8,000"));
$rowJim->addCell(new Cell(false));
$employeeData->addRow($rowJim);

$rowAlice = new Row();
$rowAlice->addCell(new Cell("Alice"));
$rowAlice->addCell(new Cell(12500, "$12,500"));
$rowAlice->addCell(new Cell(true));
$employeeData->addRow($rowAlice);

$rowBob = new Row();
$rowBob->addCell(new Cell("Bob"));
$rowBob->addCell(new Cell(7000, "$7,000"));
$rowBob->addCell(new Cell(true));
$employeeData->addRow($rowBob);

// Used for tree map
$marketData = new DataTable();
$marketData->addColumn(new Column(Column::TYPE_STRING, "l", "Location"));
$marketData->addColumn(new Column(Column::TYPE_STRING, "p", "Parent"));
$marketData->addColumn(new Column(Column::TYPE_NUMBER, "s", "Market trade volume (size)"));
$marketData->addColumn(new Column(Column::TYPE_NUMBER, "c", "Market increase/decrease (color)"));

$rowGlobal = new Row();
$rowGlobal->addCell(new Cell("Global"))->addCell(new Cell(null));
$rowGlobal->addCell(new Cell(0))->addCell(new Cell(0));
$marketData->addRow($rowGlobal);

$rowAmerica = new Row();
$rowAmerica->addCell(new Cell("America"))->addCell(new Cell("Global"));
$rowAmerica->addCell(new Cell(0))->addCell(new Cell(0));
$marketData->addRow($rowAmerica);

$rowEurope = new Row();
$rowEurope->addCell(new Cell("Europe"))->addCell(new Cell("Global"));
$rowEurope->addCell(new Cell(0))->addCell(new Cell(0));
$marketData->addRow($rowEurope);

$rowAsia = new Row();
$rowAsia->addCell(new Cell("Asia"))->addCell(new Cell("Global"));
$rowAsia->addCell(new Cell(0))->addCell(new Cell(0));
$marketData->addRow($rowAsia);

$rowAustralia = new Row();
$rowAustralia->addCell(new Cell("Australia"))->addCell(new Cell("Global"));
$rowAustralia->addCell(new Cell(0))->addCell(new Cell(0));
$marketData->addRow($rowAustralia);

$rowAfrica = new Row();
$rowAfrica->addCell(new Cell("Africa"))->addCell(new Cell("Global"));
$rowAfrica->addCell(new Cell(0))->addCell(new Cell(0));
$marketData->addRow($rowAfrica);

$rowBrazil = new Row();
$rowBrazil->addCell(new Cell("Brazil"))->addCell(new Cell("America"));
$rowBrazil->addCell(new Cell(11))->addCell(new Cell(10));
$marketData->addRow($rowBrazil);

$rowUsa = new Row();
$rowUsa->addCell(new Cell("USA"))->addCell(new Cell("America"));
$rowUsa->addCell(new Cell(52))->addCell(new Cell(31));
$marketData->addRow($rowUsa);

$rowMexico = new Row();
$rowMexico->addCell(new Cell("Mexico"))->addCell(new Cell("America"));
$rowMexico->addCell(new Cell(24))->addCell(new Cell(12));
$marketData->addRow($rowMexico);

$rowCanada = new Row();
$rowCanada->addCell(new Cell("Canada"))->addCell(new Cell("America"));
$rowCanada->addCell(new Cell(16))->addCell(new Cell(-23));
$marketData->addRow($rowCanada);

$rowFrance = new Row();
$rowFrance->addCell(new Cell("France"))->addCell(new Cell("Europe"));
$rowFrance->addCell(new Cell(42))->addCell(new Cell(-11));
$marketData->addRow($rowFrance);

$rowGermany = new Row();
$rowGermany->addCell(new Cell("Germany"))->addCell(new Cell("Europe"));
$rowGermany->addCell(new Cell(31))->addCell(new Cell(-2));
$marketData->addRow($rowGermany);

$rowSweden = new Row();
$rowSweden->addCell(new Cell("Sweden"))->addCell(new Cell("Europe"));
$rowSweden->addCell(new Cell(22))->addCell(new Cell(-13));
$marketData->addRow($rowSweden);

$rowItaly = new Row();
$rowItaly->addCell(new Cell("Italy"))->addCell(new Cell("Europe"));
$rowItaly->addCell(new Cell(17))->addCell(new Cell(4));
$marketData->addRow($rowItaly);

$rowUk = new Row();
$rowUk->addCell(new Cell("UK"))->addCell(new Cell("Europe"));
$rowUk->addCell(new Cell(21))->addCell(new Cell(-5));
$marketData->addRow($rowUk);

$rowChina = new Row();
$rowChina->addCell(new Cell("China"))->addCell(new Cell("Asia"));
$rowChina->addCell(new Cell(36))->addCell(new Cell(4));
$marketData->addRow($rowChina);

$rowJapan = new Row();
$rowJapan->addCell(new Cell("Japan"))->addCell(new Cell("Asia"));
$rowJapan->addCell(new Cell(20))->addCell(new Cell(-12));
$marketData->addRow($rowJapan);

$rowIndia = new Row();
$rowIndia->addCell(new Cell("India"))->addCell(new Cell("Asia"));
$rowIndia->addCell(new Cell(40))->addCell(new Cell(63));
$marketData->addRow($rowIndia);

$rowLaos = new Row();
$rowLaos->addCell(new Cell("Laos"))->addCell(new Cell("Asia"));
$rowLaos->addCell(new Cell(4))->addCell(new Cell(34));
$marketData->addRow($rowLaos);

$rowMongolia = new Row();
$rowMongolia->addCell(new Cell("Mongolia"))->addCell(new Cell("Asia"));
$rowMongolia->addCell(new Cell(1))->addCell(new Cell(-5));
$marketData->addRow($rowMongolia);

$rowIsrael = new Row();
$rowIsrael->addCell(new Cell("Israel"))->addCell(new Cell("Asia"));
$rowIsrael->addCell(new Cell(12))->addCell(new Cell(24));
$marketData->addRow($rowIsrael);

$rowIran = new Row();
$rowIran->addCell(new Cell("Iran"))->addCell(new Cell("Asia"));
$rowIran->addCell(new Cell(18))->addCell(new Cell(13));
$marketData->addRow($rowIran);

$rowPakistan = new Row();
$rowPakistan->addCell(new Cell("Pakistan"))->addCell(new Cell("Asia"));
$rowPakistan->addCell(new Cell(11))->addCell(new Cell(-52));
$marketData->addRow($rowPakistan);

$rowEgypt = new Row();
$rowEgypt->addCell(new Cell("Egypt"))->addCell(new Cell("Africa"));
$rowEgypt->addCell(new Cell(21))->addCell(new Cell(0));
$marketData->addRow($rowEgypt);

$rowSAfrica = new Row();
$rowSAfrica->addCell(new Cell("S. Africa"))->addCell(new Cell("Africa"));
$rowSAfrica->addCell(new Cell(30))->addCell(new Cell(43));
$marketData->addRow($rowSAfrica);

$rowSudan = new Row();
$rowSudan->addCell(new Cell("Sudan"))->addCell(new Cell("Africa"));
$rowSudan->addCell(new Cell(12))->addCell(new Cell(2));
$marketData->addRow($rowSudan);

$rowCongo = new Row();
$rowCongo->addCell(new Cell("Congo"))->addCell(new Cell("Africa"));
$rowCongo->addCell(new Cell(10))->addCell(new Cell(12));
$marketData->addRow($rowCongo);

$rowZair = new Row();
$rowZair->addCell(new Cell("Zair"))->addCell(new Cell("Africa"));
$rowZair->addCell(new Cell(8))->addCell(new Cell(10));
$marketData->addRow($rowZair);


// Prepare charts
// --------------
$manager = new ChartManager();

// Default charts from the Chart Gallery
$companyYearAxis = new Axis("year", new TextStyle("red"));

$areaChart = new AreaChart("companyArea", $companyData);
$areaChart->setTitle("Company Performance");
$areaChart->setHAxis($companyYearAxis);

$barChart = new BarChart("companyBar", $companyData);
$barChart->setTitle("Company Performance");
$barChart->setHAxis($companyYearAxis);

$bubbleChart = new BubbleChart("correlationBubble", $correlationData);
$bubbleChart->setTitle("Correlation between life expectancy, fertility rate and population of some world countries (2010)");
$bubbleChart->setHAxis(new Axis("Life Expectancy"));
$bubbleChart->setVAxis(new Axis("Fertility Rate"));
$bubbleChart->setBubble(new Bubble(null, null, new TextStyle(null, null, 11)));

$candlestickChart = new CandlestickChart("daysCandlestick", $daysData);
$candlestickChart->setLegend(new Legend(Legend::POSITION_NONE));

$columnChart = new ColumnChart("companyColumn", $companyData);
$columnChart->setTitle("Company Performance");
$columnChart->setHAxis($companyYearAxis);

$comboChart = new ComboChart("coffeeCombo", $coffeeData);
$comboChart->setTitle("Monthly Coffee Production by Country");
$comboChart->setHAxis(new Axis("Month"));
$comboChart->setVAxis(new Axis("Cups"));
$comboChart->setSeriesType(ComboChart::SERIES_BARS);
$comboChart->setSeries(array(5 => new Series(Series::TYPE_LINE)));

$gauge = new Gauge("pcGauge", $pcData);
$gauge->setRedFrom(90);
$gauge->setRedTo(100);
$gauge->setYellowFrom(75);
$gauge->setYellowTo(90);
$gauge->setMinorTicks(5);

$regionsGeoChart = new GeoChart("regionGeo", $popularityData);
$markersGeoChart = new GeoChart("markersGeo", $cityData);
$markersGeoChart->setRegion("IT");
$markersGeoChart->setDisplayMode(GeoChart::MODE_MARKERS);
$markersColorAxis = new ColorAxis();
$markersColorAxis->setColors(array("green", "blue"));
$markersGeoChart->setColorAxis($markersColorAxis);

$lineChart = new LineChart("companyLine", $companyData);
$lineChart->setTitle("Company Performance");

$pieChart = new PieChart("activitiesPie", $activitiesData);
$pieChart->setTitle("My Daily Activities");

$scatterChart = new ScatterChart("weightScatter", $weightData);
$scatterChart->setTitle("Age vs. Weight comparison");
$scatterChart->setHAxis(new Axis("Age", null, 0, 15));
$scatterChart->setVAxis(new Axis("Weight", null, 0, 15));
$scatterChart->setLegend(new Legend(Legend::POSITION_NONE));

$steppedAreaChart = new SteppedAreaChart("ratingSteppedArea", $ratingData);
$steppedAreaChart->setTitle("The decline of 'The 39 Steps'");
$steppedAreaChart->setVAxis(new Axis("Accumulated Rating"));
$steppedAreaChart->setIsStacked(true);

$table = new Table("employeeTable", $employeeData);
$table->setShowRowNumber(true);

$treeMap = new TreeMap("marketTree", $marketData);
$treeMap->setMinColor("#f00");
$treeMap->setMidColor("#ddd");
$treeMap->setMaxColor("#0d0");
$treeMap->setHeaderHeight(15);
$treeMap->setFontColor("black");
$treeMap->setShowScale(true);

// Add generated chars to the chart manager
$manager->addChart($areaChart);
$manager->addChart($barChart);
$manager->addChart($bubbleChart);
$manager->addChart($candlestickChart);
$manager->addChart($columnChart);
$manager->addChart($comboChart);
$manager->addChart($gauge);
$manager->addChart($regionsGeoChart);
$manager->addChart($markersGeoChart);
$manager->addChart($lineChart);
$manager->addChart($pieChart);
$manager->addChart($scatterChart);
$manager->addChart($steppedAreaChart);
$manager->addChart($table);
$manager->addChart($treeMap);

// Customized chart
$backgroundColor = new BackgroundColor("#666", 10, "lightgrey");
$legend = new Legend(Legend::POSITION_IN, new TextStyle("blue", "arial", 12));
$tooltip = new Tooltip(true, null, new TextStyle("red", "verdana", 12));
$customizedLineChart = new LineChart("companyLineCustomized", $companyData);
$customizedLineChart->setTitle("Company Performance");
$customizedLineChart->setBackgroundColor($backgroundColor);
$customizedLineChart->setFontSize(10);
$customizedLineChart->setFontName("Times new roman");
$customizedLineChart->setColors(array("yellow", "green"));
$customizedLineChart->setLegend($legend);
$customizedLineChart->setReverseCategories(true);
$customizedLineChart->setTooltip($tooltip);
$manager->addChart($customizedLineChart);
?>
<html>
    <head>
        <title>Chart examples</title>
<?php echo $manager->getHtmlHeaderCode(); ?>
    </head>
    <body>
        <h1>Corechart examples</h1>
        <h2>Area chart</h2>
        <?php echo $areaChart->getHtmlContainer(); ?>

        <h2>Bar chart</h2>
<?php echo $barChart->getHtmlContainer(); ?>

        <h2>Bubble chart</h2>
<?php echo $bubbleChart->getHtmlContainer(); ?>

        <h2>Candlestick chart</h2>
<?php echo $candlestickChart->getHtmlContainer(); ?>

        <h2>Column chart</h2>
<?php echo $columnChart->getHtmlContainer(); ?>

        <h2>Combo chart</h2>
<?php echo $comboChart->getHtmlContainer(); ?>

        <h2>Line chart</h2>
<?php echo $lineChart->getHtmlContainer(); ?>

        <h2>Pie chart</h2>
<?php echo $pieChart->getHtmlContainer(); ?>

        <h2>Scatter chart</h2>
<?php echo $scatterChart->getHtmlContainer(); ?>

        <h2>Stepped area chart</h2>
<?php echo $steppedAreaChart->getHtmlContainer(); ?>

        <h1>Additional charts examples</h1>
        <h2>Gauge</h2>
        <?php echo $gauge->getHtmlContainer(); ?>

        <h2>Geo chart</h2>
<?php echo $regionsGeoChart->getHtmlContainer(); ?>
        <?php echo $markersGeoChart->getHtmlContainer(); ?>

        <h2>Table</h2>
        <?php echo $table->getHtmlContainer(); ?>

        <h2>Tree map</h2>
        <?php echo $treeMap->getHtmlContainer(); ?>

        <h1>Customized chart</h1>
<?php echo $customizedLineChart->getHtmlContainer(); ?>
    </body>
</html>
