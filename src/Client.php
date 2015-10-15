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
        $curl->get($this->projectUrl . '/commits', [
            'project_id' => $this->projectId, 'project_token' => $this->projectCiToken
        ]);

        if ($curl->error) {
            throw new \Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
        }

        $response = $curl->response;

        $branchStatuses = $this->setBranchStatus($response);

        return $branchStatuses[$branch]['status'];
    }

    /**
     * @param $response
     *
     * @return array|null
     */
    protected function setBranchStatus($response)
    {
        $branchStatuses = [];

        foreach ($response as $oCommits) {
            $id = $oCommits->id;
            $branch = $oCommits->ref;
            $status = $oCommits->status;

            if (!isset($branchStatuses[$branch]) ||
                (isset($branchStatuses[$branch]) && $id > $branchStatuses[$branch]['id'])) {
                $branchStatuses[$branch] = ['id' => $id, 'status' => $status];
            }
        }

        if (! empty($branchStatuses)) {
            return $branchStatuses;
        }

        return null;
    }
}
