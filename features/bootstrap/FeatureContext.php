<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiMjMyNGI4MTQzOWEwN2M2NzhkNGIxM2NiMjA1NDA3MWRiNmQ5ZGY1OTNlYWFkYWQwNWVhZTJiMzVjNzJjNzg0NjE1ZDYyNGZmY2JjYTk4YjkiLCJpYXQiOjE1NzQ3MzIzMTQsIm5iZiI6MTU3NDczMjMxNCwiZXhwIjoxNjA2MzU0NzE0LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.W5_NHZtI1pnUStJK4BZ1GkaiVj2dCQhOWKLch91DxA66aSCQC9LFWcQiebq2sCJBCmQ6H2h34pw-nfLBrQixLRvbacuJiMRyFzgWD7xZ2s4rxsGCG-67frhMRXdwhy4ntSKwP7WXTVmmTuU2N5S9JpVFczn2IKGrSDe0pzEeoN_2lgCqZC_3INDvMSzafN55JhieRJWxIilzpzSCKKGFhh6Cb8HmLnPyge9xI89ZtMjfeLC_sv--DIYAcUQrQTw5awD1YRW9Lbz2jwNGGYX8tlhcFrMi-jbdtTVbBNM5hbak6R7TrOMbZLaHhup9BapLvNH-YDIbJc60JR4YgkIv27mEkKMJXsg_rubAMr_8hAgjsX-rJEd0_mBNFMg8LAsjPQv7sTZEpt7FjOxMxRMdgDDvK7ByyLIXfcC6pr467ixI7Uit5rKWIIaRHXrgDzHKOeD_IASd3dBAIgUbMRehfgzWZjuRxAUeAUZ6kklx8R1EdHtlb-HUe6mHZocH3kpqe5dFt5qN0f_yBR0czOYIjOsEtSbSaJ20PYTAMm7DS8MHUZXS7Z_KCvPjX-nGz7ucLIMYFK4M3XzvN8itSfsuXK-KwTHrsYiDhaCVyVg7L5SL3hQjAcn9Kfe3GS7b4nqMETRLayZXqCMhdSNnn3bgo3isOGURrbSJ-ng5ZaGnMkw";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://127.0.0.1:8000' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }

    /**
     * @Then the response contains :arg1 records
     */
    public function theResponseContainsRecords($arg1)
    {
        $data = json_decode($this->responseBody);
        $count = count($data);
        return ($count == $arg1);
    }

    /**
     * @Then the question contains a title of :arg1
     */
    public function theQuestionContainsATitleOf($arg1)
    {
        $data = json_decode($this->responseBody);
        if ($data->title != $arg1)
            throw new Exception("The title does not match");
    }
}