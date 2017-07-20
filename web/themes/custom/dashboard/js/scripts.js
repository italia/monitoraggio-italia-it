/**
 * @file
 * Dashboard sitewide Javascript logic.
 */

// ---------------------------
// UTILITY FUNCTIONS
// ---------------------------
/**
 * jQuery wrapper.
 */
(function ($, Drupal) {
  'use strict';

  // Highcharts global options.
  Highcharts.setOptions({
    lang: {
      thousandsSep: ".",
      decimalPoint: ',',
      downloadPNG: Drupal.t('Download PNG image'),
      downloadJPEG: Drupal.t('Download JPEG image'),
      downloadPDF: Drupal.t('Download PDF document'),
      downloadSVG: Drupal.t('Download SVG vector image'),
      printChart: Drupal.t('Print chart'),
      downloadCSV: Drupal.t('Download CSV'),
      downloadXLS: Drupal.t('Download XLS'),
      viewData: Drupal.t('View data table')
    }
  });

  // The following global objects will contain all charts and options.
  var charts = {},
      options = {},
      FONT_STACK = 'Titillium Web, HelveticaNeue-Light, Helvetica Neue Light, Helvetica Neue, Helvetica, Arial, Lucida Grande, sans-serif',
      DEFAULT_CHART_COLORS = ['#0066cc', '#173753', '#6daedb', '#24586d', '#efd0ca', '#e88d67', '#bad1cd', '#034732', '#8da7be', '#463f3a'];

  // Now I cycle on all monitoring indicators and print relative charts.
  $('.MonitoringIndicator').each(function () {
    printIndicatorCharts($(this).data('api'), options, charts);
  });

  /**
   * Print the current indicators charts.
   *
   * @param api_url
   *   The url to retrive JSON from.
   * @param options
   *   The object with all charts options.
   * @param charts
   *   The object containing all charts.
   */
  function printIndicatorCharts(api_url, options, charts) {
    // Retriving data from api url.
    $.get(api_url, function (indicator) {
      if (!indicator || indicator === "") {
        // error
        console.log('Error, no data from endpoint.');
        return;
      }
      // Transform string response to JSON.
      var json;
      // If not a well formatted JSON return an error.
      try {
        json = JSON.parse(indicator);
      } catch (e) {
        // error
        console.log(e);
        return;
      }

      // -----------------------------------------------------
      // Generate the target / current value comparison chart.
      // -----------------------------------------------------
      generateAndPrintTargetCharts(json, options, charts);

      // -----------------------------------------------------
      // Generate line, pie, column and map charts
      // -----------------------------------------------------

      // Now we can parse all datasets data of current indicator and plot
      // the relative chart.
      $(json.datasets).each(function (idx, dataset) {
        dataset = cleanPercentageDataSources(dataset);
        generateAndPrintIndicatorCharts(dataset, options, charts)
      });

    }, 'text');

  } // End of printIndicatorCharts function.

  /**
   * Process data removing the percentage information.
   *
   * Percentage values are in the form of an array (value / pecentage) and
   * are not directly usable in Highcharts. So we need to clean the data
   * array searching for "percentage" data, rewriting as a simple value.
   *
   * @param dataset
   * @returns {*}
   */
  function cleanPercentageDataSources(dataset) {
    var series = dataset.series;
    for (var i = 0; i < series.length; i++) {
      for (var k = 0; k < series[i].data.length; k++) {
        if (typeof (series[i].data[k]['percentage']) !== "undefined") {
          series[i].data[k] = series[i].data[k]['value'];
        }
      }
    }
    return dataset;
  }

  /**
   * Print targets charts of current indicator.
   *
   * @param json
   *   The json object with at least a target and other data.
   * @param options
   *   The object containing all options for the various charts.
   * @param charts
   *   The object containing all generated charts.
   */
  function generateAndPrintTargetCharts(json, options, charts) {
    // Do nothing if we do not have some target.
    if (typeof(json.targets) === 'undefined' || json.targets.length === 0) {
      return;
    }
    // Retrieve the date object of the last update to use the year as
    // column label.
    var last_update_dateobj = new Date(Date(json.last_update)),
        last_update_year = last_update_dateobj.getFullYear();

    // Target container.
    var $target_container = $('#monitoring_indicator-' + json.key + '-target'),
        categories_array = [Drupal.t('Actual value')],
        series_data_array = [{
          y: json.value.value,
          name: Drupal.t('Value') + ' ' + last_update_year
        }],
        suffix ='',
        $chartbox = $target_container.parents('.MonitoringIndicator').find('.ChartBox--actualValue'),
        target_height = $chartbox.innerHeight() - $chartbox.find('.ChartBox-header').innerHeight()

    // Cycle on all targets of current indicator an print the charts.
    $(json.targets).each(function (idx, target) {
      categories_array[idx+1] = [
        Drupal.t('Target') + ' ' + target.year,
      ];
      series_data_array[idx+1] = {
        y: target.value.value,
        name: Drupal.t('Target') + ' ' + target.year
      }
    })

    // Target comparison settings.
    options[json.key + '_targets'] = {
      chart: {
        height: target_height,
        renderTo: $target_container[0],
        style: {
          fontFamily: FONT_STACK
        },
        spacing: [20, 20, 20, 20],
        type: 'column'
      },
      credits: {
        enabled: false
      },
      legend: {
        enabled: false
      },
      series: [{
        colorByPoint: true,
        data: series_data_array,
        dataLabels: {
          enabled: true,
          formatter: function () {
            // Add support for % values.
            if (json.targets[0].value.percentage) {
              suffix = ' %';
            }
            return Highcharts.numberFormat(this.y, 0) + suffix;
          },
          inside: true
        },
        grouping: true,
        groupPadding: 0,
        pointPadding: 0
      }],
      title: {
        text: null // Hide the chart title.
      },
      tooltip: {
        formatter: function () {
          // Add support for % values.
          if (json.targets[0].value.percentage) {
            suffix = ' %';
          }
          return this.key + ': <b>' + Highcharts.numberFormat(this.y, 0) + suffix + '</b>';
        }
      },
      xAxis: {
        categories: categories_array,
        gridLineWidth: 0,
        labels: {
          // enabled: false,
          reserveSpace: true
        },
        lineWidth: 0,
        maxPadding: 0.2,
        minPadding: 0.2,
        offset: 0,
        startOnTick: false,
        tickWidth: 0
      },
      yAxis: {
        gridLineWidth: 0,
        maxPadding: 0,
        offset: 10,
        endOnTick: false,
        title: {
          text: ''
        }
      }
    };
    // Build the chart.
    charts[json.key + '_targets'] = new Highcharts.Chart(options[json.key + '_targets']);
  }

  /**
   * Generate and print all dataset based charts of current indicator.
   *
   * @param dataset
   *   The dataset object retrived from the JSON api.
   * @param options
   *   The options object containing all the Highcharts charts options.
   * @param charts
   *   The charts object containing all the Highcharts objects.
   */
  function generateAndPrintIndicatorCharts(dataset, options, charts) {
    // We separate charts in two groups:
    //   - non map charts
    //   - map charts
    if (!/(map|mappoint)/.test(dataset.widget.type)) {
      // We plot non map charts as first.
      // Set chart options.
      setChartSpecificOptions(options, dataset);
      // Build the charts.
      charts[dataset.key] = new Highcharts.Chart(options[dataset.key]);
    }
    else {
      // We have a map type chart.
      // Set chart options.
      setMapSpecificOptions(options, dataset);
      // Plot the maps.
      charts[dataset.key] = new Highcharts.Map(options[dataset.key]);

    }
  };

  /**
   * Scroll to the given accordion and open it.
   *
   * @param $accordion_header
   *   The jQuery object of the accordion header we want to open.
   */
  function scrollAndOpenAccordion($accordion_header) {
    $('html, body').animate({
      scrollTop: $accordion_header.offset().top
    }, 1000, function () {
      // If the accordion is collapsed, open it.
      if ($accordion_header.attr('aria-expanded') === 'false') {
        $accordion_header.trigger('click');
      }
    });
  }

  /**
   * Set options for various charts based on the chart type.
   *
   * @param options
   *   The options object
   * @param dataset
   *   The dataset object
   */
  function setChartSpecificOptions(options, dataset) {
    // Generate the columns array.
    var columns_categories = [],
      columns_categories_fullname = {},
      category;
    for (var i = 0; i < dataset.columns.length; i++) {
      if (typeof(dataset.columns[i].acronym) !== "undefined") {
        category = dataset.columns[i].acronym;
      }
      else {
        // We suppose to always have a label in the columns from the JSON.
        // Note that maps charts do not need columns.
        category = dataset.columns[i].label;
      }
      columns_categories.push(category);
      columns_categories_fullname[category] = dataset.columns[i].label;
    }

    // Common settings.
    options[dataset.key] = {
      chart: {
        description: dataset.description || dataset.title,
        renderTo: 'dataset-' + dataset.key,
        style: {
          fontFamily: FONT_STACK
        },
        spacing: [30, 20, 50, 20],
        type: dataset.widget.type
      },
      colors: DEFAULT_CHART_COLORS,
      credits: {
        enabled: false
      },
      exporting: {
        chartOptions: {
          plotOptions: {
            series: {
              dataLabels: {
                enabled: true
              }
            }
          }
        },
        filename: dataset.title,
        width: 1920,
      },
      plotOptions: {
        column: {
          groupPadding: 0.075,
          pointPadding: 0
        }
      },
      responsive: {
        rules: [
          {
            condition: {
              maxWidth: 600
            },
            chartOptions: {
              chart: {
                spacing: [30, 10, 50, 10],
                inverted: false
              },
              xAxis: {
                labels: {
                  rotation: -90
                }
              },
              yAxis: {
                labels: {
                  align: 'left',
                  x: 0,
                  y: -2
                },
                title: {
                  text: ''
                }
              }
            }
          }
        ]
      },
      series: dataset.series,
      subtitle: {
        text: dataset.description,
        verticalAlign: 'bottom'
      },
      title: {
        text: null // Hide the chart title.
      },
      tooltip: {
        formatter: function () {
          return '<b>' + dataset.title + '</b><br>' +
            this.key + ' ' + this.series.name +
            ': <b>' + Highcharts.numberFormat(this.y, 0) + '</b>';
        }
      },
      xAxis: {
        title: {
          text: Drupal.t('Month'),
          style: {
            'display': 'none' // Visible in data table only.
          },
        },
        reserveSpace: false,
        margin: 0,
        categories: columns_categories
      },
      yAxis: {
        title: {
          text: Drupal.t('Values')
        }
      }
    };

    // Here we can define type-specific chart settings.
    switch (dataset.widget.type) {
      case "line":
        // For line charts we print as visible only the last series if we have more than one.
        var num_of_series = options[dataset.key].series.length;
        if (num_of_series > 1) {
          for (var i = 0; i < (num_of_series - 1); i++) {
            options[dataset.key].series[i].visible = false;
          }
        }
        options[dataset.key].tooltip = {
          formatter: function () {
            return '<b>' + dataset.title + '</b><br>' +
              this.key + ' ' + this.series.name +
              ': <b>' + Highcharts.numberFormat(this.y, 0) + "</b>";
            ;
          }
        };
        break;

      case "pie":
        var pie_data = [];
        // Note that pie charts always have only one serie (so we do not
        // need to cycle on all series, but we can get the first and
        // only one series[0]).
        for (var k = 0; k < dataset.series[0].data.length; k++) {
          pie_data[k] = {
            name: dataset.columns[k].label,
            y: dataset.series[0].data[k],
          }
        }
        options[dataset.key].series = [
          {
            allowPointSelect: true,
            data: pie_data,
            keys: ['y'],
            name: Drupal.t('Sources')
          }
        ];
        options[dataset.key].tooltip = {
          formatter: function () {
            return dataset.title + ' - ' + this.key + ': ' + Highcharts.numberFormat(this.y, 0) +
              ' (<b>' + Math.round(this.percentage) + '% ' +
              Drupal.t('on total') + '</b>)';
          }
        };
        break;

      case "column":
        // Check if we have a "stacked" chart.
        var is_stacked = typeof(dataset.series[0].stack) !== "undefined";
        // For column charts we print as visible only the last series (year) if
        // we have more than one. For stacked columns, where we have two series
        // with the same year, we must set as visible only the last two series.
        var num_of_series = options[dataset.key].series.length;
        if (num_of_series > 1) {
          for (var i = 0; i < (num_of_series - (1 + is_stacked)); i++) {
            options[dataset.key].series[i].visible = false;
          }
        }
        // For stacked charts we need more customization.
        // Check if we have a stacked chart.
        if (is_stacked) {
          var stack = dataset.series[0].stack,
              // How many different columns we want.
              num_of_columns = dataset.series.length / 2,
              // Each element can occupy a width of 1/n, where 1 is the default width for each X value.
              pointWidth = 1 / num_of_columns,
              // The padding is the space each point (column) must leave empty for the other columns (points).
              pointPadding = (num_of_columns - 0.9) / (2 * num_of_columns),
              groupPadding = 0.05,
              pointPlacement = (1 - num_of_columns)/(2 * num_of_columns) + groupPadding/2;

          // When I have stacked series, I want them to overlap in pairs.
          // I set a minimal group padding and a pointPadding
          options[dataset.key].plotOptions.series = {
            groupPadding: groupPadding,
            pointPadding: pointPadding
          };

          options[dataset.key].plotOptions.column = {
            // stacking: 'normal'
            grouping: false,
            borderWidth: 0
          };

          options[dataset.key].tooltip = {
            formatter: function () {
              var tip = '<b>' + columns_categories_fullname[this.x] + '</b>';
              $.each(this.points, function () {
                tip += '<br/>' + this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + '</b>';
              });
              return tip;
            },
            shared: true // Shared tooltip.
          };

          // Cycle all series to set the pointPlacement since I want overlapped columns.
          for (var k = 0; k < dataset.series.length; k++) {
            // If the stack is different I need to update the stack and pointPlacement.
            if (dataset.series[k].stack !== stack) {
              stack = dataset.series[k].stack;
              pointPlacement = pointPlacement + pointWidth - groupPadding / (num_of_columns/2);
            }
            dataset.series[k].pointPlacement = pointPlacement;
          }
        }

        // We want to add special customization for the chart plotting the
        // fe_fatt_gest dataset. If we have this dataset, we want to
        // generate another custom spline chart showing % deviation.
        if ($('#dataset-fe_fatt_gest').size()) {
          generatePercentageSplineChart(options, dataset, columns_categories_fullname, Drupal.t('Percentage of waste'), Drupal.t('Percentage of waste'));
        }
        break;
    }
  }

  /**
   * Generate a spline chart for column datasets with stacked "in pair" values.
   *
   * @param options
   *   The Highchart options object.
   * @param dataset
   *   The dataset object with original data.
   * @param columns_categories_fullname
   *   The full name of the columns categories used in tooltip.
   * @param title_text
   *   String with the chart title.
   * @param tooltip_text
   *   String with the chart tooltip text.
   */
  function generatePercentageSplineChart(options, dataset, columns_categories_fullname, title_text, tooltip_text) {
    // We need to create a custom set of series plotting the % of the difference
    // from two data values of the same year using data from original graph.
    var spline_series = [];
    var number_of_series = dataset.series.length;
    // The dataset must contain a odd number of series, for each year we must
    // have two series with the values to compare.
    // If we have a even number_of_series, there is something wrong and we
    // do nothing.
    if (number_of_series % 2) {
      return;
    }
    // We have at least an odd number of series, this is not enough to say we
    // have good data, but is a nice start.
    // The series must be written in the sequence
    // Value - Value to compare - Value - Value to compare etc...
    // where each couple is relative to the same year.
    for (var i = 0; i < number_of_series; i = i + 2) {
      var data_array = [];
      // If we do not have data to check break the cycle to prevent an error.
      // This can happen when we have an empty series.
      if (typeof(dataset.series[i].data) === 'undefined') {
        continue;
      }
      for (var b = dataset.series[i].data.length, k = 0; k < b; k++) {
        data_array[k] = Math.round((dataset.series[i + 1].data[k] / dataset.series[i].data[k]) * 100);
      }
      spline_series.push(
        {
          name: title_text + ' ' + parseInt(dataset.series[i].stack),
          data: data_array,
          visible: dataset.series[i].visible
        }
      );
    }
    // We plot the graph.
    var $spline_container = $('<div></div>').attr('id', dataset.key + '_spline');
    $('#dataset-' + dataset.key).after($spline_container);

    Highcharts.chart(dataset.key + '_spline', {
      chart: {
        style: {
          fontFamily: FONT_STACK
        },
        type: 'spline',
      },
      colors: DEFAULT_CHART_COLORS,
      credits: {
        enabled: false
      },
      exporting: {
        chartOptions: {
          plotOptions: {
            series: {
              dataLabels: {
                enabled: true
              }
            }
          }
        },
        filename: dataset.title,
        width: 1920,
      },
      plotOptions: {
        spline: {
          lineWidth: 4,
          states: {
            hover: {
              lineWidth: 5
            }
          }
        }
      },
      series: spline_series,
      responsive: options[dataset.key].responsive,
      title: {
        text: tooltip_text
      },
      tooltip: {
        formatter: function () {
          var tip = '<b>' + columns_categories_fullname[this.x] + '</b>';
          $.each(this.points, function () {
            tip += '<br/>' + this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + '%</b>';
          });
          return tip;
        },
        shared: true // Shared tooltip.
        // valueSuffix: '%'
      },
      xAxis: options[dataset.key].xAxis,
      yAxis: {
        title: {
          text: Drupal.t('Value %')
        },
        minorGridLineWidth: 0,
        gridLineWidth: 0,
        alternateGridColor: null
      }
    });

  }

  /**
   * Set options for maps based on map type.
   *
   * @param options
   *   The options object
   * @param dataset
   *   The dataset object
   */
  function setMapSpecificOptions(options, dataset) {
    // Shared map chart options.
    options[dataset.key] = {
      chart: {
        description: dataset.description || dataset.title,
        renderTo: 'dataset-' + dataset.key,
        style: {
          fontFamily: FONT_STACK
        },
        spacing: [30, 20, 50, 20]
      },
      colors: DEFAULT_CHART_COLORS,
      credits: {
        enabled: false
      },
      exporting: {
        chartOptions: {
          plotOptions: {
            series: {
              dataLabels: {
                enabled: true
              }
            }
          }
        },
        filename: dataset.title,
        width: 1920,
      },
      legend: {
        enabled: false
      },
      mapNavigation: {
        buttonOptions: {
          verticalAlign: 'top'
        },
        enabled: true,
        enableDoubleClickZoom: true,
        enableMouseWheelZoom: false,
        enableTouchZoom: false
      },
      plotOptions: {
        series: {
          dataLabels: {
            enabled: false
          }
        }
      },
      series: [
        {
          // Use the it-regions map with no data as a basemap.
          name: Drupal.t('Regions of Italy'),
          keys: ['NOME_REG'],
          mapData: Highcharts.maps['countries/it/it-regions'],
          showInLegend: false,
          type: 'map',
          includeInCSVExport: false,
        }
      ],
      tooltip: {
        formatter: function () {
          return this.key;
        }
      },
      subtitle: {
        text: dataset.description,
        verticalAlign: 'bottom'
      },
      title: {
        text: null // Hide the chart title.
      }
    };

    // The we define type-specific map chart options.
    // At the moment we manage only two chart types:
    //  - map
    //  - mappoint
    switch (dataset.widget.type) {
      case "map":
        // Define the tooltip.
        options[dataset.key].tooltip = {
          formatter: function () {
            return this.point.NOME_REG;
          }
        };
        // For this kind of map we enable the legend.
        options[dataset.key].legend.enabled = true;
        // Global map type plot options.
        options[dataset.key].plotOptions.map = {
          className: 'highcharts-type-map',
          joinBy: ['NOME_REG', 'name'],
          keys: ['NOME_REG'],
          mapData: Highcharts.maps['countries/it/it-regions'],
          showInLegend: true,
        };
        // Default setting and data for the first serie.
        options[dataset.key].series[1] = {
          allAreas: false,
          data: dataset.series[0].data,
          id: 'series-1',
          keys: ['NOME_REG'],
          name: dataset.title,
        };
        options[dataset.key].xAxis = {
          // Reset categories values, since the regions names will make no sense.
          categories: ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'],
          type: 'category',
          title: {
            text: Drupal.t('Number') // Visible in data table only since we do not have axes on maps.
          }
        };
        break;

      case "mappoint":
        options[dataset.key].xAxis = {
          type: 'category',
          title: {
            text: Drupal.t('Municipality') // Visible in data table only since we do not have axes on maps.
          }
        },
        options[dataset.key].series[1] = {
          data: dataset.series[0].data,
          keys: ['lat', 'lon'],
          marker: {
            lineWidth: 0,
            radius: 5
          },
          name: dataset.title,
          type: 'mappoint'
        };
        break;
    }
  }

  /**
   * Implements the counter effect on static numbers.
   */
  Drupal.behaviors.counterUp = {
    attach: function (context, settings) {

      /**
       * Initialize counterUp on a HTML element containing a number or a % value.
       *
       * @param $item
       *   The jQuery object containing the number to animate.
       */
      var createCounterOnItem = function ($item) {
        // CounterUP options.
        var counter_up_options = {
              // delay: 10, // The delay in milliseconds per number count up
              time: 1000, // The total duration of the count up animation (milliseconds)
              'offset': 105,
              'beginAt': 0,
              'formatter': function (n) {
                return n.replace(/,/g, '.')
              }
            },
            value = $item.text(),
            is_percentage = /%/.test(value); // Add support for % values.

        if (is_percentage) {
          // Remove the % symbol from the number itself and add it as
          // formatter updating the counter options.
          value = value.replace(/%/g, '');
          // Update the formatter.
          counter_up_options.formatter = function (n) {
            return n.replace(/,/g, '.') + ' %';
          }
        }
        $item.text(value.replace(/\./g, ',')).counterUp(counter_up_options);
      }

      // The counterUp plugin does not work if a number use a . as separator for thousands.
      // We need to replace the . with a , before the counterUp plugin call.
      $('.counterUp', context).once('counter-up').each(function () {
        createCounterOnItem($(this));
      });

    }
  };

  /**
   * Manage vertical scroll and accordion element opening based on hash value.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attach the behavior logic.
   */
  Drupal.behaviors.scrollAndClick = {
    attach: function (context, settings) {

      // We do nothing if we do not have some indicators.
      if (!$('.MonitoringProject', context).size()) {
        return;
      }

      // We must manage two case:
      //  1) when we are loading a new page with an hash on the URL
      //  2) when we are clicking on the main menu on a voice of the current
      //     page (we not have a page loading effect).

      // FIRST CASE.
      // When loading a page, if we do not have an hash, we open by default
      // the first accordion and we do not scroll the page.
      if (!window.location.hash) {
        $('.Accordion-header').eq(0).trigger('click');
      }
      else {
        // Otherwise we have an hash in the url, we must open the relative
        // accordion and scroll the page to it.
        //Puts hash in variable, and removes the # character.
        var hash = window.location.hash.substring(1);
        // Get the relative accordion.
        var $accordion_header = $('#accordion-header-' + hash, context);
        $(window).on('load', function () {
          // Scroll to the relative accordion and open it.
          scrollAndOpenAccordion($accordion_header);

          // SECOND CASE.
          // Add an onClick event on menu voices in the same page to manage
          // the second case above.
          // I'm doing here, after page load event to allow megamenu build from
          // the offcanvas menu.
          $('.Megamenu').find('[href*="' + window.location.pathname + '#"]').once().click(function () {
            var clicked_menu_voice_url = $(this).attr('href');
            var clicked_menu_voice_hash = clicked_menu_voice_url.substring(clicked_menu_voice_url.indexOf('#') + 1);
            $accordion_header = $('#accordion-header-' + clicked_menu_voice_hash, context);
            scrollAndOpenAccordion($accordion_header);
          })
        });
      }

    }
  };

})(window.jQuery, window.Drupal);
