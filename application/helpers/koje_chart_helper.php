<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/


  class koje_chart {
    var $types = array('bar','line','radar','pie','doughnut','polarArea','bubble','mixed');

    var $colors = array(
      'red'      => 'rgb(255, 99, 132)',
    	'orange'   => 'rgb(255, 159, 64)',
    	'yellow'   => 'rgb(255, 205, 86)',
    	'green'    => 'rgb(75, 192, 192)',
    	'blue'     => 'rgb(54, 162, 235)',
    	'purple'   => 'rgb(153, 102, 255)',
    	'grey'     => 'rgb(201, 203, 207)',
      'bluedark' => 'rgb(0, 0, 255)',
      'bluelight' => 'rgb(0, 255, 255)',
      'white'    => '#ffff'
    );
    var $arrayLabel   = array();
    var $arrayDataset = array();
    var $colorOrder   = array();
    var $type = false;
    var $title = false;
    var $labelY = false;
    public function __construct() {
      $this->colorOrder = array_keys($this->colors);
    }

    public function setType($str) {
      $this->type = $str;
    }
    public function setBgColor($array=array()) {
      $this->colorOrder = $array;
    }
    public function setTitle($str) {
      $this->title = $str;
    }
    public function setLabel($array=array()) {
      $this->arrayLabel = $array;
    }
    public function setLabelY($str) {
      $this->labelY = $str;
    }
    public function setDataset($array=array()) {
      $this->arrayDataset = $array;
      if($this->type != 'line') {
        $i=0;
        foreach ($this->arrayDataset as $key => $value) {
          $bgColor = $this->colors[$this->colorOrder[$i]];
          $type = isset($this->arrayDataset[$key]['type']) ? $this->arrayDataset[$key]['type'] : 'bar';
          switch($type) {
            case 'line'   :
                  $this->arrayDataset[$key]['borderColor'] = $bgColor;
                  $this->arrayDataset[$key]['backgroundColor'] = "";
                  break;
            default       :
                $this->arrayDataset[$key]['backgroundColor'] = "chartColor('$bgColor').alpha(0.5).rgbString()";
                break;
          }
          $i++;
        }
      }
    }

    public function generateChart() {
      $title    = $this->title;
      $title = json_encode($title);
      $type     = $this->type;
      $labelY    = $this->labelY;
      if($type=='mixed') {
        $type   = 'bar';
      }
      $label    = json_encode($this->arrayLabel);
      $dataset  = json_encode($this->arrayDataset);
      $dataset  = str_replace('rgbString()"','rgbString()', str_replace('"chartColor(','chartColor(', $dataset));

      $str =
<<<EOF
        <canvas id="canvas" width='500'></canvas>
        <script>

        		window.onload = function() {
        			var ctx = document.getElementById('canvas').getContext('2d');
        			window.myBar = new Chart(ctx, {
        				type: '$type',
        				data: {
        					labels 		: $label,
        					datasets 	: $dataset
        				},
        				options: {
        					legend: {position: 'top',},
        					title : {display: true, text: $title},
                  tooltips: {
                              callbacks: {
                                  label: function(tooltipItem, data) {
                                          return stringToCurrency(tooltipItem.yLabel.toFixed(2));
                                        }
                            }
                          },
                  scales: {
                            yAxes: [
                                      {
                                          ticks: {
                                              callback: function(label, index, labels) {
                                                if(label>1000000)
                                                {
                                                  return stringToCurrency(label/1000000)+'jt';
                                                }
                                                else if(label>100000)
                                                {
                                                  return stringToCurrency(label/1000)+'rb';
                                                }
                                                else
                                                {
                                                  return stringToCurrency(label);
                                                }
                                              }
                                          },
                                          scaleLabel: {
                                              display: true,
                                              labelString: '$labelY'
                                          }
                                      }
                                  ]
                        }
        				}
        			});
        		};
         </script>
EOF;
      return $str;
    }
  }
