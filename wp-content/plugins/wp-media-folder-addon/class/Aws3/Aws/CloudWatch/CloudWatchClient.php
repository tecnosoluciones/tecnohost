<?php
namespace WP_Media_Folder\Aws\CloudWatch;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon CloudWatch** service.
 *
 * @method \WP_Media_Folder\Aws\Result deleteAlarms(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteAlarmsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteDashboards(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDashboardsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAlarmHistory(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAlarmHistoryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAlarms(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAlarmsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAlarmsForMetric(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAlarmsForMetricAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disableAlarmActions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disableAlarmActionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result enableAlarmActions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise enableAlarmActionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getDashboard(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getDashboardAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getMetricData(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getMetricDataAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getMetricStatistics(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getMetricStatisticsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getMetricWidgetImage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getMetricWidgetImageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listDashboards(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listDashboardsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listMetrics(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listMetricsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putDashboard(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putDashboardAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putMetricAlarm(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putMetricAlarmAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putMetricData(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putMetricDataAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setAlarmState(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setAlarmStateAsync(array $args = [])
 */
class CloudWatchClient extends AwsClient {}
