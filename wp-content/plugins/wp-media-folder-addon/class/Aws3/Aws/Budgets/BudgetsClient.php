<?php
namespace WP_Media_Folder\Aws\Budgets;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Budgets** service.
 * @method \WP_Media_Folder\Aws\Result createBudget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createBudgetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createNotification(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createNotificationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createSubscriber(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createSubscriberAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBudget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBudgetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteNotification(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteNotificationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteSubscriber(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteSubscriberAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeBudget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBudgetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeBudgetPerformanceHistory(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBudgetPerformanceHistoryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeBudgets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBudgetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeNotificationsForBudget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeNotificationsForBudgetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeSubscribersForNotification(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeSubscribersForNotificationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateBudget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateBudgetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateNotification(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateNotificationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateSubscriber(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateSubscriberAsync(array $args = [])
 */
class BudgetsClient extends AwsClient {}
