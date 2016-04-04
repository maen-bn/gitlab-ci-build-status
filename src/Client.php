<?php

namespace Maenbn\GitlabCiBuildStatus;

use Curl\Curl;

class Client
{

    protected $projectUrl;

    protected $projectId;

    protected $privateKey;

    protected $status;

    /**
     * @param $projectUrl
     * @param $projectId
     * @param $privateKey
     */
    public function __construct($projectUrl, $projectId, $privateKey)
    {
        $this->projectUrl = $projectUrl;
        $this->projectId = $projectId;
        $this->privateKey = $privateKey;
    }

    /**
     * @param string $branch
     *
     * @return mixed
     * @throws \Exception
     */
    public function getStatus($branch = 'master')
    {

        $sha = $this->getLatestCommitSha($branch);

        $curl = new Curl();
        $curl->get($this->projectUrl . '/projects/' . $this->projectId . '/' .
            'repository/commits/' . $sha . '/statuses?private_token='. $this->privateKey);

        if ($curl->error) {
            throw new \Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
        }

        $response = $curl->response;

        $this->setStatus($response);

        return $this->status;
    }

    /**
     * @param $response
     *
     * @return array|null
     */
    protected function setStatus($response)
    {
        $this->status = 'success';

        foreach($response as $buildStatus) {
            if($buildStatus->allow_failure === false && $buildStatus->status == 'failed') {
                $this->status = 'failed';
            }
        }
    }

    /**
     * @param $branch
     * @return mixed
     * @throws \Exception
     */
    protected function getLatestCommitSha($branch)
    {
        $curl = new Curl();
        $curl->get($this->projectUrl . '/projects/' . $this->projectId . '/' .
            'repository/commits?private_token='. $this->privateKey . '&ref_name=' . $branch);

        if ($curl->error) {
            throw new \Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
        }

        $response = $curl->response;

        return $response[0]->id;
    }

}
