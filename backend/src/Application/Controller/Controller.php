<?php
declare(strict_types=1); 

namespace App\Application\Controller;

use Psr\Log\LoggerInterface; 
use Slim\Exception\HttpBadRequestException; 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;  
use Illuminate\Validation\Factory as ValidationFactory;

abstract class Controller
{
    protected LoggerInterface $logger;

    protected Request $request;

    protected Response $response;

    protected ValidationFactory $validator;

    protected array $args;

    public function __construct(LoggerInterface $logger, ValidationFactory $validator)
    {
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * Set the args
     */
    protected function setContext(Request $request, Response $response, array $args = []): void
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
    }

    /**
     * @return array|object
     */
    protected function getFormData()
    {
        return $this->request->getParsedBody();
    }

    /**
     * Perform Validation input
    */
    protected function validateRequest(array $data, array $rules, array $messages = []): array
    {
        $validator = $this->validator->make($data, $rules);
        
        if (!empty($messages)) {
            $validator->setCustomMessages($messages);
        }

        if ($validator->fails()) {
            return [
                'valid' => false,
                'errors' => $validator->errors()->toArray()
            ];
        }

        return [
            'valid' => true,
            'data' => $validator->validated()
        ];
    }

    /**
     * Validation Response
    */
    protected function respondWithValidationErrors(array $errors): Response
    {
        $payload = json_encode([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $errors
        ]);

        $this->response->getBody()->write($payload);
        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(422);
    }

    /**
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param array|object|null $data
     */
    protected function respondWithData($data = null, int $statusCode = 200): Response
    {
        $payload = new ControllerPayload($statusCode, $data);

        return $this->respond($payload);
    }

    protected function respond(ControllerPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($payload->getStatusCode());
    }

    /**
     * Respond with just a message
    */
    protected function respondWithMessage(string $message, int $statusCode = 200): Response
    {
        $data = ['message' => $message];
        return $this->respondWithData($data, $statusCode);
    }
    /**
     * Respond with an error
    */
    protected function respondWithError(Response $response, string $type, string $description, int $statusCode): Response
    {
        $error = new ControllerError($type, $description);
        $payload = new ControllerPayload($statusCode, null, $error);

        $response->getBody()->write(json_encode($payload, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($statusCode);
    }
}
