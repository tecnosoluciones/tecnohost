<?php
namespace WP_Media_Folder\Aws\MachineLearning;

use WP_Media_Folder\Aws\AwsClient;
use WP_Media_Folder\Aws\CommandInterface;
use WP_Media_Folder\GuzzleHttp\Psr7\Uri;
use WP_Media_Folder\Psr\Http\Message\RequestInterface;

/**
 * Amazon Machine Learning client.
 *
 * @method \WP_Media_Folder\Aws\Result addTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createBatchPrediction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createBatchPredictionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createDataSourceFromRDS(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDataSourceFromRDSAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createDataSourceFromRedshift(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDataSourceFromRedshiftAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createDataSourceFromS3(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDataSourceFromS3Async(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createEvaluation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createEvaluationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createMLModel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createMLModelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createRealtimeEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createRealtimeEndpointAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBatchPrediction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBatchPredictionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteDataSource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDataSourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteEvaluation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteEvaluationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteMLModel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteMLModelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteRealtimeEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteRealtimeEndpointAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeBatchPredictions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBatchPredictionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDataSources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDataSourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEvaluations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEvaluationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeMLModels(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeMLModelsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBatchPrediction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBatchPredictionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getDataSource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getDataSourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getEvaluation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getEvaluationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getMLModel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getMLModelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result predict(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise predictAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateBatchPrediction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateBatchPredictionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateDataSource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateDataSourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateEvaluation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateEvaluationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateMLModel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateMLModelAsync(array $args = [])
 */
class MachineLearningClient extends AwsClient
{
    public function __construct(array $config)
    {
        parent::__construct($config);
        $list = $this->getHandlerList();
        $list->appendBuild($this->predictEndpoint(), 'ml.predict_endpoint');
    }

    /**
     * Changes the endpoint of the Predict operation to the provided endpoint.
     *
     * @return callable
     */
    private function predictEndpoint()
    {
        return static function (callable $handler) {
            return function (
                CommandInterface $command,
                RequestInterface $request = null
            ) use ($handler) {
                if ($command->getName() === 'Predict') {
                    $request = $request->withUri(new Uri($command['PredictEndpoint']));
                }
                return $handler($command, $request);
            };
        };
    }
}
