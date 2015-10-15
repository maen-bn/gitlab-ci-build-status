<?php

namespace Maenbn\GitlabCiBuildStatus;

use Curl\Curl;

class Client
{
    /**
     * @var string
     */
    protected $projectUrl;

    /**
     * @var int
     */
    protected $projectId;

    /**
     * @var string
     */
    protected $projectCiToken;

    /**
     * @var string
     */
    protected $status;

    /**
     * @param $projectUrl
     * @param $projectId
     * @param $projectCiToken
     */
    public function __construct($projectUrl, $projectId, $projectCiToken)
    {
        $this->projectId = $projectId;
        $this->projectCiToken = $projectCiToken;
        $this->projectUrl = $projectUrl;
    }

    /**
     * @param string $branch
     *
     * @return mixed
     * @throws \Exception
     */
    public function getStatus($branch = 'master')
    {
        $curl = new Curl();
        $curl->get($this->projectUrl . '/api/v1/commits', [
            'project_id' => $this->projectId, 'project_token' => $this->projectCiToken
        ]);

        if ($curl->error) {
            throw new \Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
        }

        $response = $curl->response;

        $this->setStatus($response, $branch);

        return $this->status;
    }

    /**
     * @param $response
     *
     * @return array|null
     */
    protected function setStatus($response, $branch)
    {
        $branchStatuses = [];

        foreach ($response as $oCommits) {
            $id = $oCommits->id;
            $commitBranch = $oCommits->ref;
            $status = $oCommits->status;

            if (!isset($branchStatuses[$commitBranch]) ||
                (isset($branchStatuses[$commitBranch]) && $id > $branchStatuses[$commitBranch]['id'])) {
                $branchStatuses[$commitBranch] = ['id' => $id, 'status' => $status];
            }
        }

        if (! empty($branchStatuses[$branch]['status'])) {
            $this->status = $branchStatuses[$branch]['status'];
        }
    }
}
