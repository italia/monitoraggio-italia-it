/**
 * @file
 * Configuration file for file for pace.js library
 *
 * @see dashboard.libraries.yml for library definition.
 */

/**
 * Available config options and relative default value:
 *
 *
 *  catchupTime: 100,
 *  initialRate: .03,
 *  minTime: 250,
 *  ghostTime: 100,
 *  maxProgressPerFrame: 20,
 *  easeFactor: 1.25,
 *  startOnPageLoad: true,
 *  restartOnPushState: true,
 *  restartOnRequestAfter: 500,
 *  target: 'body',
 *  elements: {
 *    checkInterval: 100,
 *    selectors: ['body']
 *  },
 *  eventLag: {
 *    minSamples: 10,
 *    sampleCount: 3,
 *    lagThreshold: 3
 *  },
 *  ajax: {
 *    trackMethods: ['GET'],
 *    trackWebSockets: true,
 *    ignoreURLs: []
 *  }
 *
 */
paceOptions = {
  target: '.MonitoringProject'
}
